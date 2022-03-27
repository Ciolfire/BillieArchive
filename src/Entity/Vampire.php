<?php

namespace App\Entity;

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
  private $apparantAge;

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
   * @ORM\OneToMany(targetEntity=VampireDiscipline::class, mappedBy="character", orphanRemoval=true)
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
    $limit = max($this->limit, $this->potency);
    $this->skills = new CharacterSkills();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(int $id)
  {
    $this->id = $id;

    return $this;
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

  public function getApparantAge(): ?int
  {
    return $this->apparantAge;
  }

  public function setApparantAge(int $apparantAge): self
  {
    $this->apparantAge = $apparantAge;

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
}
