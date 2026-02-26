<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WerewolfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: WerewolfRepository::class)]
class Werewolf extends Character
{
  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $primalUrge = 1;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $essence = 0;

  #[ORM\ManyToOne(inversedBy: 'werewolves')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Auspice $auspice = null;

  #[ORM\ManyToOne(inversedBy: 'werewolves')]
  private ?Tribe $tribe = null;

  #[ORM\OneToOne(targetEntity: WerewolfRenown::class, inversedBy: "werewolf", orphanRemoval: true, cascade: ["persist"])]
  protected WerewolfRenown $renowns;

  /**
   * @var Collection<int, Gift>
   */
  #[ORM\ManyToMany(targetEntity: Gift::class)]
  private Collection $gifts;

  public function __construct(bool $isAncient = false, bool $isNpc = false, ?Chronicle $chronicle = null)
  {
    parent::__construct(isAncient: $isAncient, isNpc: $isNpc, chronicle: $chronicle);

    $this->gifts = new ArrayCollection();
  }

  public function getPrimalUrge(): ?int
  {
    return $this->primalUrge;
  }

  public function setPrimalUrge(int $primalUrge): static
  {
    $this->primalUrge = $primalUrge;

    return $this;
  }

  public function getEssence(): ?int
  {
    return $this->essence;
  }

  public function setEssence(int $essence): static
  {
    $this->essence = $essence;

    return $this;
  }

  public function getAuspice(): ?Auspice
  {
    return $this->auspice;
  }

  public function setAuspice(?Auspice $auspice): static
  {
    $this->auspice = $auspice;

    return $this;
  }

  public function getTribe(): ?Tribe
  {
    return $this->tribe;
  }

  public function setTribe(?Tribe $tribe): static
  {
    $this->tribe = $tribe;

    return $this;
  }

  /**
   * @return Collection<int, Gift>
   */
  public function getGifts(): Collection
  {
    return $this->gifts;
  }

  public function addGift(Gift $gift): static
  {
    if (!$this->gifts->contains($gift)) {
      $this->gifts->add($gift);
    }

    return $this;
  }

  public function removeGift(Gift $gift): static
  {
    $this->gifts->removeElement($gift);

    return $this;
  }
}
