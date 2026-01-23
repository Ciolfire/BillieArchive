<?php

declare(strict_types=1);

namespace App\Entity\Item;

use App\Form\Item\RangedWeaponForm;
use App\Repository\RangedWeaponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "item_weapon_ranged")]
#[ORM\Entity(repositoryClass: RangedWeaponRepository::class)]
class RangedWeapon extends Weapon
{
  #[ORM\Column(length: 255)]
  private ?string $ranges = "";

  #[ORM\Column(length: 255)]
  private ?string $clip = "";

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $strength = null;

  // #[ORM\Column]
  // private ?bool $isTwoHanded = false;

  // #[ORM\Column]
  // private ?bool $hasNineAgain = false;

  // #[ORM\Column]
  // private ?bool $hasAutofire = false;

  public function getTypeName()
  {
    return "ranged_weapon";
  }

  public function getForm()
  {
    return RangedWeaponForm::class;
  }

  public function getRanges(): ?string
  {
    return $this->ranges;
  }

  public function setRanges(string $ranges): static
  {
    $this->ranges = $ranges;

    return $this;
  }

  public function getClip(): ?string
  {
    return $this->clip;
  }

  public function setClip(?string $clip): static
  {
    $this->clip = $clip;

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

  public function getColumns(): array
  {
    $columns = [
      'damage' => [
        'icon' => 'damage',
        'value' => $this->damage,
      ],
      'ranges' => [
        'icon' => 'ranges',
        'value' => $this->ranges,
      ],
      'clip' => [
        'icon' => 'clip',
        'value' => $this->clip,
      ],
      'strength' => [
        'icon' => 'strength',
        'value' =>  $this->size,
      ],
    ];

    return $columns;
  }
}
