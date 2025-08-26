<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Character;
use App\Entity\Chronicle;

use App\Entity\Mage;
use App\Entity\Arcanum;
use App\Entity\MageArcanum;
use App\Entity\MageOrder;
use App\Entity\MagicalPractice;
use App\Entity\Path;
use App\Entity\SpellRote;
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

    if (isset($form->getExtraData()['arcana'])) {
      $arcana = $form->getExtraData()['arcana'];
    }
    $startingMana = $character->getMoral();

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
    
    if ($mage->getLesserTemplate()) {
      $mage->getLesserTemplate()->setIsActive(false);
    }
    $this->dataService->save($mage);

    return true;
  }

  /**
   * @return array<string, array<int, object>>
   */
  public function getSpecial(Mage $mage): array
  {
    /** @var array<int, Arcanum> */
    $arcana = $this->filterArcana($this->dataService->findAll(Arcanum::class), $mage);
    $rotes = $this->filterRotes($this->dataService->findAll(SpellRote::class), $mage);
    return [
      'arcana' => $arcana,
      'rotes' => $rotes,
    ];
  }

  private function filterArcana(array $arcana, Mage $mage): array
  {
    foreach ($arcana as $key => $arcanum) {
      /** @var Arcanum $arcanum */
      if ($mage->hasArcanum($arcanum->getId())) {
        unset($arcana[$key]);
      }
    }

    return $arcana;
  }

  private function filterRotes(array $rotes, Mage $mage): array
  {
    foreach ($rotes as $key => $rote) {
      /** @var SpellRote $rote */
      if (
        // Not owned rote
        $mage->hasRote($rote) ||
        !is_null($rote->getHomebrewFor()) && $rote->getHomebrewFor() != $mage->getChronicle() ||
        !is_null($rote->getMageOrder()) && $rote->getMageOrder() != $mage->getOrder() 
      ) {
        unset($rotes[$key]);
      }
    }

    return $rotes;
  }

  /** @param array<string, mixed> $data */
  public function handleEdit(Mage $character, array $data): void
  {
    if (isset($data['gnosis']) && $data['gnosis'] > $character->getGnosis()) {
      $character->setGnosis((int)$data['gnosis']);
    }
    
    if (isset($data['arcanaUp'])) {
      foreach ($data['arcanaUp'] as $id => $level) {
        $arcanum = $character->getArcanum($id);
        if ($arcanum) {
          $arcanum->setLevel((int)$level);
        }
      }
    }
    if (isset($data['arcana'])) {
      $this->addArcana($character, $data['arcana']);
    }
    if (isset($data['rotes'])) {
      $this->addRotes($character, $data['rotes']);
    }
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
        //   $newArcanum = new ????Arcanum($character, $arcanum, (int)$level);
        // }
      } else {
        throw new \Exception("\$arcanum not an Arcanum");
      }
      $this->dataService->add($newArcanum);
      $character->addArcanum($newArcanum);
    }
  }

  /** @param array<int, int> $arcana */
  public function addRotes(Mage $character, array $rotes): void
  {
    foreach ($rotes as $roteId => $value) {
      $rote = $this->dataService->find(SpellRote::class, $roteId);
      if ($rote instanceof SpellRote) {
        if ($character instanceof Mage) {
          $character->addRote($rote);
        }
      } else {
        throw new \Exception("\$rote not a rote");
      }
    }
  }

  public function getRules(Chronicle $chronicle)
  {
    $rules = $chronicle->getRules('mage');

    // // We do not have any custom rules, or no custom rules for mage
    if (is_null($rules)) {
      $rules = [
        'maxMana' =>  [
          '1' => 10,
          '2' => 11,
          '3' => 12,
          '4' => 13,
          '5' => 14,
          '6' => 15,
          '7' => 20,
          '8' => 30,
          '9' => 50,
          '10' => 100,
        ],
        'maxManaPerTurn' => [
          '1' => 1,
          '2' => 1,
          '3' => 1,
          '4' => 2,
          '5' => 2,
          '6' => 3,
          '7' => 5,
          '8' => 7,
          '9' => 10,
          '10' => 15,
        ]
      ];
    }

    return $rules;
  }

  public function getRemovableAttributes()
  {
    $removables = [
      // 'gnosis' => ['label' => 'gnosis.label', 'domain' => 'mage'],
      // 'arcanum' => ['label' => 'label.single', 'domain' => 'arcanum'],
      // 'rote' => ['label' => 'devotion.label.single', 'domain' => 'arcanum'],
      // 'merit' => [],
      // 'willpower' => ['label' => 'willpower.label', 'domain' => 'character'],
      // 'derangement' => [],
    ];

    return $removables;
  }
}
