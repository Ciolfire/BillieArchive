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

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

class VampireService
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  public function getSpecial(Vampire $vampire)
  {
    $disciplines = $this->doctrine->getRepository(Discipline::class)->findAll();
      foreach ($disciplines as $key => $discipline) {
        /** @var Discipline $discipline */
        if ($vampire->hasDiscipline($discipline->getId()) || !$discipline->isAvailable($vampire->getChronicle())) {
          unset($disciplines[$key]);
        }
      }
    $devotions = $this->doctrine->getRepository(Devotion::class)->findAll();
    foreach ($devotions as $key => $devotion) {
      /** @var Devotion $devotion */
      if ($vampire->hasDevotion($devotion->getId()) || !$devotion->isAvailable($vampire->getChronicle())) {
        unset($disciplines[$key]);
      }
    }
    return [
      'disciplines' => $disciplines,
      'devotions' => $devotions,
    ];
  }

  public function embrace(Character $character, FormInterface $form)
  {
    $connection = $this->doctrine->getConnection();

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
    $this->doctrine->resetManager();
    /** @var Vampire $vampire */
    $vampire = $this->doctrine->getRepository(Vampire::class)->find($character->getId());
    $vampire->addAttribute($data['attribute']->getIdentifier(), 1);
    $this->addDiscipline($vampire, $form->getExtraData()['disciplines']);
    $this->doctrine->getManager()->persist($vampire);
    $this->doctrine->getManager()->flush();
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
      $discipline = $this->doctrine->getRepository(Discipline::class)->find($id);
      $newDiscipline = new VampireDiscipline($vampire, $discipline, $level);
      $this->doctrine->getManager()->persist($newDiscipline);
      $vampire->addDiscipline($newDiscipline);
    }
  }

  public function getBloodlines($item = null)
  {
    /** @var ClanRepository $repo */
    $repo = $this->doctrine->getRepository(Clan::class);
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