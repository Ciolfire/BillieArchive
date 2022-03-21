<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\Specialty;
use App\Entity\Merit;
use Doctrine\Persistence\ManagerRegistry;

class CreationService
{
  private $doctrine;
  private $create;

  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

  public function updateMerits(Character $character, $formMerits)
  {
    $merits = [];
    
    foreach ($formMerits as $key => $merit) {
      if (!empty($merit['level'])) {
        $merits[$key] = $merit;
      }
    }
    
    foreach ($merits as $id => $merit) {
      $entityMerit = $this->doctrine->getRepository(Merit::class)->find($id);
      $characterMerit = new CharacterMerit;
      $characterMerit->setMerit($entityMerit);
      $characterMerit->setLevel(intval($merit['level']));
      if (isset($merit['details'])) {
        $characterMerit->setChoice($merit['details']);
      }
      $character->addMerit($characterMerit);
      $this->doctrine->getManager()->persist($characterMerit);
    }
  }

  public function getSpecialties(Character $character, $form): void
  {
    $specialties = [$form['specialty1'], $form['specialty2'], $form['specialty3']];

    foreach ($specialties as $specialty) {
      $specialty = $specialty->getData();
      /** @var Specialty $specialty */
      $specialty->setCharacter($character);
      $character->addSpecialty($specialty);
    }
  }


  public function getWillpower(Character $character): void
  {
    $character->setWillpower($character->getResolve() + $character->getComposure());
  }
}