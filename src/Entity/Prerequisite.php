<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\PrerequisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrerequisiteRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Prerequisite
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column]
  private string $type = "";

  #[ORM\Column]
  private ?int $entityId = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $value;

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?int $choiceGroup = null;

  #[ORM\ManyToMany(targetEntity: Merit::class, mappedBy: 'prerequisites')]
  private Collection $merits;

  #[ORM\ManyToMany(targetEntity: Devotion::class, mappedBy: 'prerequisites')]
  private Collection $devotions;

  private mixed $entity = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $special = null;

  public function __construct()
  {
    $this->merits = new ArrayCollection();
    $this->devotions = new ArrayCollection();
  }

  public function __toString()
  {
    return "id:{$this->entityId}, type:{$this->type}, value:{$this->value}";
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getType(): string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getEntityId(): ?int
  {
    return $this->entityId;
  }

  public function setEntityId(int $entityId): self
  {
    $this->entityId = $entityId;

    return $this;
  }

  public function getValue(): ?int
  {
    return $this->value;
  }

  public function setValue(int $value): self
  {
    $this->value = $value;

    return $this;
  }

  public function getChoiceGroup(): ?int
  {
    return $this->choiceGroup;
  }

  public function setChoiceGroup(?int $choiceGroup): self
  {
    $this->choiceGroup = $choiceGroup;

    return $this;
  }

  /**
   * @return Collection<int, Merit>
   */
  public function getMerits(): Collection
  {
    return $this->merits;
  }

  public function addMerit(Merit $merit): self
  {
    if (!$this->merits->contains($merit)) {
      $this->merits->add($merit);
      $merit->addPrerequisite($this);
    }

    return $this;
  }

  public function removeMerit(Merit $merit): self
  {
    if ($this->merits->removeElement($merit)) {
      $merit->removePrerequisite($this);
    }

    return $this;
  }

  public function getEntity() : ?object
  {
    return $this->entity;
  }

  public function setEntity(object $entity) : self
  {
    $this->entity = $entity;

    return $this;
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
          $devotion->addPrerequisite($this);
      }

      return $this;
  }

  public function removeDevotion(Devotion $devotion): self
  {
      if ($this->devotions->removeElement($devotion)) {
          $devotion->removePrerequisite($this);
      }

      return $this;
  }

  public function getRealType(): string
  {
    return strtolower(substr($this->type, strrpos($this->type, '\\') + 1));
  }

  public function getSpecial(): ?string
  {
      return $this->special;
  }

  public function setSpecial(?string $special): static
  {
      $this->special = $special;

      return $this;
  }
}
