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

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $strength = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $defense = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $speed = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $ratingMelee = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $ratingRanged = null;

  #[ORM\Column]
  private ?bool $isBulletproof = null;

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
        'value' => $this->getRatingString(),
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

  public function getRatingMelee(): ?int
  {
    return $this->ratingMelee;
  }

  public function setRatingMelee(int $ratingMelee): static
  {
    $this->ratingMelee = $ratingMelee;

    return $this;
  }

  public function getRatingRanged(): ?int
  {
    return $this->ratingRanged;
  }

  public function setRatingRanged(int $ratingRanged): static
  {
    $this->ratingRanged = $ratingRanged;

    return $this;
  }

  public function getRatingString(): ?string
  {
    return "{$this->ratingMelee} / {$this->ratingRanged}";
  }

  public function isBulletproof(): ?bool
  {
      return $this->isBulletproof;
  }

  public function setIsBulletproof(bool $isBulletproof): static
  {
      $this->isBulletproof = $isBulletproof;

      return $this;
  }
}
