<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Vampire;
use App\Entity\VampireDiscipline;
use App\Entity\Discipline;
use App\Entity\Merit;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

class VampireService
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
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
    $nativeQuery = $connection->prepare("INSERT IGNORE INTO `vampire`(`id`, `clan_id`, `sire`, `apparant_age`, `potency`, `vitae`) VALUES (:id, :clan, :sire, :age, 1, 1)");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->bindValue('clan', $data['clan']->getId());
    $nativeQuery->bindValue('sire', $data['sire']);
    $nativeQuery->bindValue('age', $data['age']);
    $nativeQuery->executeStatement();
    // We force the change to the manager, to avoid fetching from memory (?)
    $this->doctrine->resetManager();
    $vampire = $this->doctrine->getRepository(Vampire::class)->find($character->getId());
    $vampire->addAttribute($data['attribute']->getName(), 1);
    foreach ($form->getExtraData()['disciplines'] as $id => $level) {
      $discipline = $this->doctrine->getRepository(Discipline::class)->find($id);
      $newDiscipline = new VampireDiscipline($vampire, $discipline, $level);
      $this->doctrine->getManager()->persist($newDiscipline);
      $vampire->addDiscipline($newDiscipline);
    }
    $this->doctrine->getManager()->persist($vampire);
    $this->doctrine->getManager()->flush();
  }
}