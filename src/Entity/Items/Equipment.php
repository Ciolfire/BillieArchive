<?php

declare(strict_types=1);

namespace App\Entity\Items;

use App\Entity\Item;
use App\Form\EquipmentType;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Table(name: "item_equipment")]
#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment extends Item
{
  public function getTypeName()
  {
    return "equipment";
  }

  public function getForm()
  {
    return EquipmentType::class;
  }

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $quality = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $functionality = "";

  public function getQuality(): ?int
  {
    return $this->quality;
  }

  public function setQuality(int $quality): static
  {
    $this->quality = $quality;

    return $this;
  }

  public function getFunctionality(): ?string
  {
    return $this->functionality;
  }

  public function setFunctionality(string $functionality): static
  {
    if ($this->functionality == "") {
      $this->functionality = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $functionality);
    } else {
      $this->functionality = $functionality;
    }

    return $this;
  }
}
