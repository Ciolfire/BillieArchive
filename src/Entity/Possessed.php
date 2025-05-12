<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PossessedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PossessedRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Possessed extends CharacterLesserTemplate
{
  protected $limit = 5;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $currentInfernalWill = 0;

  /**
   * @var Collection<int, PossessedVice>
   */
  #[ORM\OneToMany(targetEntity: PossessedVice::class, mappedBy: 'possessed', orphanRemoval: true, cascade: ["persist"])]
  private Collection $vices;


  public function __construct()
  {
    $this->vices = new ArrayCollection();
  }

  public function __clone()
  {
    parent::__clone();
  }

  public function getType(): string
  {
    return "possessed";
  }

  public function getSetting(): string
  {
    return "human";
  }

  public static function getForm(): ?string
  {
    return null;
  }

  public function getPrimaryVice(): ?PossessedVice
  {
    foreach ($this->vices as $vice) {
      if ($vice->getVice() === $this->getSourceCharacter()->getVice()) {
        return $vice;
      }
    }

    return null;
  }

  public function getInfernalWill(): ?int
  {
    return $this->getPrimaryVice()->getLevel();
  }

  public function getCurrentInfernalWill(): ?int
  {
    return $this->currentInfernalWill;
  }

  public function setCurrentInfernalWill(int $currentInfernalWill): static
  {
    $this->currentInfernalWill = $currentInfernalWill;

    return $this;
  }

  /**
   * @return array
   */
  public function getVestments(): array
  {
    $vestments = [];
    foreach ($this->vices as $vice) {
      foreach ($vice->getVestments() as $vestment) {
        $vestments[$vestment->getLevel()][] = $vestment;
      }
    }
    ksort($vestments);
    return $vestments;
  }

  // public function addVestment(PossessedVestment $vestment): static
  // {
  //   if (!$this->vestments->contains($vestment)) {
  //     $this->vestments->add($vestment);
  //   }

  //   return $this;
  // }

  // public function removeVestment(PossessedVestment $vestment): static
  // {
  //   $this->vestments->removeElement($vestment);

  //   return $this;
  // }

  /**
   * @return Collection<int, PossessedVice>
   */
  public function getVices(): Collection
  {
    return $this->vices;
  }

  public function addVice(PossessedVice $vice): static
  {
    if (!$this->vices->contains($vice)) {
      $this->vices->add($vice);
      $vice->setPossessed($this);
    }

    return $this;
  }

  public function removeVice(PossessedVice $vice): static
  {
    if ($this->vices->removeElement($vice)) {
      // set the owning side to null (unless already changed)
      if ($vice->getPossessed() === $this) {
        $vice->setPossessed(null);
      }
    }

    return $this;
  }
}
