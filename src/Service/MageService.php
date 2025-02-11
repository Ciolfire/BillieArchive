<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Description;

use App\Entity\Mage;
use App\Entity\Arcana;
use App\Entity\Arcanum;
use App\Entity\MageArcanum;
use App\Entity\MageOrder;
use App\Entity\Path;
use Symfony\Component\Form\FormInterface;

class MageService
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  public function awaken(Character $character, FormInterface $form): bool
  {
    $connection = $this->dataService->getConnection();
    $data = $form->getData();
    
    if (!$data['path'] instanceof Path) {

      return false;
    }

    $arcana = $form->getExtraData()['arcana'];
    $startingMana = $character->getMoral();
    
    // dd("processing", $arcana, $form, $data['path']->getAttribute()->getIdentifier());
    
    // $lesser = $character->findLesserTemplate("LESSER");
    // // IF IT'S A LESSER TEMPLATE, MAYBE NEED TO KEEP THE ARCANA/WHATEVER/...? Didn't work with entity, had to convert to array, but well, it works.
    // if ($lesser instanceof LESSER) {
    //   $result = $this->awakenFromLESSER($lesser, $powers);
    //   $powers = $result['powers'];
    // }

    // The human is becoming more...
    $nativeQuery = $connection->prepare("UPDATE `characters` SET type='mage' WHERE id = :id");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->executeStatement();
    // ... as a Mage for the rest of her life
    $nativeQuery = $connection->prepare("INSERT IGNORE INTO `mage`(`id`, `path_id`, `order_id`, `gnosis`, `mana`) VALUES (:id, :path, :order, 1, $startingMana)");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->bindValue('path', $data['path']->getId());
    if ($data['order'] instanceof MageOrder) {
      $nativeQuery->bindValue('order', $data['order']->getId());
    } else {
      $nativeQuery->bindValue('order', null);
    }
    $nativeQuery->executeStatement();
    // // We force the change to the manager, to avoid conflict from memory (?)
    $this->dataService->reset();
    $mage = $this->dataService->find(Mage::class, $character->getId());
    // Bonus attribute and starting Arcana
    $mage->addAttribute($data['path']->getAttribute()->getIdentifier(), 1);
    $this->addArcana($mage, $arcana);

    // Should we really ? There is potential loss of informations there
    // $mage->cleanLesserTemplates();
    $this->dataService->save($mage);

    return true;
  }

  /**
   * @return array<string, array<int, object>>
   */
  public function getSpecial(Mage $mage): array
  {
    /** @var array<int, Arcanum> */
    $arcana = $this->dataService->findAll(Arcanum::class);
    // $arcana = $this->dataService->findBy(Arcanum::class, ['isCoil' => false, 'isThaumaturgy' => false, 'isSorcery' => false]);
    // /** @var array<int, Discipline> */
    // $sorcery = $this->dataService->findBy(Discipline::class, ['isSorcery' => true]);
    // $sorcery = $this->filterDisciplines($sorcery, $vampire);
    // /** @var array<int, Discipline> */
    // $coils = $this->dataService->findBy(Discipline::class, ['isCoil' => true]);
    // $coils = $this->filterDisciplines($coils, $vampire);
    // /** @var array<int, Discipline> */
    // $thaumaturgy = $this->dataService->findBy(Discipline::class, ['isThaumaturgy' => true]);
    // $thaumaturgy = $this->filterDisciplines($thaumaturgy, $vampire);

    // $devotions = $this->dataService->findBy(Devotion::class, [], ['name' => 'ASC']);
    // foreach ($devotions as $key => $devotion) {
    //   /** @var Devotion $devotion */
    //   if ($vampire->hasDevotion($devotion->getId()) || !$devotion->isAvailable($vampire->getChronicle())) {
    //     unset($devotions[$key]);
    //   }
    //   $this->dataService->loadPrerequisites($devotion);
    // }
    return [
      'arcana' => $arcana,
      // 'spells' => $spells,
    ];
  }

  /** @param array<string, mixed> $data */
  public function handleEdit(Mage $mage, array $data): void
  {

    if (isset($data['gnosis']) && $data['gnosis'] > $mage->getGnosis()) {
      $mage->setGnosis((int)$data['gnosis']);
    }
    
    // if (isset($data['disciplinesUp'])) {
    //   foreach ($data['disciplinesUp'] as $id => $level) {
    //     $discipline = $mage->getDiscipline($id);
    //     if ($discipline) {
    //       $discipline->setLevel((int)$level);
    //     }
    //   }
    // }
    // if (isset($data['disciplines'])) {
    //   $this->addDisciplines($mage, $data['disciplines']);
    // }
    // if (isset($data['devotions'])) {
    //   $this->addDevotions($mage, $data['devotions']);
    // }

    // if (isset($data['rituals'])) {
    //   $this->addRituals($mage, $data['rituals']);
    // }
  }

  /** @param array<int, int> $arcana */
  public function addArcana(Mage $character, array $arcana): void
  {
    foreach ($arcana as $id => $level) {
      $arcanum = $this->dataService->find(Arcanum::class, $id);
      if ($arcanum instanceof Arcanum) {
        if ($character instanceof Mage) {
          $newArcanum = new MageArcanum($character, $arcanum, (int)$level);
        }
        // else {
        //   $newDiscipline = new GhoulDiscipline($character, $discipline, (int)$level);
        // }
      } else {
        throw new \Exception("\$discipline not a Discipline");
      }
      $this->dataService->add($newArcanum);
      $character->addArcanum($newArcanum);
    }
  }

  public function getRules(Chronicle $chronicle)
  {
    $rules = $chronicle->getRules('mage');

    // // We do not have any custom rules, or no custom rules for vampire
    // if (is_null($rules)) {
    //   $rules = [
    //     'maxVitae' =>  [
    //       '1' => 10,
    //       '2' => 11,
    //       '3' => 12,
    //       '4' => 13,
    //       '5' => 14,
    //       '6' => 15,
    //       '7' => 20,
    //       '8' => 30,
    //       '9' => 50,
    //       '10' => 100,
    //     ],
    //     'maxVitaePerTurn' => [
    //       '1' => 1,
    //       '2' => 1,
    //       '3' => 1,
    //       '4' => 2,
    //       '5' => 2,
    //       '6' => 3,
    //       '7' => 5,
    //       '8' => 7,
    //       '9' => 10,
    //       '10' => 15,
    //     ]
    //   ];
    // }

    return $rules;
  }

  public function getRemovableAttributes()
  {
    $removables = [
      // 'potency' => ['label' => 'potency.label', 'domain' => 'vampire'],
      // 'discipline' => ['label' => 'label.single', 'domain' => 'discipline'],
      // 'devotion' => ['label' => 'devotion.label.single', 'domain' => 'discipline'],
      // 'merit' => [],
      // 'willpower' => ['label' => 'willpower.label', 'domain' => 'character'],
      // 'derangement' => [],
    ];

    return $removables;
  }
}
