<?php

declare(strict_types=1);

namespace App\Entity\Item;

use App\Entity\Item;
use App\Form\Item\ArmorForm;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Table(name: "item_armor")]
#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Armor extends Item
{
  public function getTypeName()
  {
    return "armor";
  }

  public function getForm()
  {
    return ArmorForm::class;
  }
  
  #[ORM\Column(type: Types::TEXT)]
  private ?string $rating = "";
  
  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $strength = null;
  
  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $defense = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $speed = 0;

  public function getRating(): ?string
  {
    return $this->rating;
  }

  public function setRating(string $rating): static
  {
    if ($this->rating == "") {
      $this->rating = $rating;
    } else {
      $this->rating = $rating;
    }

    return $this;
  }

  public function getStrength(): ?int
  {
    return $this->strength;
  }

  public function setStrength(int $strength): static
  {
    $this->strength = $strength;

    return $this;
  }

  public function getDefense(): ?int
  {
    return $this->defense;
  }

  public function setDefense(int $defense): static
  {
    $this->defense = $defense;

    return $this;
  }

  public function getSpeed(): ?int
  {
    return $this->speed;
  }

  public function setSpeed(int $speed): static
  {
    $this->speed = $speed;

    return $this;
  }

  public function getColumns(): array
  {
    $columns = [
    'rating' => [
        'icon' => 'armor',
        'value' => $this->rating,
      ],
    'strength' => [
        'icon' => 'strength',
        'value' => $this->strength,
      ],
      'defense' => [
        'icon' => 'defense',
        'value' => $this->defense,
      ],
      'speed' => [
        'icon' => 'speed',
        'value' => $this->speed,
      ],
      'cost' => [
        'icon' => 'cost',
        'value' => $this->getCostString(),
      ],
    ];

    return $columns;
  }
}
