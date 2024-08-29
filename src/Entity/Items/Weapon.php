<?php

declare(strict_types=1);

namespace App\Entity\Items;

use App\Entity\Item;
use App\Form\WeaponType;
use App\Repository\VehicleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Weapon extends Item
{

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $damage = 0;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $special = "";

  // #[ORM\Column]
  // private ?bool $isTwoHanded = false;

  // #[ORM\Column]
  // private ?bool $hasNineAgain = false;

  public function getTypeName()
  {
    return "weapon";
  }

  public function getForm()
  {
    return WeaponType::class;
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

  public function getStrength(): mixed
  {
      return $this->size;
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
