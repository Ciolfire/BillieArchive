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

  #[ORM\Column]
  private ?bool $isLevelDependant = false;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $choice = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $icon = null;

  #[ORM\ManyToOne(inversedBy: 'statusEffects')]
  private ?DisciplinePower $disciplinePower = null;

  #[ORM\ManyToOne(inversedBy: 'statusEffects')]
  protected ?Character $owner = null;

  #[ORM\ManyToOne(inversedBy: 'statusEffects')]
  private ?PossessedVestment $possessedVestment = null;

  // #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'statusEffects')]
  // #[ORM\ManyToOne(targetEntity: Item::class, mappedBy: 'statusEffects')]
  #[ORM\ManyToOne(inversedBy: 'statusEffects')]
  private ?Item $item = null;

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

  public function getRealValue(): int
  {
    // If level dependant, return level x value
    if ($this->isLevelDependant()) {
      if ($power = $this->getDisciplinePower()) {
        if ($this->owner instanceof Vampire) {
          foreach ($this->owner->getDisciplines() as $discipline) {
            /** @var VampireDiscipline $discipline */
            if ($discipline->getDiscipline() == $power->getDiscipline()) {

              return $discipline->getLevel() * $this->value;
            }
          }
        }
      }
    }
    return $this->value;
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

  public function getDefaultIcon(): ?string
  {
    switch ($this->type) {
      case 'skill':
        return "skills/{$this->choice}";
      case 'armor':
      case 'defense':
      case 'health':
      case 'speed':
      case 'willpower':
        return $this->type;
    }

    if ($this->getDisciplinePower()) {
      return "discipline";
    } else if ($this->getPossessedVestment()) {
      return "type/possessed";
    }

    return "info";
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
    $label = "";
    $label .= $this->name;

    if ($this->description) {
      if ($label != "") {
        $label .= "\n————\n";
      }
      $label .= $this->description;
    }

    // Translation issue, to think about
    // if ($label != "") {
    //   $label .= "\n————\n";
    // }
    // if ($this->choice) {
    //   $label .= $this->choice;
    // }

    return $label;
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

  public function getOwner(): ?Character
  {
    return $this->owner;
  }

  public function setOwner(?Character $owner): static
  {
    $this->owner = $owner;

    return $this;
  }

  public function getPossessedVestment(): ?PossessedVestment
  {
    return $this->possessedVestment;
  }

  public function setPossessedVestment(?PossessedVestment $possessedVestment): static
  {
    $this->possessedVestment = $possessedVestment;

    return $this;
  }

  public function getItem(): ?Item
  {
    return $this->item;
  }

  public function setItem(?Item $item): static
  {
    $this->item = $item;

    return $this;
  }
}
