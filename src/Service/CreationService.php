<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\CharacterSpecialty;
use App\Entity\Skill;
use App\Entity\Merit;
use App\Entity\References\MeritReferences;
use Doctrine\Persistence\ManagerRegistry;

class CreationService
{
  private $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
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
      // We take advantage of the fact that a string with non-number char count as the first number ie: 6-df456 is 6
      /** @var Merit $entityMerit */
      $entityMerit = $this->doctrine->getRepository(Merit::class)->find($id);
      if (!is_null($entityMerit)) {
        $characterMerit = new CharacterMerit;
        $characterMerit->setMerit($entityMerit);
        $characterMerit->setLevel(intval($merit['level']));
        if (isset($merit['details'])) {
          $characterMerit->setChoice($merit['details']);
        }
      }
      $character->addMerit($characterMerit);
      if ($entityMerit->getId() === MeritReferences::GIANT) {
        $character->setSize(6);
      }
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
          $specialty = new CharacterSpecialty($character, $skill, $name);
          
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
      /** @var CharacterSpecialty $specialty */
      $specialty->setCharacter($character);
      $character->addSpecialty($specialty);
    }
  }
}