<?php

declare(strict_types=1);

namespace App\Entity\Items;

use App\Entity\Item;
use App\Form\RangedWeaponType;
use App\Repository\VehicleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class RangedWeapon extends Item
{
  #[ORM\Column(length: 255)]
  private ?string $ranges = "";

  #[ORM\Column(length: 255)]
  private ?string $clip = "";

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $damage = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $strength = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $special = "";

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
    return RangedWeaponType::class;
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

  public function getDamage(): ?int
  {
    return $this->damage;
  }

  public function setDamage(int $damage): static
  {
    $this->damage = $damage;

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

  public function getSpecial(): ?string
  {
    return $this->special;
  }

  public function setSpecial(string $special): static
  {
    $this->special = $special;

    return $this;
  }
}
