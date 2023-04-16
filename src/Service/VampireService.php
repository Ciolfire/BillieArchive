<?php declare(strict_types=1);

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
  public function getSpecial(Vampire $vampire) : array
  {
    /** @var array<int, Discipline> */
    $disciplines = $this->dataService->findBy(Discipline::class, ['isCoil' => false, 'isThaumaturgy' => false, 'isSorcery' => false]);
    /** @var array<int, Discipline> */
    $sorcery = $this->dataService->findBy(Discipline::class, ['isSorcery' => true]);
    /** @var array<int, Discipline> */
    $coils = $this->dataService->findBy(Discipline::class, ['isCoil' => true]);
    /** @var array<int, Discipline> */
    $thaumaturgy = $this->dataService->findBy(Discipline::class, ['isThaumaturgy' => true]);

    $disciplines = $this->filterDisciplines($disciplines, $vampire);
    $sorcery = $this->filterDisciplines($sorcery, $vampire);
    $coils = $this->filterDisciplines($coils, $vampire);
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
 * @param array<int, Discipline> $disciplines
 * @return array<int, Discipline>
 */
  private function filterDisciplines(array $disciplines, Vampire $vampire) : array
  {
    foreach ($disciplines as $key => $discipline) {
      /** @var Discipline $discipline */
      if (!$this->isDisciplineAllowed($discipline, $vampire)) {
        unset($disciplines[$key]);
      }
    }

    return $disciplines;
  }

  private function isDisciplineAllowed(Discipline $discipline, Vampire $vampire) : bool
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

  public function embrace(Character $character, FormInterface $form) : void
  {
    $connection = $this->dataService->getConnection();
    $data = $form->getData();

    // The human is gone forever...
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

  /** @param array<string, mixed> $data */
  public function handleEdit(Vampire $vampire, array $data) : void
  {
    foreach ($data['disciplinesUp'] as $id => $level) {
      $discipline = $vampire->getDiscipline($id);
      if ($discipline) {
        $discipline->setLevel($level);
      }
    }
    if (isset($data['disciplines'])) {
      $this->addDisciplines($vampire, $data['disciplines']);
    }
    if (isset($data['potency']) && $data['potency'] > $vampire->getPotency()) {
      $vampire->setPotency($data['potency']);
    }

    if (isset($data['devotions'])) {
      $this->addDevotions($vampire, $data['devotions']);
    }

    if (isset($data['rituals'])) {
      $this->addRituals($vampire, $data['rituals']);
    }
  }

  /** @param array<int, int> $disciplines */
  public function addDisciplines(Vampire $vampire, array $disciplines) : void
  {
    foreach ($disciplines as $id => $level) {
      $discipline = $this->dataService->find(Discipline::class, $id);
      if ($discipline instanceof Discipline) {
        $newDiscipline = new VampireDiscipline($vampire, $discipline, $level);
      } else {
        throw new \Exception("\$discipline not a Discipline");
      }
      $this->dataService->add($newDiscipline);
      $vampire->addDiscipline($newDiscipline);
    }
  }

  /** @param array<int, int> $devotions */
  public function addDevotions(Vampire $vampire, array $devotions) : void
  {
    foreach ($devotions as $id => $value) {
      if ($value == 1) {
        $devotion = $this->dataService->find(Devotion::class, $id);
        if ($devotion instanceof Devotion) {
          $vampire->addDevotion($devotion);
        }
      }
    }
  }

  /** @param array<int, int> $rituals */
  public function addRituals(Vampire $vampire, array $rituals) : void
  {
    foreach ($rituals as $id => $value) {
      if ($value == 1) {
        $ritual = $this->dataService->find(DisciplinePower::class, $id);
        if ($ritual instanceof DisciplinePower) {
          $vampire->addRitual($ritual);
        }
      }
    }
  }

  /** @return array<int, Clan> */
  public function getBloodlines(mixed $item = null) : array
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
  public function getDisciplines(string $type = "discipline", string $filter = null, int $id = null) : array
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
  public function getRituals(string $filter = null, int $id = null) : array
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
}