<?php

declare(strict_types=1);

namespace App\Entity\Item;

use App\Entity\Item;
use App\Form\Item\VehicleForm;
use App\Repository\VehicleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "item_vehicle")]
#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle extends Item
{
  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $acceleration = null;

  #[ORM\Column]
  private ?int $safeSpeed = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $maxSpeed = null;

  #[ORM\Column(length: 255)]
  private ?string $handling = null;

  public function getForm()
  {
    return VehicleForm::class;
  }

  public function getTypeName()
  {
    return "vehicle";
  }

  public function getAcceleration(): ?int
  {
    return $this->acceleration;
  }

  public function setAcceleration(int $acceleration): static
  {
    $this->acceleration = $acceleration;

    return $this;
  }

  public function getSafeSpeed(): ?int
  {
    return $this->safeSpeed;
  }

  public function setSafeSpeed(int $safeSpeed): static
  {
    $this->safeSpeed = $safeSpeed;

    return $this;
  }

  public function getMaxSpeed(): ?int
  {
    return $this->maxSpeed;
  }

  public function setMaxSpeed(int $maxSpeed): static
  {
    $this->maxSpeed = $maxSpeed;

    return $this;
  }

  public function getHandling(): ?string
  {
    return $this->handling;
  }

  public function setHandling(string $handling): static
  {
    $this->handling = $handling;

    return $this;
  }
}
