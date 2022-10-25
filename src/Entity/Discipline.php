<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\DisciplineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=DisciplineRepository::class)
 * 
 * @ORM\AssociationOverrides({
 *  @ORM\AssociationOverride(name="book", inversedBy="disciplines")
 * })
 */
class Discipline
{
  use Homebrewable;
  use Sourcable;

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @var int|null
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=50)
   * @var string|null
   */
  private $name;

  /**
   * @ORM\Column(type="text")
   * @var string|null
   */
  private $description;

  /**
   * @ORM\Column(type="text")
   * @var string|null
   */
  private $short = "";

  /**
   * @ORM\Column(type="boolean")
   */
  private $isRestricted = 1;

  /**
   * @ORM\Column(type="text", nullable=true)
   * @var string|null
   */
  private $rules = "";

  /**
   * @ORM\OneToMany(targetEntity=DisciplinePower::class, mappedBy="discipline", orphanRemoval=true, fetch="EAGER"))
   * @var \Doctrine\Common\Collections\Collection<\App\Entity\DisciplinePower>
   */
  private $powers;

  public function __construct()
  {
    $this->powers = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function IsSinglePower(): bool
  {
    if (count($this->getPowers()) == 1) {
      return true;
    }
    return false;
  }

  public function getPower(): ?DisciplinePower
  {
    if ($this->IsSinglePower()) {
      return $this->powers->first();
    }
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): self
  {
    $this->short = $short;

    return $this;
  }

  public function getIsRestricted(): ?bool
  {
    return $this->isRestricted;
  }

  public function setIsRestricted(bool $isRestricted): self
  {
    $this->isRestricted = $isRestricted;

    return $this;
  }

  public function getRules(): ?string
  {
    return $this->rules;
  }

  public function setRules(?string $rules): self
  {
    $this->rules = $rules;

    return $this;
  }

  /**
   * @return Collection|DisciplinePower[]
   */
  public function getPowers(): Collection
  {
    return $this->powers;
  }

  public function addPower(DisciplinePower $power): self
  {
    if (!$this->powers->contains($power)) {
      $this->powers[] = $power;
      $power->setDiscipline($this);
    }

    return $this;
  }

  public function removePower(DisciplinePower $power): self
  {
    if ($this->powers->removeElement($power)) {
      // set the owning side to null (unless already changed)
      if ($power->getDiscipline() === $this) {
        $power->setDiscipline(null);
      }
    }

    return $this;
  }

  public function getMaxLevel(): int
  {
    return count($this->powers);
  }
}
