<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\Specialty;
use App\Entity\Skill;
use App\Entity\Merit;
use Doctrine\Persistence\ManagerRegistry;

class CreationService
{
  private $doctrine;
  private $create;

  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

  public function addMerits(Character $character, $formMerits)
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

  public function updateMerits(Character $character, $formMerits)
  {
    $merits = [];
    
    foreach ($formMerits as $key => $merit) {
      if (!empty($merit['level'])) {
        $merits[$key] = $merit;
      }
    }
    
    foreach ($merits as $id => $merit) {
      /** @var CharacterMerit $characterMerit */
      $characterMerit = $this->doctrine->getRepository(CharacterMerit::class)->find($id);
      $characterMerit->setLevel($merit['level']);
      if (isset($merit['details'])) {
        $characterMerit->setChoice($merit['details']);
      }
    }
  }

  public function addSpecialties(Character $character, $formSpecialties)
  {
    foreach ($formSpecialties as $skill => $skillSpec) {
      if (!empty($skillSpec)) {

        foreach ($skillSpec as $id => $name) {
          $skill = $this->doctrine->getRepository(Skill::class)->findOneBy(['name' => $skill]);
          $specialty = new Specialty($character, $skill, $name);
          
          $specialty->setCharacter($character);
          $character->addSpecialty($specialty);
          $this->doctrine->getManager()->persist($specialty);
        }
      }
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
    $character->setWillpower($character->getAttributes()->getResolve() + $character->getAttributes()->getComposure());
  }
}