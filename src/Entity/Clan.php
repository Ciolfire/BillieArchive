<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\ClanRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;


#[Gedmo\TranslationEntity(class: "App\Entity\Translation\ClanTranslation")]
#[ORM\Table(name: "clan")]
#[ORM\Entity(repositoryClass: ClanRepository::class)]
// #[ORM\AssociationOverrides([
//   new ORM\AssociationOverride(
//     name: "book",
//     inversedBy: "clans"
//   ),
// ])]
class Clan
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private $id;

  #[Gedmo\Translatable]
  #[ORM\Column(type: "string", length: 20, nullable: true)]
  private $name;

  #[Gedmo\Translatable]
  #[ORM\Column(type: "text")]
  private $description = "";

  #[ORM\ManyToMany(targetEntity: Attribute::class)]
  private $attributes;

  #[ORM\ManyToMany(targetEntity: Discipline::class)]
  private $disciplines;

  #[Gedmo\Translatable]
  #[ORM\Column(type: "text")]
  private $short = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: "string", length: 100)]
  private $keywords;

  #[ORM\ManyToOne(targetEntity: Clan::class, inversedBy: "bloodlines")]
  private $parentClan;

  #[ORM\OneToMany(targetEntity: Clan::class, mappedBy: "parentClan")]
  private $bloodlines;

  #[ORM\Column(type: "string", length: 255, nullable: true)]
  private $emblem;

  public function __construct()
  {
    $this->attributes = new ArrayCollection();
    $this->disciplines = new ArrayCollection();
    $this->bloodlines = new ArrayCollection();
  }

  public function __toString()
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

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $converter = new HtmlConverter();
    $this->description = $converter->convert($description);

    return $this;
  }

  /**
   * @return Collection|Attribute[]
   */
  public function getAttributes(): Collection
  {
    return $this->attributes;
  }

  public function addAttribute(Attribute $attribute): self
  {
    if (!$this->attributes->contains($attribute)) {
      $this->attributes[] = $attribute;
    }

    return $this;
  }

  public function removeAttribute(Attribute $attribute): self
  {
    $this->attributes->removeElement($attribute);

    return $this;
  }

  /**
   * @return Collection|Discipline[]
   */
  public function getDisciplines(): Collection
  {
    return $this->disciplines;
  }

  public function addDiscipline(Discipline $discipline): self
  {
    if (!$this->disciplines->contains($discipline)) {
      $this->disciplines[] = $discipline;
    }

    return $this;
  }

  public function removeDiscipline(Discipline $discipline): self
  {
    $this->disciplines->removeElement($discipline);

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): self
  {
    $this->short = $short;

    return $this;
  }

  public function getKeywords(): ?string
  {
    return $this->keywords;
  }

  public function setKeywords(string $keywords): self
  {
    $this->keywords = $keywords;

    return $this;
  }

  public function isFavored(Discipline $discipline): bool
  {
    if ($this->disciplines->contains($discipline)) {

      return true;
    }

    return false;
  }

  public function isBloodline(): ?bool
  {
    if ($this->parentClan == null) {

      return true;
    }
    return false;
  }

  public function getParentClan(): ?self
  {
    return $this->parentClan;
  }

  public function setParentClan(?self $parentClan): self
  {
    $this->parentClan = $parentClan;

    return $this;
  }

  public function getBloodlines(): Collection
  {
    return $this->bloodlines;
  }

  public function addBloodline(self $bloodline): self
  {
    if (!$this->bloodlines->contains($bloodline)) {
      $this->bloodlines[] = $bloodline;
      $bloodline->setParentClan($this);
    }

    return $this;
  }

  public function removeBloodline(self $bloodline): self
  {
    if ($this->bloodlines->removeElement($bloodline)) {
      // set the owning side to null (unless already changed)
      if ($bloodline->getParentClan() === $this) {
        $bloodline->setParentClan(null);
      }
    }

    return $this;
  }

  public function getEmblem(): ?string
  {
    return $this->emblem;
  }

  public function setEmblem(?string $emblem): self
  {
    $this->emblem = $emblem;

    return $this;
  }
}
