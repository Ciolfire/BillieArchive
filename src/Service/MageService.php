<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Description;

use App\Entity\Mage;
use App\Entity\Arcana;

use Symfony\Component\Form\FormInterface;

class MageService
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }


  /**
   * @return array<string, array<int, object>>
   */
  public function getSpecial(Mage $mage): array
  {
    // /** @var array<int, Discipline> */
    // $disciplines = $this->dataService->findBy(Discipline::class, ['isCoil' => false, 'isThaumaturgy' => false, 'isSorcery' => false]);
    // $disciplines = $this->filterDisciplines($disciplines, $vampire);
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
      // 'disciplines' => $disciplines,
      // 'sorcery' => $sorcery,
      // 'coils' => $coils,
      // 'thaumaturgy' => $thaumaturgy,
      // 'devotions' => $devotions,
    ];
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
