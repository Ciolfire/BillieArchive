<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Clan;
use App\Entity\Covenant;
use App\Entity\Description;
use App\Entity\Devotion;
use App\Entity\Vampire;
use App\Entity\VampireDiscipline;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Entity\Ghoul;
use App\Entity\GhoulDiscipline;
use App\Entity\GhoulFamily;
use App\Repository\ClanRepository;
use Symfony\Component\Form\FormInterface;

class VampireService
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  public function embrace(Character $character, FormInterface $form): bool
  {
    $connection = $this->dataService->getConnection();
    $data = $form->getData();
    
    if (!$data['clan'] instanceof Clan) {

      return false;
    }

    $ghoul = $character->findLesserTemplate("ghoul");
    $disciplines = $form->getExtraData()['disciplines'];
    // IF IT'S A GHOUL, NEED TO KEEP THE DISCIPLINES/DEVOTIONS/... Didn't work with entity, had to convert to array, but well, it works.
    if ($ghoul instanceof Ghoul) {
      $result = $this->embraceFromGhoul($ghoul, $disciplines);
      $disciplines = $result['disciplines'];
    }
    // The human is gone forever...
    $nativeQuery = $connection->prepare("UPDATE `characters` SET type='vampire' WHERE id = :id");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->executeStatement();
    // ...But the Vampire rise for eternity
    $nativeQuery = $connection->prepare("INSERT IGNORE INTO `vampire`(`id`, `clan_id`, `covenant_id`, `sire`, `death_age`, `potency`, `vitae`) VALUES (:id, :clan, :covenant, :sire, :age, 1, 1)");
    $nativeQuery->bindValue('id', $character->getId());
    $nativeQuery->bindValue('clan', $data['clan']->getId());
    if ($data['covenant'] instanceof Covenant) {
      $nativeQuery->bindValue('covenant', $data['covenant']->getId());
    } else {
      $nativeQuery->bindValue('covenant', null);
    }
    $nativeQuery->bindValue('sire', $data['sire']);
    $nativeQuery->bindValue('age', $data['age']);
    $nativeQuery->executeStatement();
    // We force the change to the manager, to avoid conflict from memory (?)
    $this->dataService->reset();
    $vampire = $this->dataService->find(Vampire::class, $character->getId());
    $vampire->addAttribute($data['attribute']->getIdentifier(), 1);
    // Should reflect the current advancement of the ghoul, ie if the ghoul already has 2 discipline, she only get 1 new dot
    $this->addDisciplines($vampire, $disciplines);
    if (isset($result['rituals'])) {
      $this->addRituals($vampire, $result['rituals']);
    }
    if (isset($result['devotions'])) {
      $this->addDevotions($vampire, $result['devotions']);
    }
    $vampire->cleanLesserTemplates();
    $this->dataService->save($vampire);

    return true;
  }

  public function embraceFromGhoul(Ghoul $ghoul, array $disciplines) : array
  {
    $rituals = [];
    $devotions = [];
    foreach ($ghoul->getDisciplines() as $ghoulDiscipline) {
      if ($ghoulDiscipline instanceof GhoulDiscipline) {
        $discipline = $ghoulDiscipline->getDiscipline();
        $level = $ghoulDiscipline->getLevel();
        $disciplines[$discipline->getId()] = $level;
      }
    }
    foreach ($ghoul->getRituals() as $ritual) {
      if ($ritual instanceof DisciplinePower) {
        $rituals[$ritual->getId()] = 1;
      }
    }
    foreach ($ghoul->getDevotions() as $devotion) {
      if ($devotion instanceof Devotion) {
        $devotions[$devotion->getId()] = 1;
      }
    }

    return [
      'disciplines' => $disciplines,
      'rituals' => $rituals,
      'devotions' => $devotions,
    ];
  }

  public function ghoulify(Ghoul $template, array $data): Ghoul
  {
    // The human turn partly vampire...
    $template->setClan($this->dataService->find(Clan::class, $data['clan']));
    $template->setRegent($data['regent']);
    $template->setCovenant($this->dataService->find(Covenant::class, $data['covenant']));
    $template->setFamily($this->dataService->find(GhoulFamily::class, $data['family']));

    return $template;
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
  public function getDisciplines(string $type = "discipline", ?string $filter = null, ?int $id = null): array
  {
    $template = 'vampire/discipline/list.html.twig';
    $criteria = [];
    if (!is_null($filter)) {
      switch ($filter) {
        case 'chronicle':
          /** @var Chronicle */
          $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
          $criteria['homebrewFor'] = $item;
          $back = ['path' => 'homebrew_index', 'params' => [
            'id' => $id,
          ]];

          break;
        case 'book':
        default:
          /** @var Book */
          $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
          $criteria['book'] = $item;
          $back = ['path' => 'book_index', 'params' => [
            'setting' => 'vampire',
            '_fragment' => $id
          ]];
      }
    } else {
      $back = null;
      $criteria['homebrewFor'] = null;
    }

    $icon = null;
    $label = null;
    switch ($type) {
      case 'all':
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_discipline']);
        $type = 'discipline';
        break;
      case 'sorcery':
        $criteria['isSorcery'] = true;
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_sorcery']);
        $icon = "pentacle";
        $label = "sorcery.label.multi";
        break;
      case 'thaumaturgy':
        $criteria['isThaumaturgy'] = true;
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_thaumaturgy']);
        $label = "thaumaturgy.label";
        break;
      case 'coils':
        $criteria['isCoil'] = true;
        $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_coils']);
        $label = "coil.label.multi";
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
      'label' => $label,
      'icon' => $icon,
    ];
  }

  /**
   * @return array<string, mixed>
   */
  public function getRituals(?string $filter = null, ?int $id = null): array
  {
    $criteria = [];

    if (!is_null($filter)) {
      switch ($filter) {
        case 'chronicle':
          /** @var Chronicle */
          $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);

          $criteria['chronicle'] = $item;
          break;

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

  public function getRemovableAttributes()
  {
    $removables = [
      'potency' => ['label' => 'potency.label', 'domain' => 'vampire'],
      'discipline' => ['label' => 'label.single', 'domain' => 'discipline'],
      'devotion' => ['label' => 'devotion.label.single', 'domain' => 'discipline'],
    ];

    return $removables;
  }

  public function getGhoulRemovableAttributes()
  {
    $removables = [
      'discipline' => ['label' => 'label.single', 'domain' => 'discipline'],
      'devotion' => ['label' => 'devotion.label.single', 'domain' => 'discipline'],
    ];

    return $removables;
  }
}
