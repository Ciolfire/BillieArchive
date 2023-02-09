<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Translation\DevotionTranslation;
use App\Repository\DevotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "devotions")])]
#[ORM\Entity(repositoryClass: DevotionRepository::class)]
#[Gedmo\TranslationEntity(class: DevotionTranslation::class)]
class Devotion
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Locale]
  private $locale;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\ManyToMany(targetEntity: Prerequisite::class, inversedBy: 'devotions', cascade: ['persist', 'remove'])]
  private Collection $prerequisites;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $cost = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  #[ORM\Column(length: 255)]
  private ?string $short = null;

  #[ORM\ManyToMany(targetEntity: Attribute::class)]
  private Collection $attributes;

  #[ORM\ManyToMany(targetEntity: Skill::class)]
  private Collection $skills;

  #[ORM\ManyToMany(targetEntity: Discipline::class)]
  private Collection $disciplines;

  #[ORM\ManyToOne(inversedBy: 'devotions')]
  private ?Clan $bloodline = null;

  public function __construct()
  {
    $this->prerequisites = new ArrayCollection();
    $this->description = "";
    $this->attributes = new ArrayCollection();
    $this->skills = new ArrayCollection();
    $this->disciplines = new ArrayCollection();
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

  /**
   * @return Collection<int, Prerequisite>
   */
  public function getPrerequisites(): Collection
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

  public function getCost(): ?int
  {
    return $this->cost;
  }

  public function setCost(int $cost): self
  {
    $this->cost = $cost;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    if (!is_null($description)) {
      $converter = new HtmlConverter();
      $description = $converter->convert($description);
    } else {
      $description = "";
    }
    $this->description = $description;

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

  /**
   * @return Collection<int, Attribute>
   */
  public function getAttributes(): Collection
  {
      return $this->attributes;
  }

  public function addAttribute(Attribute $attribute): self
  {
      if (!$this->attributes->contains($attribute)) {
          $this->attributes->add($attribute);
      }

      return $this;
  }

  public function removeAttribute(Attribute $attribute): self
  {
      $this->attributes->removeElement($attribute);

      return $this;
  }

  /**
   * @return Collection<int, Skill>
   */
  public function getSkills(): Collection
  {
      return $this->skills;
  }

  public function addSkill(Skill $skill): self
  {
      if (!$this->skills->contains($skill)) {
          $this->skills->add($skill);
      }

      return $this;
  }

  public function removeSkill(Skill $skill): self
  {
      $this->skills->removeElement($skill);

      return $this;
  }

  /**
   * @return Collection<int, Discipline>
   */
  public function getDisciplines(): Collection
  {
      return $this->disciplines;
  }

  public function addDiscipline(Discipline $discipline): self
  {
      if (!$this->disciplines->contains($discipline)) {
          $this->disciplines->add($discipline);
      }

      return $this;
  }

  public function removeDiscipline(Discipline $discipline): self
  {
      $this->disciplines->removeElement($discipline);

      return $this;
  }

  public function getBloodline(): ?Clan
  {
      return $this->bloodline;
  }

  public function setBloodline(?Clan $bloodline): self
  {
      $this->bloodline = $bloodline;

      return $this;
  }
}
