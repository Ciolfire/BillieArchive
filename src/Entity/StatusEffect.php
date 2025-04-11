<?php

namespace App\Entity;

use App\Repository\StatusEffectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusEffectRepository::class)]
class StatusEffect
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $name = null;

  #[ORM\Column(length: 255)]
  private ?string $type = null;

  #[ORM\Column]
  private ?int $value = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  #[ORM\ManyToOne(inversedBy: 'statusEffects')]
  private ?DisciplinePower $disciplinePower = null;

  #[ORM\Column]
  private ?bool $isLevelDependant = false;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $choice = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $icon = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(?string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): static
  {
    $this->type = $type;

    return $this;
  }

  public function getValue(): ?int
  {
    if ($this->isLevelDependant) {
      if ($this->disciplinePower) {
        // Should reverse character from status, to get the discipline lvl
      }
    }

    return $this->value;
  }

  public function setValue(int $value): static
  {
    $this->value = $value;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    $this->description = $description;

    return $this;
  }

  public function getDisciplinePower(): ?DisciplinePower
  {
    return $this->disciplinePower;
  }

  public function setDisciplinePower(?DisciplinePower $disciplinePower): static
  {
    $this->disciplinePower = $disciplinePower;

    return $this;
  }

  public function isLevelDependant(): ?bool
  {
    return $this->isLevelDependant;
  }

  public function setIsLevelDependant(bool $isLevelDependant): static
  {
    $this->isLevelDependant = $isLevelDependant;

    return $this;
  }

  public function getChoice(): ?string
  {
    return $this->choice;
  }

  public function setChoice(?string $choice): static
  {
    $this->choice = $choice;

    return $this;
  }

  public function getIcon(): ?string
  {
    return $this->icon;
  }

  public function setIcon(?string $icon): static
  {
    $this->icon = $icon;

    return $this;
  }

  public function isLocked(): bool
  {
    if ($this->disciplinePower) {
      return true;
    }

    return false;
  }

  public function getLabel(): string
  {
    $label = $this->name;

    if ($this->description) {
      if ($label != "") {
        $label .= "\n————\n";
      }
      $label.= $this->description;
    }

    return $label;
  }
}
