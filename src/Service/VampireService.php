<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Clan;
use App\Entity\Description;
use App\Entity\Devotion;
use App\Entity\Vampire;
use App\Entity\VampireDiscipline;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Entity\Ghoul;
use App\Entity\GhoulDiscipline;
use App\Repository\ClanRepository;
use Symfony\Component\Form\FormInterface;

class VampireService
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }


  /**
   * @return array<string, array<int, object>>
   */
  public function getSpecial(Vampire $vampire): array
  {
    /** @var array<int, Discipline> */
    $disciplines = $this->dataService->findBy(Discipline::class, ['isCoil' => false, 'isThaumaturgy' => false, 'isSorcery' => false]);
    $disciplines = $this->filterDisciplines($disciplines, $vampire);
    /** @var array<int, Discipline> */
    $sorcery = $this->dataService->findBy(Discipline::class, ['isSorcery' => true]);
    $sorcery = $this->filterDisciplines($sorcery, $vampire);
    /** @var array<int, Discipline> */
    $coils = $this->dataService->findBy(Discipline::class, ['isCoil' => true]);
    $coils = $this->filterDisciplines($coils, $vampire);
    /** @var array<int, Discipline> */
    $thaumaturgy = $this->dataService->findBy(Discipline::class, ['isThaumaturgy' => true]);
    $thaumaturgy = $this->filterDisciplines($thaumaturgy, $vampire);

    $devotions = $this->dataService->findBy(Devotion::class, [], ['name' => 'ASC']);
    foreach ($devotions as $key => $devotion) {
      /** @var Devotion $devotion */
      if ($vampire->hasDevotion($devotion->getId()) || !$devotion->isAvailable($vampire->getChronicle())) {
        unset($devotions[$key]);
      }
      $this->dataService->loadPrerequisites($devotion);
    }
    return [
      'disciplines' => $disciplines,
      'sorcery' => $sorcery,
      'coils' => $coils,
      'thaumaturgy' => $thaumaturgy,
      'devotions' => $devotions,
    ];
  }

  /**
   * @return array<string, array<int, object>>
   */
  public function getGhoulSpecial(Ghoul $ghoul): array
  {
    /** @var array<int, Discipline> */
    $disciplines = $this->dataService->findBy(Discipline::class, ['isCoil' => false, 'isThaumaturgy' => false, 'isSorcery' => false]);
    $disciplines = $this->filterDisciplines($disciplines, $ghoul);
    /** @var array<int, Discipline> */
    $sorcery = $this->dataService->findBy(Discipline::class, ['isSorcery' => true]);
    $sorcery = $this->filterDisciplines($sorcery, $ghoul);
    /** @var array<int, Discipline> */
    $thaumaturgy = $this->dataService->findBy(Discipline::class, ['isThaumaturgy' => true]);
    $thaumaturgy = $this->filterDisciplines($thaumaturgy, $ghoul);

    $devotions = $this->dataService->findBy(Devotion::class, [], ['name' => 'ASC']);
    foreach ($devotions as $key => $devotion) {
      /** @var Devotion $devotion */
      if ($ghoul->hasDevotion($devotion->getId()) || !$devotion->isAvailable($ghoul->getChronicle())) {
        unset($devotions[$key]);
      }
      $this->dataService->loadPrerequisites($devotion);
    }
    return [
      'disciplines' => $disciplines,
      'sorcery' => $sorcery,
      'thaumaturgy' => $thaumaturgy,
      'devotions' => $devotions,
    ];
  }

  /**
   * @param array<int, Discipline> $disciplines
   * @return array<int, Discipline>
   */
  private function filterDisciplines(array $disciplines, Vampire|Ghoul $vampire): array
  {
    foreach ($disciplines as $key => $discipline) {
      /** @var Discipline $discipline */
      if (!$this->isDisciplineAllowed($discipline, $vampire)) {
        unset($disciplines[$key]);
      }
    }

    return $disciplines;
  }

  private function isDisciplineAllowed(Discipline $discipline, Vampire|Ghoul $vampire): bool
  {
    if (!is_null($discipline->getId()) && $vampire->hasDiscipline($discipline->getId())) {

      return false;
    }
    if (!$discipline->isAvailable($vampire->getChronicle())) {

      return false;
    }
    if ($discipline->isRestricted() && !(!is_null($vampire->getClan()) && $vampire->getClan()->hasDiscipline($discipline))) {

      return false;
    }

    return true;
  }

  public function embrace(Character $character, FormInterface $form): void
  {
    $connection = $this->dataService->getConnection();
    $data = $form->getData();

    // The human is gone forever...
    // IF IT'S A GHOUL, NEED TO KEEP THE DISCIPLINES/DEVOTIONS/...
    // $nativeQuery = $connection->prepare("DELETE FROM `human` WHERE id = :id");// $nativeQuery->bindValue('id', $character->getId());// $nativeQuery->executeStatement();
    $nativeQuery = $connection->prepare("UPDATE `characters` SET type='vampire' WHERE id = :id");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->executeStatement();
    // ...But the Vampire rise for eternity
    $nativeQuery = $connection->prepare("INSERT IGNORE INTO `vampire`(`id`, `clan_id`, `sire`, `death_age`, `potency`, `vitae`) VALUES (:id, :clan, :sire, :age, 1, 1)");
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
    $this->addDisciplines($vampire, $form->getExtraData()['disciplines']);
    $this->dataService->save($vampire);
  }


  public function ghoulify(Character $character): void
  {
    // $data = $form->getData();

    // The human turn partly vampire...
    $clan = $this->dataService->find(Clan::class, 1);
    $ghoul = new Ghoul($clan);
    $character->addLesserTemplate($ghoul);
    $this->dataService->add($ghoul);
    $this->dataService->save($character);
  }

  /** @param array<string, mixed> $data */
  public function handleEdit(Vampire $vampire, array $data): void
  {
    if (isset($data['disciplinesUp'])) {
      foreach ($data['disciplinesUp'] as $id => $level) {
        $discipline = $vampire->getDiscipline($id);
        if ($discipline) {
          $discipline->setLevel((int)$level);
        }
      }
    }
    if (isset($data['disciplines'])) {
      $this->addDisciplines($vampire, $data['disciplines']);
    }
    if (isset($data['potency']) && $data['potency'] > $vampire->getPotency()) {
      $vampire->setPotency((int)$data['potency']);
    }

    if (isset($data['devotions'])) {
      $this->addDevotions($vampire, $data['devotions']);
    }

    if (isset($data['rituals'])) {
      $this->addRituals($vampire, $data['rituals']);
    }
  }

  /** @param array<string, mixed> $data */
  public function handleGhoulEdit(Ghoul $ghoul, array $data): void
  {
    if (isset($data['disciplinesUp'])) {
      foreach ($data['disciplinesUp'] as $id => $level) {
        $discipline = $ghoul->getDiscipline($id);
        if ($discipline) {
          $discipline->setLevel((int)$level);
        }
      }
    }
    if (isset($data['disciplines'])) {
      $this->addDisciplines($ghoul, $data['disciplines']);
    }
    if (isset($data['devotions'])) {
      $this->addDevotions($ghoul, $data['devotions']);
    }

    if (isset($data['rituals'])) {
      $this->addRituals($ghoul, $data['rituals']);
    }
  }

  /** @param array<int, int> $disciplines */
  public function addDisciplines(Vampire|Ghoul $character, array $disciplines): void
  {

    foreach ($disciplines as $id => $level) {
      $discipline = $this->dataService->find(Discipline::class, $id);
      if ($discipline instanceof Discipline) {
        if ($character instanceof Vampire) {
          $newDiscipline = new VampireDiscipline($character, $discipline, (int)$level);
        } else {
          $newDiscipline = new GhoulDiscipline($character, $discipline, (int)$level);
        }
      } else {
        throw new \Exception("\$discipline not a Discipline");
      }
      $this->dataService->add($newDiscipline);
      $character->addDiscipline($newDiscipline);
    }
  }

  /** @param array<int, int> $devotions */
  public function addDevotions(Vampire|Ghoul $character, array $devotions): void
  {
    foreach ($devotions as $id => $value) {
      if ($value == 1) {
        $devotion = $this->dataService->find(Devotion::class, $id);
        if ($devotion instanceof Devotion) {
          $character->addDevotion($devotion);
        }
      }
    }
  }

  /** @param array<int, int> $rituals */
  public function addRituals(Vampire|Ghoul $character, array $rituals): void
  {
    foreach ($rituals as $id => $value) {
      if ($value == 1) {
        $ritual = $this->dataService->find(DisciplinePower::class, $id);
        if ($ritual instanceof DisciplinePower) {
          $character->addRitual($ritual);
        }
      }
    }
  }

  /** @return array<int, Clan> */
  public function getBloodlines(mixed $item = null): array
  {
    /** @var ClanRepository $repo */
    $repo = $this->dataService->getRepository(Clan::class);
    if ($item instanceof Book) {

      return $repo->findByBook($item);
    } else {

      return $repo->findAllBloodlines();
    }
  }

  /**
   * @return array<string, mixed>
   */
  public function getDisciplines(string $type = "discipline", string $filter = null, int $id = null): array
  {
    $template = 'vampire/discipline/index.html.twig';
    $criteria = [];

    if (!is_null($filter)) {
      switch ($filter) {
        case 'chronicle':
          /** @var Chronicle */
          $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
          $criteria['homebrewFor'] = $item;
          $back = ['path' => 'homebrew_index', 'id' => $id];

          break;
        case 'book':
        default:
          /** @var Book */
          $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
          $criteria['book'] = $item;
          $back = ['path' => 'book_index', 'id' => $id];
      }
    } else {
      $back = null;
      $criteria['homebrewFor'] = null;
    }

    switch ($type) {
      case 'all':
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_discipline']);
        $type = 'discipline';
        break;
      case 'sorcery':
        $criteria['isSorcery'] = true;
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_sorcery']);
        break;
      case 'thaumaturgy':
        $criteria['isThaumaturgy'] = true;
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_thaumaturgy']);
        break;
      case 'coils':
        $criteria['isCoil'] = true;
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_coils']);
        break;
      case 'discipline':
      default:
        $criteria['isSorcery'] = false;
        $criteria['isThaumaturgy'] = false;
        $criteria['isCoil'] = false;
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_discipline']);
    }

    $disciplines = $this->dataService->findBy(Discipline::class, $criteria, ['name' => 'ASC']);

    return [
      'template' => $template,
      'disciplines' => $disciplines,
      'description' => $description,
      'entity' => 'discipline',
      'type' => $type,
      'back' => $back,
    ];
  }

  /**
   * @return array<string, mixed>
   */
  public function getRituals(string $filter = null, int $id = null): array
  {
    $criteria = [];

    if (!is_null($filter)) {
      switch ($filter) {
        case 'book':
        default:
          /** @var Book */
          $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);

          $criteria['book'] = $item;
      }
      $rituals = $item->getRituals();
    } else {
      $rituals = [];
    }

    $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_ritual']);

    return [
      'rituals' => $rituals,
      'description' => $description,
      'entity' => 'discipline',
      'type' => 'ritual',
    ];
  }

  public function getRules(Chronicle $chronicle)
  {
    $rules = $chronicle->getRules('vampire');

    // We do not have any custom rules, or no custom rules for vampire
    if (is_null($rules)) {
      $rules = [
        'maxVitae' =>  [
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
        'maxVitaePerTurn' => [
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
}
