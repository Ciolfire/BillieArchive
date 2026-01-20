<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\References\DisciplineReferences;
use App\Form\Vampire\GhoulForm;
use App\Repository\GhoulRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: GhoulRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Ghoul extends CharacterLesserTemplate
{
  protected $limit = 5;

  #[ORM\Column(length: 60)]
  protected string $regent = "";

  #[ORM\ManyToOne(targetEntity: Clan::class)]
  #[ORM\JoinColumn(nullable: false)]
  private Clan $clan;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $vitae = 1;

  #[ORM\OneToMany(targetEntity: GhoulDiscipline::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist"])]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $disciplines;

  #[ORM\ManyToMany(targetEntity: Devotion::class, cascade: ["persist"])]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $devotions;

  #[ORM\ManyToMany(targetEntity: DisciplinePower::class, cascade: ["persist"])]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $rituals;

  #[ORM\ManyToOne]
  private ?GhoulFamily $family = null;

  #[ORM\ManyToOne]
  private ?Covenant $covenant = null;

  public function __construct(?Clan $clan = null, ?Ghoul $ghoul = null)
  {
    if (isset($clan)) {
      $this->clan = $clan;
    }
    $this->disciplines = new ArrayCollection();
    $this->devotions = new ArrayCollection();
    $this->rituals = new ArrayCollection();
  }

  public function __clone()
  {
    parent::__clone();
    $this->disciplines = $this->cloneCollection($this->disciplines);
  }

  public function getType(): string
  {
    return "ghoul";
  }

  public function getSetting(): string
  {
    return "vampire";
  }

  public static function getForm(): string
  {
    return GhoulForm::class;
  }

  public function getRegent(): ?string
  {
    return $this->regent;
  }

  public function setRegent(string $regent): self
  {
    $this->regent = $regent;

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

  public function getFilteredDisciplines(?string $filter = null): mixed
  {
    switch ($filter) {
      case 'discipline':
        $disciplines = [];
        foreach ($this->disciplines as $discipline) {
          /** @var GhoulDiscipline $discipline */
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

  public function addDiscipline(GhoulDiscipline $discipline): self
  {
    if (!$this->disciplines->contains($discipline)) {
      $this->disciplines[] = $discipline;
      $discipline->setCharacter($this);
    }

    return $this;
  }

  public function removeDiscipline(GhoulDiscipline $discipline): self
  {
    if ($this->disciplines->removeElement($discipline)) {
      // set the owning side to null (unless already changed)
      if ($discipline->getCharacter() === $this) {
        $discipline->setCharacter(null);
      }
    }

    return $this;
  }

  public function getDiscipline(int $id): ?GhoulDiscipline
  {
    foreach ($this->disciplines as $discipline) {
      /** @var GhoulDiscipline $discipline */
      if ($discipline->getDiscipline()->getId() === $id) {

        return $discipline;
      }
    }

    return null;
  }

  public function hasDiscipline(int $id): bool
  {
    foreach ($this->disciplines as $discipline) {
      /** @var GhoulDiscipline $discipline */
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

  public function getMaxHealth(): ?int
  {
    $base = $this->getSourceCharacter()->getSize() + $this->limit;

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

  public function getFamily(): ?GhoulFamily
  {
    return $this->family;
  }

  public function setFamily(?GhoulFamily $family): static
  {
    $this->family = $family;

    return $this;
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
}
