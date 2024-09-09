<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Traits\Typed;
use App\Repository\MeritRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\Table(name: "merits")]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "merits"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "merits")])]
#[ORM\Entity(repositoryClass: MeritRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\MeritTranslation")]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Merit implements Translatable
{
  use Typed;
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 40)]
  private string $name = "";

  #[ORM\Column(type: Types::STRING, length: 20)]
  private string $category;

  #[ORM\Column(type: Types::BOOLEAN)]
  private bool $isFighting;

  #[ORM\Column(type: Types::BOOLEAN)]
  private bool $isExpanded;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $min = 1;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $max;

  #[ORM\Column(type: Types::BOOLEAN)]
  private bool $isUnique;

  #[ORM\Column(type: Types::BOOLEAN)]
  private bool $isCreationOnly;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $effect = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $description;

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

  public function isFighting(): ?bool
  {
    return $this->isFighting;
  }

  public function setIsFighting(bool $isFighting): self
  {
    $this->isFighting = $isFighting;

    return $this;
  }

  public function isExpanded(): ?bool
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

  public function isUnique(): ?bool
  {
    return $this->isUnique;
  }

  public function setIsUnique(bool $isUnique): self
  {
    $this->isUnique = $isUnique;

    return $this;
  }

  public function isCreationOnly(): ?bool
  {
    return $this->isCreationOnly;
  }

  public function setIsCreationOnly(bool $isCreationOnly): self
  {
    $this->isCreationOnly = $isCreationOnly;

    return $this;
  }

  public function getEffect(): string
  {
    return $this->effect;
  }

  public function setEffect(string $effect = ""): self
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
