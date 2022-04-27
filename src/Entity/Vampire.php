<?php

namespace App\Entity;

use App\Entity\VampireDiscipline;
use App\Repository\VampireRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=VampireRepository::class)
 */
class Vampire extends Character
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=50)
   */
  private $sire;

  /**
   * @ORM\Column(type="smallint")
   */
  private $apparentAge;

  /**
   * @ORM\ManyToOne(targetEntity=Clan::class)
   * @ORM\JoinColumn(nullable=false)
   */
  private $clan;

  /**
   * @ORM\Column(type="smallint")
   */
  private $potency = 1;

  /**
   * @ORM\Column(type="smallint")
   */
  private $vitae = 1;

  /**
   * @ORM\OneToMany(targetEntity=VampireDiscipline::class, mappedBy="character", orphanRemoval=true, fetch="EAGER"))
   */
  private $disciplines;

  protected $limit = 5;

  public function __construct(Character $character = null)
  {
    $this->disciplines = new ArrayCollection();
    if ($character) {
      // Initializing class properties
      foreach ($character as $property => $value) {
        $this->$property = $value;
      }
    }
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getLimit(): int
  {
    return max($this->limit, $this->potency);
  }

  public function getSire(): ?string
  {
    return $this->sire;
  }

  public function setSire(string $sire): self
  {
    $this->sire = $sire;

    return $this;
  }

  public function getApparentAge(): ?int
  {
    return $this->apparentAge;
  }

  public function setApparentAge(int $apparentAge): self
  {
    $this->apparentAge = $apparentAge;

    return $this;
  }

  public function getClan(): ?Clan
  {
    return $this->clan;
  }

  public function setClan(?Clan $clan): self
  {
    $this->clan = $clan;

    return $this;
  }

  public function getPotency(): ?int
  {
    return $this->potency;
  }

  public function setPotency(int $potency): self
  {
    $this->potency = $potency;

    return $this;
  }

  public function getMaxVitae(): int
  {
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

  public function getVitae(): ?int
  {
    return $this->vitae;
  }

  public function setVitae(int $vitae): self
  {
    $this->vitae = $vitae;

    return $this;
  }

  /**
   * @return Collection|VampireDiscipline[]
   */
  public function getDisciplines(): Collection
  {
    return $this->disciplines;
  }

  public function addDiscipline(VampireDiscipline $discipline): self
  {
    if (!$this->disciplines->contains($discipline)) {
      $this->disciplines[] = $discipline;
      $discipline->setVampire($this);
    }

    return $this;
  }

  public function removeDiscipline(VampireDiscipline $discipline): self
  {
    if ($this->disciplines->removeElement($discipline)) {
      // set the owning side to null (unless already changed)
      if ($discipline->getVampire() === $this) {
        $discipline->setVampire(null);
      }
    }

    return $this;
  }

  public function getDiscipline(int $id): ?VampireDiscipline
  {
    foreach ($this->disciplines as $discipline) {
      /** @var VampireDiscipline $discipline */
      if ($discipline->getDiscipline()->getId() == $id) {

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
}
