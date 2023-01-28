<?php

namespace App\Service;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Merit;
use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;

class CharacterService
{
  private $doctrine;
  private $vService;

  public function __construct(EntityManagerInterface $entityManager, VampireService $vService)
  {
    $this->doctrine = $entityManager;
    $this->vService = $vService;
  }

  public function takeWound(Character $character, int $value)
  {
    $wounds = $character->getWounds();
    switch ($value) {
      case 1:
        $wounds['B']++;
        break;
      case 2:
        if ($wounds['B'] > 0) {
          $wounds['B']--;
          $wounds['L']++;
        }
        break;
      case 3:
        if ($wounds['L'] > 0) {
          $wounds['L']--;
          $wounds['A']++;
        }
        break;
      default:
        if ($wounds['A'] > 0) {
          $wounds['A']--;
        }
        break;
    }
    $character->setWounds($wounds);
    $this->doctrine->flush();
  }

  public function healWound(Character $character, int $value)
  {
    $wounds = $character->getWounds();
    switch ($value) {
      case 0:
        if ($wounds['B'] > 0) {
          $wounds['B']--;
        }
        break;
      case 1:
        if ($wounds['L'] > 0) {
          $wounds['L']--;
          $wounds['B']++;
        }
        break;
      case 2:
        if ($wounds['A'] > 0) {
          $wounds['A']--;
          $wounds['L']++;
        }
        break;
    }
    $character->setWounds($wounds);
    $this->doctrine->flush();
  }

  public function updateLogs(Character $character, $logs, $isFree) {
    $logs = json_decode($logs);
    foreach ($logs as $key => $entry) {
      if (is_null($entry)) {
        unset($logs->$key);
      } else if ($isFree) {
        $entry->info->cost = 0;
      }
    }
    $logs = json_decode(json_encode($logs), true);
    // We only handle it if something has changed
    if (!empty($logs)) {
      $time = time();
      $logs = ["{$time}" => $logs];
      $oldlogs = $character->getExperienceLogs();
      if (!empty($oldlogs)) {
        $logs = $logs + $oldlogs;
      }
      $character->setExperienceLogs($logs);
    }
  }

  public function updateTrait(Character $character, $data)
  {
    switch ($data->trait) {
      case 'willpower':
        if ($data->value == 1) {
          $character->setCurrentWillpower(min($character->getWillpower(), $character->getCurrentWillpower() + 1));
        } else if ($data->value == 0) {
          $character->setCurrentWillpower(max(0, $character->getCurrentWillpower() - 1));
        }
        break;
      default:
        $getTrait = "get".ucfirst($data->trait);
        $setTrait = "set".ucfirst($data->trait);
        if ($data->value == 1) {
          $character->$setTrait($character->$getTrait() + 1);
        } else {
          $character->$setTrait(max(0, $character->$getTrait() - 1));
        }
        break;
      }
    $this->doctrine->flush();
  }

  public function updateWillpower(Character $character, int $modifier)
  {
    $character->setWillpower($character->getWillpower() + $modifier);
  }

  public function updateExperience(Character $character, $data)
  {
    if ($data->method == "add") {
      $total = $character->getXpTotal();
      $new = $total + $data->value;
      $character->setXpTotal($new);
      $this->doctrine->flush();

      return $new;
    }
  }

  public function getSortedAttributes() : array
  {
    $sortedAttributes = [];
    $attributes = $this->doctrine->getRepository(Attribute::class)->findAll();
    foreach ($attributes as $attribute) {
      /** @var Attribute $attribute */
      if (!isset($sortedAttributes[$attribute->getCategory()])) {
        $sortedAttributes[$attribute->getCategory()] = [];
      }
      $sortedAttributes[$attribute->getCategory()][$attribute->getType()] = ['id' => $attribute->getIdentifier(), 'name' => $attribute->getName()];
    }

    return $sortedAttributes;
  }

  public function getSortedSkills() : array
  {
    $sortedSkills = [];
    $skills = $this->doctrine->getRepository(Skill::class)->findAll();
    foreach ($skills as $skill) {
      /** @var Skill $skill */
      if (!isset($sortedSkills[$skill->getCategory()])) {
        $sortedSkills[$skill->getCategory()] = [];
      }
      $sortedSkills[$skill->getCategory()][$skill->getIdentifier()] = $skill->getName();
    }

    return $sortedSkills;
  }

  /** Remove all non valid merits */
  public function filterMerits(Character $character, $isCreation = true)
  {
    $merits = $this->doctrine->getRepository(Merit::class)->findAll();

    foreach ($merits as $key => $merit) {
      /** @var Merit $merit */
      if ($merit->getIsUnique() && !is_null($character->hasMerit($merit->getId()))) {
        // Character already has this merit, we remove it from the list
        unset($merits[$key]);
      } else if (!$isCreation && $merit->getIsCreationOnly()) {
        // Level 1 merit
        unset($merits[$key]);
      }
      else if ($merit->getType() != "" && $character->getType() != $merit->getType()) {
        unset($merits[$key]);
      }
    }
    return $merits;
  }

  public function getSpecial(Character $character)
  {
    if ($character->getType() == 'vampire') {
      return $this->vService->getSpecial($character);
    } else {

      return null;
    }
  }
}