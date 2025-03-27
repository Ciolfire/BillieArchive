<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\SiddhiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiddhiRepository::class)]
class Siddhi
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  /**
   * @var Collection<int, SiddhiPower>
   */
  #[ORM\OneToMany(targetEntity: SiddhiPower::class, mappedBy: 'siddhi', orphanRemoval: true)]
  private Collection $powers;

  public function __construct()
  {
    $this->powers = new ArrayCollection();
  }

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

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    $this->description = $description;

    return $this;
  }

  /**
   * @return Collection<int, SiddhiPower>
   */
  public function getPowers(): Collection
  {
    return $this->powers;
  }

  public function addPower(SiddhiPower $power): static
  {
    if (!$this->powers->contains($power)) {
      $this->powers->add($power);
      $power->setSiddhi($this);
    }

    return $this;
  }

  public function removePower(SiddhiPower $power): static
  {
    if ($this->powers->removeElement($power)) {
      // set the owning side to null (unless already changed)
      if ($power->getSiddhi() === $this) {
        $power->setSiddhi(null);
      }
    }

    return $this;
  }
}
