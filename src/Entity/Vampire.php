<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\VampireRepository;
use App\Entity\References\DisciplineReferences;
use App\Form\Vampire\VampireForm;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: VampireRepository::class)]
class Vampire extends Character
{
  #[ORM\Column(type: Types::SMALLINT)]
  private int $potency = 1;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $vitae = 1;

  #[ORM\ManyToOne(targetEntity: Clan::class)]
  #[ORM\JoinColumn(nullable: false)]
  private Clan $clan;

  #[ORM\ManyToOne]
  private ?Covenant $covenant = null;

  #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
  private ?string $sire = null;

  #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ["unsigned" => true])]
  private ?int $deathAge = null;

  #[ORM\OneToMany(targetEntity: VampireDiscipline::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist", "remove"])]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["level" => "DESC", "discipline" => "ASC"])]
  private Collection $disciplines;


  #[ORM\ManyToMany(targetEntity: Devotion::class, orphanRemoval: false, cascade: ["persist"])]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $devotions;

  #[ORM\ManyToMany(targetEntity: DisciplinePower::class, orphanRemoval: false, cascade: ["persist"])]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $rituals;


  public function __construct(?Character $character = null)
  {
    $this->disciplines = new ArrayCollection();
    if (is_object($character)) {
      // Initializing class properties
      foreach ($character->getProperties() as $property => $value) {
        $this->$property = $value;
      }
    }
    $this->devotions = new ArrayCollection();
    $this->rituals = new ArrayCollection();
  }

  public function __clone()
  {
    if ($this->id) {
      parent::__clone();
      // Collections/Arrays, OneToMany
      $this->disciplines = $this->cloneCollection($this->disciplines);
    }
  }

  public function getType(): string
  {
    return "vampire";
  }

  public function getForm(): string
  {
    return VampireForm::class;
  }

  public function setPowerRating(): self
  {
    $sum = parent::setPowerRating()->getPowerRating();

    // Disciplines
    $sum += 50 + $this->potency ** 2;
    foreach ($this->disciplines as $discipline) {
      /** @var VampireDiscipline $discipline */
      $sum += $this->weightPower[$discipline->getLevel()] * 7;
    }
    // Devotions
    foreach ($this->devotions as $devotion) {
      /** @var Devotion $devotion */
      // Why 3: Stronger than a merit
      $sum += $devotion->getCost();
    }
    // Rituals
    foreach ($this->rituals as $ritual) {
      /** @var DisciplinePower $ritual */
      // Why 1: The level of the discipline is already included
      $sum += $this->weightPower[$ritual->getLevel()] * 1;
    }

    $this->powerRating = $sum;
    return $this;
  }

  public function getLimit(): int
  {
    return max($this->limit, $this->potency);
  }

  public function getSire(): ?string
  {
    return $this->sire;
  }

  public function setSire(?string $sire): self
  {
    $this->sire = $sire;

    return $this;
  }

  public function getDeathAge(): ?int
  {
    return $this->deathAge;
  }

  public function setDeathAge(?int $deathAge): self
  {
    $this->deathAge = $deathAge;

    return $this;
  }

  public function getClan(): ?Clan
  {
    return $this->clan;
  }

  public function setClan(Clan $clan): self
  {
    $this->clan = $clan;

    return $this;
  }

  public function getPotency($withBonus = false): ?int
  {
    $bonus = 0;
    if ($withBonus) {
      foreach ($this->getStatusEffects() as $effect) {
        if ($effect->getType() == 'potency') {
          $bonus += $effect->getRealValue();
        }
      }
    }

    return $this->potency + $bonus;
  }

  public function setPotency(int $potency): self
  {
    $this->potency = $potency;

    return $this;
  }

  public function getMaxVitae(): int
  {
    if (!is_null($this->getChronicle()) && !is_null($this->getChronicle()->getRules('vampire'))) {

      return $this->getChronicle()->getRules('vampire')['maxVitae'][$this->potency];
    }
    switch ($this->potency) {
      case 10:
        return 100;
      case 9:
        return 50;
      case 8:
        return 30;
      case 7:
        return 20;
      default:
        return $this->potency + 9;
    }
  }

  public function getMaxVitaePerTurn(): int
  {
    if (!is_null($this->getChronicle()) && !is_null($this->getChronicle()->getRules('vampire'))) {

      return $this->getChronicle()->getRules('vampire')['maxVitaePerTurn'][$this->potency];
    }
    switch ($this->potency) {
      case 10:
        return 15;
      case 9:
        return 10;
      case 8:
        return 7;
      case 7:
        return 5;
      case 5:
        return 3;
      default:
        return intval(max(1, round($this->potency / 2, 0, PHP_ROUND_HALF_DOWN)));
    }
  }

  public function getVitae(): ?int
  {
    return $this->vitae;
  }

  public function setVitae(int $vitae): self
  {
    $this->vitae = $vitae;

    return $this;
  }

  public function getDisciplines(): Collection
  {
    return $this->disciplines;
  }

  public function getFilteredDisciplines(string $filter = null): mixed
  {
    switch ($filter) {
      case 'discipline':
        $disciplines = [];
        foreach ($this->disciplines as $discipline) {
          /** @var VampireDiscipline $discipline */
          if ($discipline->getDiscipline()->isSimple()) {
            $disciplines[] = $discipline;
          }
        }
        break;

      case 'sorcery':
        $disciplines = [];
        foreach ($this->disciplines as $discipline) {
          /** @var VampireDiscipline $discipline */
          if ($discipline->getDiscipline()->isSorcery()) {
            $disciplines[] = $discipline;
          }
        }
        break;
      case 'coils':
        $disciplines = [];
        foreach ($this->disciplines as $discipline) {
          /** @var VampireDiscipline $discipline */
          if ($discipline->getDiscipline()->isCoil()) {
            $disciplines[] = $discipline;
          }
        }
        break;
      case 'thaumaturgy':
        $disciplines = [];
        foreach ($this->disciplines as $discipline) {
          /** @var VampireDiscipline $discipline */
          if ($discipline->getDiscipline()->isThaumaturgy()) {
            $disciplines[] = $discipline;
          }
        }
        break;
      default:
        $disciplines = $this->disciplines->toArray();
        break;
    }

    return $disciplines;
  }

  public function addDiscipline(VampireDiscipline $discipline): self
  {
    if (!$this->disciplines->contains($discipline)) {
      $this->disciplines[] = $discipline;
      $discipline->setCharacter($this);
    }

    return $this;
  }

  public function removeDiscipline(VampireDiscipline $discipline): self
  {
    if ($this->disciplines->removeElement($discipline)) {
      // set the owning side to null (unless already changed)
      if ($discipline->getCharacter() === $this) {
        $discipline->setCharacter(null);
      }
    }

    return $this;
  }

  public function getDiscipline(int $id): ?VampireDiscipline
  {
    foreach ($this->disciplines as $discipline) {
      /** @var VampireDiscipline $discipline */
      if ($discipline->getDiscipline()->getId() === $id) {

        return $discipline;
      }
    }

    return null;
  }

  public function hasDiscipline(int $id): bool
  {
    foreach ($this->disciplines as $discipline) {
      /** @var VampireDiscipline $discipline */
      if ($discipline->getDiscipline()->getId() == $id) {

        return true;
      }
    }

    return false;
  }

  /**
   * @return Collection<int, Devotion>
   */
  public function getDevotions(): Collection
  {
    return $this->devotions;
  }

  public function addDevotion(Devotion $devotion): self
  {
    if (!$this->devotions->contains($devotion)) {
      $this->devotions->add($devotion);
    }

    return $this;
  }

  public function removeDevotion(Devotion $devotion): self
  {
    $this->devotions->removeElement($devotion);

    return $this;
  }

  public function hasDevotion(?int $id): bool
  {
    foreach ($this->devotions as $devotion) {
      /** @var Devotion $devotion */
      if ($devotion->getId() == $id) {

        return true;
      }
    }

    return false;
  }

  /**
   * @return Collection<int, DisciplinePower>
   */
  public function getRituals(): Collection
  {
    return $this->rituals;
  }

  public function addRitual(DisciplinePower $ritual): self
  {
    if (!$this->rituals->contains($ritual)) {
      $this->rituals->add($ritual);
    }

    return $this;
  }

  public function removeRitual(DisciplinePower $ritual): self
  {
    $this->rituals->removeElement($ritual);

    return $this;
  }

  public function hasRitual(DisciplinePower $ritual): bool
  {
    if (in_array($ritual, $this->rituals->toArray())) {
      return true;
    }

    return false;
  }

  public function hasRitualAtLevel(Discipline $discipline, int $level = 1): bool
  {
    foreach ($this->rituals as $ritual) {
      /** @var DisciplinePower $ritual */
      if ($ritual->getDiscipline() === $discipline && $ritual->getLevel() == $level) {

        return true;
      }
    }

    return false;
  }

  /** Get the total level of the character in the coils of the Dragons, mostly used to calculate costs */
  public function coilsLevel(): int
  {
    $level = 0;
    foreach ($this->disciplines as $discipline) {
      /** @var VampireDiscipline $discipline */
      if ('coil' == $discipline->getType()) {
        $level += $discipline->getLevel();
      }
    }

    return $level;
  }

  public function getMaxHealth(): ?int
  {
    $base = $this->size + max($this->getLimit(), $this->getAttributes()->get('stamina'));

    $resilience = $this->getDiscipline(DisciplineReferences::RESILIENCE);
    if (!is_null($resilience)) {
      $base = $base + $resilience->getLevel();
    }

    return $base;
  }

  public function getMaxMorality(): int
  {
    $restriction = 0;
    $restrictingDisicplines = [];
    $restrictingDisicplines[] = $this->getDiscipline(DisciplineReferences::CRUAC);
    $restrictingDisicplines[] = $this->getDiscipline(DisciplineReferences::MERGES);

    if (!empty($restrictingDisicplines)) {
      foreach ($restrictingDisicplines as $discipline) {
        if ($discipline instanceof VampireDiscipline && $discipline->getLevel() > $restriction) {
          $restriction = $discipline->getLevel();
        }
      }
    }
    
    return 10 - $restriction;
  }

  public function getCovenant(): ?Covenant
  {
      return $this->covenant;
  }

  public function setCovenant(?Covenant $covenant): static
  {
      $this->covenant = $covenant;

      return $this;
  }

  public function getMainOrganization(): ?Organization
  {
    return $this->covenant;
  }
}
