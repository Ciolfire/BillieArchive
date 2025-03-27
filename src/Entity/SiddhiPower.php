<?php

namespace App\Entity;

use App\Entity\Traits\Rollable;
use App\Repository\SiddhiPowerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiddhiPowerRepository::class)]
class SiddhiPower
{
  use Rollable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column]
  private ?int $level = null;

  #[ORM\ManyToOne(inversedBy: 'powers')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Siddhi $siddhi = null;

  #[ORM\Column(length: 255)]
  private ?string $short = null;

  #[ORM\Column(length: 255)]
  private ?string $cost = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getLevel(): ?int
  {
    return $this->level;
  }

  public function setLevel(int $level): static
  {
    $this->level = $level;

    return $this;
  }

  public function getSiddhi(): ?Siddhi
  {
    return $this->siddhi;
  }

  public function setSiddhi(?Siddhi $siddhi): static
  {
    $this->siddhi = $siddhi;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): static
  {
    $this->short = $short;

    return $this;
  }

  public function getCost(): ?string
  {
    return $this->cost;
  }

  public function setCost(string $cost): static
  {
    $this->cost = $cost;

    return $this;
  }
}
