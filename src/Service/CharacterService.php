<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\CharacterDerangement;
use App\Entity\CharacterMerit;
use App\Entity\CharacterSpecialty;
use App\Entity\Derangement;
use App\Entity\Merit;
use App\Entity\Skill;
use App\Entity\Vampire;

class CharacterService
{
  private DataService $dataService;
  private VampireService $vampireService;
  private CreationService $create;

  public function __construct(DataService $dataService, VampireService $vampireService, CreationService $create)
  {
    $this->create = $create;
    $this->dataService = $dataService;
    $this->vampireService = $vampireService;
  }

  public function editCharacter(Character $character, array $extraData)
  {
    if (is_null($character->getLookAge()) && !is_null($character->getAge())) {
      $character->setLookAge($character->getAge());
    }
    if (isset($extraData['merits'])) {
      $this->create->addMerits($character, $extraData['merits']);
    }
    if (isset($extraData['meritsUp'])) {
      $this->create->updateMerits($extraData['meritsUp']);
    }
    if (isset($extraData['specialties'])) {
      $this->create->addSpecialties($character, $extraData['specialties']);
    }
    if (isset($extraData['willpower']) && $extraData['willpower'] > $character->getWillpower()) {
      $this->updateWillpower($character, (int)$extraData['willpower']);
    }
    switch ($character->getType()) {
      case 'vampire':
        /** @var Vampire $character */
        $this->vampireService->handleEdit($character, $extraData);
        break;
      case 'ghoul':
        $this->vampireService->handleGhoulEdit($character->getLesserTemplate(), $extraData);
      default:
        # code...
        break;
    }
    if (isset($extraData['xp']) && !isset($extraData['isFree'])) {
      $character->spendXp((int)$extraData['xp']['spend']);
      $isFree = false;
    } else {
      $isFree = true;
    }
    $this->updateLogs($character, $extraData['xpLogs'], $isFree);
    $this->dataService->flush();
  }

  public function sortCharacters(Character ...$characters)
  {
    $sortedCharacters = [];
    foreach ($characters as $character) {
      $type = $character->getType();
      if (!isset($sortedCharacters[$type])) {
        $sortedCharacters[$type] = [];
      }
      $sortedCharacters[$type][] = $character;
    }
    return $sortedCharacters;
  }

  public function takeWound(Character $character, int $value): void
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
    $this->dataService->flush();
  }

  public function healWound(Character $character, int $value): void
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
    $this->dataService->flush();
  }

  public function updateLogs(Character $character, string $logs, bool $isFree = false): void
  {
    $logs = json_decode($logs);
    foreach ($logs as $key => $entry) {
      if (is_null($entry)) {
        unset($logs->$key);
      } else if ($isFree) {
        $entry->info->cost = 0;
      }
    }
    $logs = json_encode($logs);
    if ($logs !== false) {
      $logs = json_decode($logs, true);
    }
    // We only handle it if something has changed
    if (!empty($logs) && is_array($logs)) {
      $time = (string)time();
      /** @var array<string, mixed> */
      $logs = [$time => $logs];
      $oldlogs = $character->getExperienceLogs();
      if (!empty($oldlogs)) {
        $logs = $logs + $oldlogs;
      }
      $character->setExperienceLogs($logs);
    }
  }

  public function updateTrait(Character $character, \stdClass $data): void
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
        $getTrait = "get" . ucfirst($data->trait);
        $setTrait = "set" . ucfirst($data->trait);
        if ($data->value == 1) {
          $character->$setTrait($character->$getTrait() + 1);
        } else {
          $character->$setTrait(max(0, $character->$getTrait() - 1));
        }
        break;
    }
    $this->dataService->flush();
  }

  public function updateLesserTrait(Character $character, \stdClass $data): void
  {
    $character = $character->getLesserTemplate();

    switch ($data->trait) {
      default:
        $getTrait = "get" . ucfirst($data->trait);
        $setTrait = "set" . ucfirst($data->trait);
        if ($data->value == 1) {
          $character->$setTrait($character->$getTrait() + 1);
        } else {
          $character->$setTrait(max(0, $character->$getTrait() - 1));
        }
        break;
    }
    $this->dataService->flush();
  }

  public function updateWillpower(Character $character, int $value): void
  {
    $character->setWillpower($value);
  }

  public function updateExperience(Character $character, \stdClass $data): ?int
  {


    if ($data->method == "add") {
      $total = $character->getXpTotal();
      $new = $total + (int)$data->value;
      $character->setXpTotal($new);
      if ($new > $total) {
        $action = 'add';
      } else {
        $action = 'remove';
      }
      $logs = ['xp' => [
        'type' => 'xp',
        'info' => [
          'action' => $action,
          'value' => (int)$data->value,
          'base' => $total,
          'new' => $new,
        ],
      ]];
      $this->updateLogs($character, json_encode($logs), false);
      $this->dataService->flush();
    } else {
      $new = null;
    }



    return $new;
  }

  /**
   *  @return array<string, array<string, array<string, string|null>>>
   */
  public function getSortedAttributes(): array
  {
    $sortedAttributes = [];
    $attributes = $this->dataService->findAll(Attribute::class);
    foreach ($attributes as $attribute) {
      /** @var Attribute $attribute */
      if (!isset($sortedAttributes[$attribute->getCategory()])) {
        $sortedAttributes[$attribute->getCategory()] = [];
      }
      $sortedAttributes[$attribute->getCategory()][$attribute->getType()] = ['id' => $attribute->getIdentifier(), 'name' => $attribute->getName()];
    }

    return $sortedAttributes;
  }

  /**
   *  @return array<string, array<string, string|null>>
   */
  public function getSortedSkills(): array
  {
    $sortedSkills = [];
    $skills = $this->dataService->findAll(Skill::class);
    foreach ($skills as $skill) {
      /** @var Skill $skill */
      if (!isset($sortedSkills[$skill->getCategory()])) {
        $sortedSkills[$skill->getCategory()] = [];
      }
      $sortedSkills[$skill->getCategory()][$skill->getIdentifier()] = $skill->getName();
    }

    return $sortedSkills;
  }

  public function loadMerits(Character $character): array
  {
    $merits = $this->filterMerits($character, false);
    $this->dataService->loadMeritsPrerequisites($character->getMerits(), 'character');
    $this->dataService->loadMeritsPrerequisites($merits);

    return $merits;
  }

  /** Remove all non valid merits 
   *  @return array<int, object>
   */
  public function filterMerits(Character $character, bool $isCreation = true): array
  {
    $chronicle = $character->getChronicle();
    $merits = $this->dataService->findAll(Merit::class);

    foreach ($merits as $key => $merit) {
      /** @var Merit $merit */
      if (
        ($merit->isUnique() && !is_null($character->hasMerit($merit->getId()))) || // Character already has this merit
        (!$isCreation && $merit->isCreationOnly()) || // Creation merit
        ($merit->getType() != "" && $character->getType() != $merit->getType()) || // Template of the character does not match the merit
        (!is_null($merit->getHomebrewFor()) && $merit->getHomebrewFor() !== $chronicle) // Homebrew merit, only show for the chronicle 
      ) {
        unset($merits[$key]);
      }
    }
    return $merits;
  }

  /**
   * @return array<string, array<int, object>>|null
   */
  public function getSpecial(Character $character): ?array
  {
    switch ($character->getType()) {
      case 'vampire':
        /** @var Vampire $character */
        return $this->vampireService->getSpecial($character);

      case 'ghoul':
        /** @var Vampire $character */
        return $this->vampireService->getGhoulSpecial($character->getLesserTemplate());

      default:
        return null;
    }
  }

  public function removeAbility(Character $character, array $data): bool
  {
    $infos = [];
    if (isset($data['element'])) {
      $element = $data['element'];
      $infos['name'] = $element;
    }
    $method = $data['method'];

    switch ($data['type']) {
      case 'attribute':
        $attributes = $character->getAttributes();
        $value = $attributes->get($element);
        $infos['base'] = $value;
        if ($method == 'reduce' && $value > 0) {
          $newValue = $value - 1;
        } else {
          $newValue = 0;
        }
        $attributes->set($element, $newValue);
        break;
      case 'skill':
        $skills = $character->getSkills();
        $value = $skills->get($element);
        $infos['base'] = $value;
        if ($method == 'reduce' && $skills->get($element) > 0) {
          $newValue = $value - 1;
        } else {
          $newValue = 0;
        }
        $skills->set($element, $newValue);
        break;
      case 'merit':
        $merit = $this->dataService->find(CharacterMerit::class, $element);

        if ($merit instanceof CharacterMerit) {
          $level = $merit->getLevel();
          $infos['base'] = $level;
          $infos['name'] = $merit->getMeritName();
          if ($method == 'reduce' && $level > 1) {
            $merit->setLevel($level - 1);
          } else {
            $character->removeMerit($merit);
          }
        }
        break;
      case 'specialty':
        $specialty = $this->dataService->find(CharacterSpecialty::class, $element);
        $infos['name'] = "{$specialty->getName()} ({$specialty->getSkill()->getName()})";
        if ($specialty instanceof CharacterSpecialty) {
          $character->removeSpecialty($specialty);
        } else {

          return false;
        }
        break;
      case 'willpower':
        $willpower = $character->getWillpower();
        if ($willpower > 1) {
          $infos['base'] = $willpower;
          $character->setWillpower($willpower - 1);
        } else {

          return false;
        }
        break;
      case 'derangement':
        $derangement = $this->dataService->find(CharacterDerangement::class, $element);
        if ($method == 'reduce' && !is_null($derangement->getDerangement()->getPreviousAilment())) {
          $data['method'] = "derangement-reduce";
          $infos['name'] = $derangement->getName();
          $derangement->setDerangement($derangement->getDerangement()->getPreviousAilment());
          $infos['replace'] = $derangement->getName();
        } else {
          $data['method'] = "derangement-remove";
          $infos['name'] = $derangement->getName();
          $derangement->getCharacter()->removeDerangement($derangement);
        }
        break;
      default:

        return false;
    }

    $logs = [$data['method'] => [
      'type' => $data['type'],
      'info' => $infos,
    ]];
    $this->updateLogs($character, json_encode($logs));
    $this->dataService->flush();

    return true;
  }

  public function moralityIncrease(Character $character, bool $isDerangementRemoved, bool $isFree): void
  {
    $base = $character->getMoral();
    $cost = $base * 3;
    $derangementName = '';
    $derangement = $character->getMoralityDerangement($base);
    if ($isDerangementRemoved && !is_null($derangement)) {
      $character->removeDerangement($derangement);
      $derangementName = $derangement->getDerangement()->getName();
      if (!is_null($derangement->getDetails())) {
        $derangementName .= " ({$derangement->getDetails()})";
      }
    }
    $character->setMoral($base + 1);
    if (!$isFree) {
      $character->setXpUsed($character->getXpUsed() + $cost);
    }
    $logs = ['morality-increase' => [
      'type' => 'morality',
      'info' => [
        'action' => 'increase',
        'removed' => $derangementName,
        'base' => $base,
        'value' => $base + 1,
        'cost' => $cost,
      ],
    ]];
    $this->updateLogs($character, json_encode($logs), $isFree);
    $this->dataService->flush();
  }

  public function moralityDecrease(Character $character, int $derangementId, string $details): void
  {
    $base = $character->getMoral();
    $character->setMoral($base - 1);
    $gained = '';
    if (0 != $derangementId) {
      if (!is_null($character->getMoralityDerangement($character->getMoral()))) {
        $character->removeDerangement($character->getMoralityDerangement($character->getMoral()));
      }
      $derangement = $this->dataService->findOneBy(Derangement::class, ['id' => $derangementId]);
      if (!is_null($derangement) && $character->getMoral() < 7) {
        $charDerangement = new CharacterDerangement($character, $details, $character->getMoral(), $derangement);
        $character->addDerangement($charDerangement);
        $this->dataService->add($charDerangement);
        $gained = $derangement->getName();
        if (!empty($details)) {
          $gained .= " ({$details})";
        }
      }
    }
    $logs = ['morality-increase' => [
      'type' => 'morality',
      'info' => [
        'action' => 'reduce',
        'gained' => "{$gained}",
        'base' => $base,
        'value' => $base - 1,
      ],
    ]];
    $this->updateLogs($character, json_encode($logs));
    $this->dataService->flush();
  }

  public function newCharacterDerangement(Character $character, int $derangementId, string $details): void
  {
    $derangement = $this->dataService->findOneBy(Derangement::class, ['id' => $derangementId]);
    if (!is_null($derangement)) {
      $charDerangement = new CharacterDerangement($character, $details, null, $derangement);
      $character->addDerangement($charDerangement);
      $this->dataService->add($charDerangement);
      $derangementName = $derangement->getName();
      if (!empty($details)) {
        $derangementName .= " ({$details})";
      }
      $logs = ['derangement' => [
        'type' => 'derangement',
        'info' => [
          'name' => $derangementName
        ],
      ]];
      $this->updateLogs($character, json_encode($logs));
    }
    $this->dataService->flush();
  }
}
