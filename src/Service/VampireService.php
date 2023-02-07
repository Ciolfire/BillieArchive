<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\Clan;
use App\Entity\Devotion;
use App\Entity\Vampire;
use App\Entity\VampireDiscipline;
use App\Entity\Discipline;
use App\Repository\ClanRepository;

use Symfony\Component\Form\FormInterface;

class VampireService
{
  private $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  public function getSpecial(Vampire $vampire)
  {
    $disciplines = $this->dataService->findAll(Discipline::class);
      foreach ($disciplines as $key => $discipline) {
        /** @var Discipline $discipline */
        if ($vampire->hasDiscipline($discipline->getId()) || !$discipline->isAvailable($vampire->getChronicle())) {
          unset($disciplines[$key]);
        }
      }
    $devotions = $this->dataService->findAll(Devotion::class);
    foreach ($devotions as $key => $devotion) {
      /** @var Devotion $devotion */
      if ($vampire->hasDevotion($devotion->getId()) || !$devotion->isAvailable($vampire->getChronicle())) {
        unset($devotions[$key]);
      }
      foreach ($devotion->getprerequisites() as $prerequisite) {
        $prerequisite->setEntity($this->dataService->findOneBy($prerequisite->getType(), ['id' => $prerequisite->getEntityId()]));
      }
    }
    return [
      'disciplines' => $disciplines,
      'devotions' => $devotions,
    ];
  }

  public function embrace(Character $character, FormInterface $form)
  {
    $connection = $this->dataService->getConnection();

    $data = $form->getData();

    // The human is gone forever...
    // $nativeQuery = $connection->prepare("DELETE FROM `human` WHERE id = :id");// $nativeQuery->bindValue('id', $character->getId());// $nativeQuery->executeStatement();
    $nativeQuery = $connection->prepare("UPDATE `characters` SET type='vampire' WHERE id = :id");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->executeStatement();
    // ...But the Vampire rise for eternity
    $nativeQuery = $connection->prepare("INSERT IGNORE INTO `vampire`(`id`, `clan_id`, `sire`, `apparent_age`, `potency`, `vitae`) VALUES (:id, :clan, :sire, :age, 1, 1)");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->bindValue('clan', $data['clan']->getId());
    $nativeQuery->bindValue('sire', $data['sire']);
    $nativeQuery->bindValue('age', $data['age']);
    $nativeQuery->executeStatement();
    // We force the change to the manager, to avoid fetching from memory (?)
    $this->dataService->reset();
    /** @var Vampire $vampire */
    $vampire = $this->dataService->find(Vampire::class, $character->getId());
    $vampire->addAttribute($data['attribute']->getIdentifier(), 1);
    $this->addDiscipline($vampire, $form->getExtraData()['disciplines']);
    $this->dataService->save($vampire);
  }

  public function handleEdit(Vampire $vampire, array $data)
  {
    foreach ($data['disciplinesUp'] as $id => $level) {
      $discipline = $vampire->getDiscipline($id);
      $discipline->setLevel($level);
    }
    if (isset($data['disciplines'])) {
      $this->addDiscipline($vampire, $data['disciplines']);
    }
    if (isset($data['potency']) && $data['potency'] > $vampire->getPotency()) {
      $vampire->setPotency($data['potency']);
    }
  }

  public function addDiscipline(Vampire $vampire, array $disciplines)
  {
    foreach ($disciplines as $id => $level) {
      $discipline = $this->dataService->find(Discipline::class, $id);
      $newDiscipline = new VampireDiscipline($vampire, $discipline, $level);
      $this->dataService->add($newDiscipline);
      $vampire->addDiscipline($newDiscipline);
    }
  }

  public function getBloodlines($item = null)
  {
    /** @var ClanRepository $repo */
    $repo = $this->dataService->getRepository(Clan::class);
    if ($item instanceof Book) {

      return $repo->findByBook($item);
    } else {

      return $repo->findAllBloodlines();
    }
  }

  /** Save and or edit a clan/bloodline */
  public function handleClan($isNew = true, $isBloodline = false) {
  }
}