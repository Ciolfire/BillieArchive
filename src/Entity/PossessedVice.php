<?php

namespace App\Entity;

use App\Repository\PossessedViceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PossessedViceRepository::class)]
class PossessedVice
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Vice $vice = null;

  #[ORM\ManyToOne(inversedBy: 'vices')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Possessed $possessed = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $level = null;

  /**
   * @var Collection<int, PossessedVestment>
   */
  #[ORM\ManyToMany(targetEntity: PossessedVestment::class)]
  private Collection $vestments;

  public function __construct(Vice $vice, int $level = 0)
  {
    $this->vice = $vice;
    $this->level = $level;
    $this->vestments = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->getVice()->getName();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getVice(): ?Vice
  {
    return $this->vice;
  }

  public function setVice(?Vice $vice): static
  {
    $this->vice = $vice;

    return $this;
  }

  public function getPossessed(): ?Possessed
  {
    return $this->possessed;
  }

  public function setPossessed(?Possessed $possessed): static
  {
    $this->possessed = $possessed;

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

  /**
   * @return Collection<int, PossessedVestment>
   */
  public function getVestments(): Collection
  {
    return $this->vestments;
  }

  public function addVestment(PossessedVestment $vestment): static
  {
    if (!$this->vestments->contains($vestment)) {
      $this->vestments->add($vestment);
    }

    return $this;
  }

  public function removeVestment(PossessedVestment $vestment): static
  {
    $this->vestments->removeElement($vestment);

    return $this;
  }

  public function getVestmentsByLevel()
  {
    $vestments = [];
    foreach ($this->vestments as $vestment) {
      if (!isset($vestments[$vestment->getLevel()])) {
        $vestments[$vestment->getLevel()] = 0;
      }
      $vestments[$vestment->getLevel()]++;
    }

    return $vestments;
  }

  public function isPrimary() : bool
  {
    if ($this->vice === $this->possessed->getSourceCharacter()->getVice()) {

      return true;
    }

    return false;
  }
}
