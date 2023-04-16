<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\CharacterSpecialty;
use App\Entity\Skill;
use App\Entity\Merit;
use App\Entity\References\MeritReferences;
use Doctrine\Persistence\ManagerRegistry;
use \Symfony\Component\Form\FormInterface;

class CreationService
{
  private ManagerRegistry $doctrine;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  /**
   * @param array<string, mixed> $formMerits
   */
  public function addMerits(Character $character, $formMerits) : void
  {
    $merits = [];
    
    foreach ($formMerits as $key => $merit) {
      if (!empty($merit['level'])) {
        $merits[$key] = $merit;
      }
    }
    
    foreach ($merits as $id => $merit) {
      // We take advantage of the fact that a string with non-number char count as the number before the letters ie: 6-df456 is 6
      $entityMerit = $this->doctrine->getRepository(Merit::class)->find($id);
      if (!is_null($entityMerit)) {
        $characterMerit = new CharacterMerit();
        $characterMerit->setMerit($entityMerit);
        $characterMerit->setLevel(intval($merit['level']));
        if (isset($merit['details'])) {
          $characterMerit->setChoice($merit['details']);
        }
        $character->addMerit($characterMerit);
        if ($entityMerit->getId() === MeritReferences::GIANT) {
          $character->setSize(6);
        }
        $this->doctrine->getManager()->persist($characterMerit);
      }
    }
  }

  /**
   * @param array<string, mixed> $formMerits
   */
  public function updateMerits(array $formMerits) : void
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

  /**
   * @param array<string, mixed> $formSpecialties
   */
  public function addSpecialties(Character $character, array $formSpecialties) : void
  {
    foreach ($formSpecialties as $skill => $skillSpec) {
      if (count($skillSpec) > 0) {
        $skill = $this->doctrine->getRepository(Skill::class)->findOneBy(['identifier' => $skill]);
        foreach ($skillSpec as $id => $name) {
          $specialty = new CharacterSpecialty($character, $skill, $name);
          
          $specialty->setCharacter($character);
          $character->addSpecialty($specialty);
          $this->doctrine->getManager()->persist($specialty);
        }
      }
    }
  }

  public function getSpecialties(Character $character, FormInterface $form): void
  {
    $specialties = [$form['specialty1'], $form['specialty2'], $form['specialty3']];

    /** @var FormInterface $specialty */
    foreach ($specialties as $specialty) {
      $specialty = $specialty->getData();
      /** @var CharacterSpecialty $specialty */
      if (!is_null($specialty->getName())) {
        $specialty->setCharacter($character);
        $character->addSpecialty($specialty);
      }
    }
  }
}