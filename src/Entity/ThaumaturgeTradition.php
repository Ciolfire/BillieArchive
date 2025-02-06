<?php

namespace App\Entity;

use App\Repository\ThaumaturgeTraditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;

#[ORM\Entity(repositoryClass: ThaumaturgeTraditionRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\ThaumaturgeTraditionTranslation")]
class ThaumaturgeTradition implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 100)]
  private ?string $name = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Merit $definingMerit = null;

  /**
   * @var Collection<int, Merit>
   */
  #[ORM\ManyToMany(targetEntity: Merit::class)]
  private Collection $pathMerits;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $strengths = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $weaknesses = null;


  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $quote = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $creation = null;

  public function __construct()
  {
    $this->pathMerits = new ArrayCollection();
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

  public function getDefiningMerit(): ?Merit
  {
    return $this->definingMerit;
  }

  public function setDefiningMerit(?Merit $definingMerit): static
  {
    $this->definingMerit = $definingMerit;

    return $this;
  }

  /**
   * @return Collection<int, Merit>
   */
  public function getPathMerits(): Collection
  {
    return $this->pathMerits;
  }

  public function addPathMerit(Merit $pathMerit): static
  {
    if (!$this->pathMerits->contains($pathMerit)) {
      $this->pathMerits->add($pathMerit);
    }

    return $this;
  }

  public function removePathMerit(Merit $pathMerit): static
  {
    $this->pathMerits->removeElement($pathMerit);

    return $this;
  }

  public function getStrengths(): ?string
  {
    return $this->strengths;
  }

  public function setStrengths(string $strengths): static
  {
    $this->strengths = $strengths;

    return $this;
  }

  public function getWeaknesses(): ?string
  {
    return $this->weaknesses;
  }

  public function setWeaknesses(string $weaknesses): static
  {
    $this->weaknesses = $weaknesses;

    return $this;
  }

  public function getQuote(): ?string
  {
    return $this->quote;
  }

  public function setQuote(string $quote): static
  {
    $this->quote = $quote;

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

  public function getCreation(): ?string
  {
    return $this->creation;
  }

  public function setCreation(string $creation): static
  {
    $this->creation = $creation;

    return $this;
  }
}
