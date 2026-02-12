<?php

declare(strict_types=1);

namespace App\Entity\Item;

use App\Entity\Item;
use App\Entity\Types\DamageType;
use App\Form\Item\WeaponForm;
use App\Repository\WeaponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "item_weapon")]
#[ORM\Entity(repositoryClass: WeaponRepository::class)]
class Weapon extends Item
{

  #[ORM\Column(type: Types::SMALLINT)]
  protected ?int $damage = 0;

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $special = "";

  #[ORM\Column(type: Types::SMALLINT, enumType: DamageType::class)]
  protected DamageType $damageType = DamageType::lethal;

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
    return WeaponForm::class;
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
    if ($this->special == "") {
      $this->special = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $special);
    } else {
      $this->special = $special;
    }

    return $this;
  }

  public function getDamageType(): DamageType
  {
    return $this->damageType;
  }

  public function setDamageType(DamageType $damageType): static
  {
    $this->damageType = $damageType;

    return $this;
  }

  public function getColumns(): array
  {
    $columns = [
      'damage' => [
        'icon' => 'damage',
        'value' => $this->damage,
      ],
      'size' => [
        'icon' => 'size',
        'value' =>  $this->size,
      ],
    ];

    return $columns;
  }
}
