<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Specialty;

class CreationService
{
  public function getMerits($formMerits): array
  {
    $merits = [];

    foreach ($formMerits as $key => $merit) {
      if (!empty($merit['level'])) {
        $merits[$key] = $merit;
      }
    }

    return $merits;
  }

  public function getSkills($formMerits): array
  {
    $skills = [];

    foreach ($formMerits as $key => $merit) {
      if (!empty($merit['level'])) {
        $merits[$key] = $merit;
      }
    }

    return $merits;
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