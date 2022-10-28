<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Merit;
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
    foreach ($logs as $value) {
      $value->timestamp = time();
      if ($isFree) {
        $value->info->cost = 0;
      }
    }
    $logs = json_decode(json_encode($logs), true);
    $oldlogs = $character->getExperienceLogs();
    $logs = array_merge($oldlogs, $logs);
    $character->setExperienceLogs($logs);
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

  public function updateWillpower(Character $character, int $willpower)
  {
    $character->setWillpower($willpower);
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

  public function filterMerits(Character $character, $isCreation = true)
  {
    $merits = $this->doctrine->getRepository(Merit::class)->findAll();

    foreach ($merits as $key => $merit) {
      /** @var Merit $merit */
      if ($merit->getIsUnique() && $character->hasMerit($merit->getId())) {
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