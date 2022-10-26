<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\MeritRepository;
use App\Entity\Translation\MeritTranslation;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;


#[ORM\Table(name: "merits")]
#[ORM\Entity(repositoryClass: MeritRepository::class)]
// #[ORM\AssociationOverrides([
//   new ORM\AssociationOverride(
//     name: "book",
//     inversedBy: "merits"
//   )
// ])]
#[Gedmo\TranslationEntity(class: MeritTranslation::class)]
class Merit
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private $id;

  #[Gedmo\Locale]
  private $locale;

  #[Gedmo\Translatable]
  #[ORM\Column(type: "string", length: 40)]
  private $name;

  #[ORM\Column(type: "string", length: 20)]
  private $category;

  #[ORM\Column(type: "boolean")]
  private $isFighting;

  #[ORM\Column(type: "boolean")]
  private $isExpanded;

  #[ORM\Column(type: "smallint")]
  private $min = 1;

  #[ORM\Column(type: "smallint")]
  private $max;

  #[ORM\Column(type: "boolean")]
  private $isUnique;

  #[ORM\Column(type: "boolean")]
  private $isCreationOnly;

  #[ORM\Column(type: "json", nullable: true)]
  private $prerequisites = [];

  #[Gedmo\Translatable]
  #[ORM\Column(type: "text")]
  private $effect = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: "text")]
  private $description;

  #[ORM\Column(type: "string", length: 20)]
  private $type;

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

  public function getPrerequisites(): ?array
  {
    return $this->prerequisites;
  }

  public function setPrerequisites(?array $prerequisites): self
  {
    $this->prerequisites = $prerequisites;

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
}
