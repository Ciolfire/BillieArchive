<?php

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
  private $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  public function getSpecial(Vampire $vampire)
  {
    $disciplines = $this->filterDisciplines($this->dataService->findBy(Discipline::class, ['isCoil' => false, 'isThaumaturgy' => false, 'isSorcery' => false]), $vampire);
    $sorcery = $this->filterDisciplines($this->dataService->findBy(Discipline::class, ['isSorcery' => true]), $vampire);
    $coils = $this->filterDisciplines($this->dataService->findBy(Discipline::class, ['isCoil' => true]), $vampire);
    $thaumaturgy = $this->filterDisciplines($this->dataService->findBy(Discipline::class, ['isThaumaturgy' => true]), $vampire);
    // dd($disciplines, $vampire);
    $devotions = $this->dataService->findBy(Devotion::class, [], ['name' => 'ASC']);
    foreach ($devotions as $key => $devotion) {
      /** @var Devotion $devotion */
      if ($vampire->hasDevotion($devotion->getId()) || !$devotion->isAvailable($vampire->getChronicle())) {
        unset($devotions[$key]);
      }
      foreach ($devotion->getprerequisites() as $prerequisite) {
        $prerequisite->setEntity($this->dataService->findOneBy($prerequisite->getType(), ['id' => $prerequisite->getEntityId()]));
      }
    }
    return [
      'disciplines' => $disciplines,
      'sorcery' => $sorcery,
      'coils' => $coils,
      'thaumaturgy' => $thaumaturgy,
      'devotions' => $devotions,
    ];
  }

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

  private function isDisciplineAllowed(Discipline $discipline, Vampire $vampire)
  {
    if ($vampire->hasDiscipline($discipline->getId())) {

      return false;
    }
    if (!$discipline->isAvailable($vampire->getChronicle())) {

      return false;
    }
    if ($discipline->isRestricted() && !$vampire->getClan()->hasDiscipline($discipline)) {

      return false;
    }

    return true;
  }

  public function embrace(Character $character, FormInterface $form)
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

  public function handleEdit(Vampire $vampire, array $data)
  {
    foreach ($data['disciplinesUp'] as $id => $level) {
      $discipline = $vampire->getDiscipline($id);
      $discipline->setLevel($level);
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

  public function addDisciplines(Vampire $vampire, array $disciplines)
  {
    foreach ($disciplines as $id => $level) {
      $discipline = $this->dataService->find(Discipline::class, $id);
      $newDiscipline = new VampireDiscipline($vampire, $discipline, $level);
      $this->dataService->add($newDiscipline);
      $vampire->addDiscipline($newDiscipline);
    }
  }

  public function addDevotions(Vampire $vampire, array $devotions)
  {
    foreach ($devotions as $id => $value) {
      if ($value == 1) {
        $devotion = $this->dataService->find(Devotion::class, $id);
        $vampire->addDevotion($devotion);
      }
    }
  }

  public function addRituals(Vampire $vampire, array $rituals)
  {
    foreach ($rituals as $id => $value) {
      if ($value == 1) {
        $ritual = $this->dataService->find(DisciplinePower::class, $id);
        $vampire->addRitual($ritual);
      }
    }
  }

  public function getBloodlines($item = null)
  {
    /** @var ClanRepository $repo */
    $repo = $this->dataService->getRepository(Clan::class);
    if ($item instanceof Book) {

      return $repo->findByBook($item);
    } else {

      return $repo->findAllBloodlines();
    }
  }

  public function getDisciplines($type = "discipline", $filter = null, $id = null)
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

  public function getRituals($filter = null, $id = null)
  {
    $criteria = [];

    if (!is_null($filter)) {
      switch ($filter) {
        case 'book':
        default:
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);

        $criteria['book'] = $item;
        break;
      }
    }

    $rituals = $item->getRituals();
    $description = $this->dataService->findOneBy(Description::class, ['name' => 'vampire_ritual']);

    return [
      'rituals' => $rituals,
      'description' => $description,
      'entity' => 'discipline',
      'type' => 'ritual',
    ];
  }
}