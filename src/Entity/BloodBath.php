<?php

namespace App\Entity;

use App\Repository\BloodBathRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BloodBathRepository::class)]
class BloodBath
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column]
  private array $bath = [];

  #[ORM\Column]
  private array $blood = [];

  #[ORM\Column]
  private array $effects = [];

  #[ORM\Column]
  private array $frequency = [];

  #[ORM\Column]
  private array $preparation = [];

  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getBath(): array
  {
    return $this->bath;
  }

  public function setBath(array $bath): static
  {
    $this->bath = $bath;

    return $this;
  }

  public function getBlood(): array
  {
    return $this->blood;
  }

  public function setBlood(array $blood): static
  {
    $this->blood = $blood;

    return $this;
  }

  public function getEffects(): array
  {
    return $this->effects;
  }

  public function setEffects(array $effects): static
  {
    $this->effects = $effects;

    return $this;
  }

  public function getFrequency(): array
  {
    return $this->frequency;
  }

  public function setFrequency(array $frequency): static
  {
    $this->frequency = $frequency;

    return $this;
  }

  public function getPreparation(): array
  {
    return $this->preparation;
  }

  public function setPreparation(array $preparation): static
  {
    $this->preparation = $preparation;

    return $this;
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
}
