<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\CharacterDerangement;
use App\Entity\CharacterLesserTemplate;
use App\Entity\CharacterMerit;
use App\Entity\CharacterSpecialty;
use App\Entity\Derangement;
use App\Entity\Devotion;
use App\Entity\Ghoul;
use App\Entity\GhoulDiscipline;
use App\Entity\Human;
use App\Entity\Merit;
use App\Entity\Skill;
use App\Entity\Vampire;
use App\Entity\VampireDiscipline;

class CharacterService
{
  private DataService $dataService;
  private VampireService $vampireService;
  private MageService $mageService;
  // private WerewolfService $werewolfService;
  private CreationService $create;

  public function __construct(
    DataService $dataService,
    VampireService $vampireService,
    MageService $mageService,
    //WerewolfService $werewolfService,
    CreationService $create
  )
  {
    $this->create = $create;
    $this->dataService = $dataService;
    $this->vampireService = $vampireService;
    $this->mageService = $mageService;
  }

  public function editCharacter(Character $character, array $extraData): int
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
      $xp = (int)$extraData['xp']['spend'];
      $character->spendXp((int)$extraData['xp']['spend']);
      $isFree = false;
    } else {
      $isFree = true;
      $xp = 0;
    }
    $this->updateLogs($character, $extraData['xpLogs'], $isFree);
    $this->dataService->update($character);

    return $xp;
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
    ksort($sortedCharacters);

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
    $this->dataService->update($character);
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

  public function updateTrait(Character $character, \stdClass $data, bool $isLesser = false): void
  {
    if ($isLesser) {
      $character = $character->getLesserTemplate();
    }

    switch ($data->trait) {
      case 'willpower':
          $character->setCurrentWillpower(min($character->getWillpower(), $data->value));
        break;
      default:
        $setTrait = "set" . ucfirst($data->trait);
        $character->$setTrait($data->value);
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

  public function lesserTemplateAdd(Character $character, $name, array $data)
  {
    $template = new $name();
    $character->addLesserTemplate($template);

    switch ($template->getType()) {
      case 'ghoul':
        $template = $this->vampireService->ghoulify($character->getLesserTemplate(), $data[$template->getType()]);

        break;
      case 'innocents':
        $character->setSize($character->getSize() - 1);

        break;
    }
    if ($character->getLesserTemplate() === $template) {
      $this->dataService->add($template);
    }
    
    $this->dataService->update($template);
  }

  public function lesserTemplateRemove(Character $character, CharacterLesserTemplate $template)
  {
    $template->setIsActive(false);

    switch ($template->getType()) {
      case 'innocents':
        $character->setSize($character->getSize() + 1);
        break;
    }
    $this->dataService->update($character);
  }

  public function lesserTemplatesGetAllAvailable(?CharacterLesserTemplate $exception = null) : array
  {
    if (!is_null($exception)) {
      $exception = $exception::class;
    }
    $templates = [];
    $classes = array_filter(get_declared_classes(), fn($class) => is_subclass_of($class, CharacterLesserTemplate::class));
    foreach ($classes as $class) {
      // We don't add the current template in the list
      if ($class !== $exception) {
        $class = new $class();
        $templates["{$class->getType()}"] = get_class($class);
      }
    }

    ksort($templates);
    return $templates;
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
    $this->dataService->loadMeritsPrerequisites($character->getMerits());
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
        // (!$isCreation && $merit->isCreationOnly()) || // Creation merit, for now, allowed to everyone
        ($merit->getType() != "" && $merit->getType() != $character->getType()) || // Template of the character does not match the merit
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
      
      case 'mage':
        return $this->mageService->getSpecial($character);

      default:
        return null;
    }
  }

  public function removeAbility(Character $character, array $data): ?string
  {
    $infos = [];
    if (isset($data['element'])) {
      $element = $data['element'];
      $infos['name'] = $element;
    }
    $method = $data['method'];

    switch ($data['type']) {
      case 'attribute':
        $isDone = $this->removeAttribute($character, $method, $element, $infos);
        break;
      case 'skill':
        $isDone = $this->removeSkill($character, $method, $element, $infos);
        break;
      case 'merit':
        $isDone = $this->removeMerit($character, $method, $element, $infos);
        break;
      case 'specialty':
        $isDone = $this->removeSpecialty($character, $element, $infos);
        break;
      case 'willpower':
        $isDone = $this->reduceWillpower($character, $element, $infos);
        break;
      case 'derangement':
        $isDone = $this->removeDerangement($method, $element, $data, $infos);
        break;
      // Vampire
      case 'potency':
        $isDone = $this->reducePotency($character, $element, $infos);
        break;
      case 'discipline':
        $isDone = $this->removeDiscipline($character, $method, $element, $infos);
        break;
      case 'devotion':
        $isDone = $this->removeDevotion($character, $element, $infos);
        break;
      default:
        $isDone = false;
    }

    if (!$isDone) {

      return null;
    }

    $logs = [$data['method'] => [
      'type' => $data['type'],
      'info' => $infos,
    ]];
    $this->updateLogs($character, json_encode($logs));
    $this->dataService->flush();

    return $element;
  }

  private function removeAttribute(Character $character, string $method, &$element, &$infos) : bool
  {
    $attributes = $character->getAttributes();
    $value = $attributes->get($element);
    $infos['base'] = $value;
    if ($method == 'reduce' && $value > 0) {
      $newValue = $value - 1;
    } else {
      $newValue = 0;
    }
    $attributes->set($element, $newValue);

    return true;
  }

  private function removeSkill(Character $character, string $method, &$element, &$infos) : bool
  {
    $skills = $character->getSkills();
    $value = $skills->get($element);
    $infos['base'] = $value;
    if ($method == 'reduce' && $skills->get($element) > 0) {
      $newValue = $value - 1;
    } else {
      $newValue = 0;
    }
    $skills->set($element, $newValue);

    return true;
  }

  private function removeMerit(Character $character, string $method, &$element, &$infos) : bool
  {
    $merit = $this->dataService->find(CharacterMerit::class, $element);
    if ($merit instanceof CharacterMerit) {
      $element = $merit->getMeritName();
      $level = $merit->getLevel();
      $infos['base'] = $level;
      $infos['name'] = $element;
      if ($method == 'reduce' && $level > 1) {
        $merit->setLevel($level - 1);
      } else {
        $character->removeMerit($merit);
      }
      return true;
    }

    return false;
  }

  private function removeSpecialty(Character $character, &$element, &$infos) : bool
  {
    $specialty = $this->dataService->find(CharacterSpecialty::class, $element);
    if ($specialty instanceof CharacterSpecialty) {
      $element = $specialty->getName();
      $infos['name'] = "{$element} ({$specialty->getSkill()->getName()})";
      $character->removeSpecialty($specialty);

      return true;
    }

    return false;
  }

  private function reduceWillpower(Character $character, &$element, &$infos) : bool
  {
    $willpower = $character->getWillpower();
    if ($willpower > 1) {
      $infos['base'] = $willpower;
      $character->setWillpower($willpower - 1);
      $element = "willpower";

      return true;
    }

    return false;
  }

  private function removeDerangement(string $method, &$element, &$data, &$infos) : bool
  {
    $derangement = $this->dataService->find(CharacterDerangement::class, $element);
    if ($derangement instanceof CharacterDerangement) {
      $element = $derangement->getName();
      if ($method == 'reduce' && $derangement->getDerangement()->getPreviousAilment() instanceof Derangement) {
        $data['method'] = "derangement-reduce";
        $infos['name'] = $element;
        $derangement->setDerangement($derangement->getDerangement()->getPreviousAilment());
        $infos['replace'] = $derangement->getName();
      } else {
        $data['method'] = "derangement-remove";
        $infos['name'] = $element;
        $this->dataService->delete($derangement);
      }
      return true;
    }

    return false;
  }

  private function reducePotency(Vampire $character, &$element, &$infos) : bool
  {
    $potency = $character->getPotency();
    if ($potency > 1) {
      $infos['base'] = $potency;
      $character->setPotency($potency - 1);
      $element = "potency";

      return true;
    }

    return false;
  }

  /** Reduce/Remove a discipline for a Vampire or a Ghoul */
  private function removeDiscipline(Character $character, string $method, &$element, &$infos) : bool
  {
    if ($character instanceof Vampire) {
      $discipline = $this->dataService->find(VampireDiscipline::class, $element);
      if ($discipline instanceof VampireDiscipline) {
        $element = $discipline->getName();
        $level = $discipline->getLevel();
        $infos['base'] = $level;
        $infos['name'] = $element;
        if ($method == 'reduce' && $level > 1) {
          $discipline->setLevel($level - 1);
        } else {
          $character->removeDiscipline($discipline);
        }
        return true;
      }
    } else {
      $lesser = $character->getLesserTemplate();
      if ($lesser instanceof Ghoul) {
        $discipline = $this->dataService->find(GhoulDiscipline::class, $element);
        if ($discipline instanceof GhoulDiscipline) {
          $element = $discipline->getName();
          $level = $discipline->getLevel();
          $infos['base'] = $level;
          $infos['name'] = $element;
          if ($method == 'reduce' && $level > 1) {
            $discipline->setLevel($level - 1);
          } else {
            $lesser->removeDiscipline($discipline);
          }
          return true;
        }
      }
    }

    return false;
  }

  private function removeDevotion(Character $character, &$element, &$infos) : bool
  {
    $devotion = $this->dataService->find(Devotion::class, $element);
    if ($devotion instanceof Devotion) {
      $infos['name'] = $devotion->getName();
      if ($character instanceof Vampire) {
        $character->removeDevotion($devotion);
      } else {
        $lesser = $character->getLesserTemplate();
        if ($lesser instanceof Ghoul) {
          $lesser->removeDevotion($devotion);
        }
      }

      return true;
    }

    return false;
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

  public function getRemovableAttributes(Character $character)
  {
    $removables = [
      'attribute' => [],
      'skill' => [],
      'specialty' => ['label' => 'specialty.label.single', 'domain' => 'skill'],
      'merit' => [],
      'willpower' => ['label' => 'willpower.label', 'domain' => 'character'],
      'derangement' => [],
    ];
    switch ($character::class) {
      case Vampire::class:
        $removables = array_merge($removables, $this->vampireService->getRemovableAttributes());
        break;
      case Human::class:
        if ($character->getLesserTemplate()) {
          switch ($character->getLesserTemplate()::class) {
            case Ghoul::class:
              // for ghoul
              // $removables = array_merge($removables, $this->vampireService->getGhoulRemovableAttributes());
              break;
          }
        }
        break;
    }

    return $removables;
  }
}
