<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\PossessedVestmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: PossessedVestmentRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\PossessedVestmentTranslation")]
class PossessedVestment implements Translatable
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'possessedVestments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Vice $vice = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $level = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $effect = null;

  /**
   * @var Collection<int, StatusEffect>
   */
  #[ORM\OneToMany(targetEntity: StatusEffect::class, mappedBy: 'possessedVestment', cascade: ["persist"])]
  private Collection $statusEffects;

  #[ORM\Column]
  private ?bool $canToggle = null;

  public function __toString()
  {
    return $this->name;
  }

  public function __construct()
  {
    $this->statusEffects = new ArrayCollection();
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

  public function getLevel(): ?int
  {
    return $this->level;
  }

  public function setLevel(int $level): static
  {
    $this->level = $level;

    return $this;
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

  public function getEffect(): ?string
  {
    return $this->effect;
  }

  public function setEffect(string $effect): static
  {
        if ($this->effect == "") {
      $this->effect = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $effect);
    } else {
      $this->effect = $effect;
    }

    return $this;
  }

  /**
   * @return Collection<int, StatusEffect>
   */
  public function getStatusEffects(): Collection
  {
    return $this->statusEffects;
  }

  public function addStatusEffect(StatusEffect $statusEffect): static
  {
    if (!$this->statusEffects->contains($statusEffect)) {
      $this->statusEffects->add($statusEffect);
      $statusEffect->setPossessedVestment($this);
    }

    return $this;
  }

  public function removeStatusEffect(StatusEffect $statusEffect): static
  {
    if ($this->statusEffects->removeElement($statusEffect)) {
      // set the owning side to null (unless already changed)
      if ($statusEffect->getPossessedVestment() === $this) {
        $statusEffect->setPossessedVestment(null);
      }
    }

    return $this;
  }

  public function isCanToggle(): ?bool
  {
      return $this->canToggle;
  }

  public function setCanToggle(bool $canToggle): static
  {
      $this->canToggle = $canToggle;

      return $this;
  }
}
