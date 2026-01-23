<?php

declare(strict_types=1);

namespace App\Entity\Item;

use App\Form\Item\ThrownWeaponForm;
use App\Repository\ThrownWeaponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "item_weapon_thrown")]
#[ORM\Entity(repositoryClass: ThrownWeaponRepository::class)]
class ThrownWeapon extends Weapon
{
  #[ORM\Column(type: Types::BOOLEAN)]
  private ?bool $isAerodynamic = false;

  // #[ORM\Column]
  // private ?bool $hasNineAgain = false;

  public function getTypeName()
  {
    return "thrown_weapon";
  }

  public function getForm()
  {
    return ThrownWeaponForm::class;
  }

  public function getIsAerodynamic(): ?bool
  {
    return $this->isAerodynamic;
  }

  public function setIsAerodynamic(bool $isAerodynamic): static
  {
    $this->isAerodynamic = $isAerodynamic;

    return $this;
  }

  public function getRange(): int
  {
    $character = $this->getOwner();
    if ($character && $this->size < $character->getAttributes()->getStrength()) {
      $range = $character->getAttributes()->getStrength() + $character->getSkills()->getAthletics() - $this->size;
      if ($this->isAerodynamic) {
        return $range * 2;
      }

      return $range;
    }

    return 0;
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
      'aerodynamic' => [
        'icon' => 'aerodynamic',
        'value' => $this->isAerodynamic,
      ]
    ];

    if ($this->getOwner()) {
      $columns['range'] = [
        'icon' => 'ranges',
        'value' =>  $this->getRange(),
      ];
    }

    return $columns;
  }
}
