<?php

namespace App\Entity;

use App\Repository\PrerequisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrerequisiteRepository::class)]
class Prerequisite
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column]
  private ?string $type = null;

  #[ORM\Column]
  private ?int $entityId = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $value = null;

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?int $choiceGroup = null;

  #[ORM\ManyToMany(targetEntity: Merit::class, mappedBy: 'Prereqs')]
  private Collection $merits;

  public function __construct()
  {
      $this->merits = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getType(): ?string
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
          $merit->addPrereq($this);
      }

      return $this;
  }

  public function removeMerit(Merit $merit): self
  {
      if ($this->merits->removeElement($merit)) {
          $merit->removePrereq($this);
      }

      return $this;
  }
}
