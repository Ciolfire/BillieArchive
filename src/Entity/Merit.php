<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\MeritRepository;
use App\Entity\Translation\MeritTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\Table(name: "merits")]
#[ORM\Entity(repositoryClass: MeritRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "merits"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "merits")])]
#[Gedmo\TranslationEntity(class: MeritTranslation::class)]
class Merit
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private $id;

  #[Gedmo\Locale]
  private $locale;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 40)]
  private $name;

  #[ORM\Column(type: Types::STRING, length: 20)]
  private $category;

  #[ORM\Column(type: Types::BOOLEAN)]
  private $isFighting;

  #[ORM\Column(type: Types::BOOLEAN)]
  private $isExpanded;

  #[ORM\Column(type: Types::SMALLINT)]
  private $min = 1;

  #[ORM\Column(type: Types::SMALLINT)]
  private $max;

  #[ORM\Column(type: Types::BOOLEAN)]
  private $isUnique;

  #[ORM\Column(type: Types::BOOLEAN)]
  private $isCreationOnly;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private $effect = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private $description;

  #[ORM\Column(type: Types::STRING, length: 20)]
  private $type;

  #[ORM\ManyToMany(targetEntity: Prerequisite::class, inversedBy: 'merits', cascade: ['persist', 'remove'])]
  #[ORM\OrderBy(["choiceGroup" => "ASC", "type" => "ASC"])]
  private Collection $prerequisites;


  public function __construct()
  {
    $this->prerequisites = new ArrayCollection();
  }

  public function __toString(): string
  {
    return $this->name;
  }


  public function getId(): ?int
  {
    return $this->id;
  }

  public function setTranslatableLocale($locale)
  {
    $this->locale = $locale;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getCategory(): ?string
  {
    return $this->category;
  }

  public function setCategory(string $category): self
  {
    $this->category = $category;

    return $this;
  }

  public function getIsFighting(): ?bool
  {
    return $this->isFighting;
  }

  public function setIsFighting(bool $isFighting): self
  {
    $this->isFighting = $isFighting;

    return $this;
  }

  public function getIsExpanded(): ?bool
  {
    return $this->isExpanded;
  }

  public function setIsExpanded(bool $isExpanded): self
  {
    $this->isExpanded = $isExpanded;

    return $this;
  }

  public function getMin(): ?int
  {
    return $this->min;
  }

  public function setMin(int $min): self
  {
    $this->min = $min;

    return $this;
  }

  public function getMax(): ?int
  {
    return $this->max;
  }

  public function setMax(int $max): self
  {
    $this->max = $max;

    return $this;
  }

  public function getIsUnique(): ?bool
  {
    return $this->isUnique;
  }

  public function setIsUnique(bool $isUnique): self
  {
    $this->isUnique = $isUnique;

    return $this;
  }

  public function getIsCreationOnly(): ?bool
  {
    return $this->isCreationOnly;
  }

  public function setIsCreationOnly(bool $isCreationOnly): self
  {
    $this->isCreationOnly = $isCreationOnly;

    return $this;
  }

  public function getEffect(): ?string
  {
    return $this->effect;
  }

  public function setEffect(string $effect): self
  {
    $converter = new HtmlConverter();
    $this->effect = $converter->convert($effect);

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }

  /**
   * @return Collection<int, Prerequisite>
   */
  public function getprerequisites(): Collection
  {
    return $this->prerequisites;
  }

  public function addPrerequisite(Prerequisite $prerequisite): self
  {
    if (!$this->prerequisites->contains($prerequisite)) {
      $this->prerequisites->add($prerequisite);
    }

    return $this;
  }

  public function removePrerequisite(Prerequisite $prerequisite): self
  {
    $this->prerequisites->removeElement($prerequisite);

    return $this;
  }
}
