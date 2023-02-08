<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\DisciplineRepository;
use App\Entity\Translation\DisciplineTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book",inversedBy: "disciplines")])]
#[ORM\Entity(repositoryClass: DisciplineRepository::class)]
#[Gedmo\TranslationEntity(class: DisciplineTranslation::class)]
class Discipline
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
  #[ORM\Column(type: Types::STRING, length: 50)]
  private $name;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private $description;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
  private $short;

  #[ORM\Column(type: Types::BOOLEAN)]
  private $isRestricted =  1;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private $rules;

  #[ORM\OneToMany(targetEntity: DisciplinePower::class, mappedBy: "discipline", orphanRemoval: true, fetch: "EAGER")]
  private $powers;

  #[ORM\Column]
  private ?bool $isThaumaturgy = null;

  public function __construct()
  {
    $this->powers = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function IsSinglePower(): bool
  {
    if (count($this->getPowers()) == 1) {
      return true;
    }
    return false;
  }

  public function getPower(): ?DisciplinePower
  {
    if ($this->IsSinglePower()) {

      return $this->powers->first();
    } else return null;
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
    $this->description = $description;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(?string $short): self
  {
    $this->short = $short;

    return $this;
  }

  public function getIsRestricted(): ?bool
  {
    return $this->isRestricted;
  }

  public function setIsRestricted(bool $isRestricted): self
  {
    $this->isRestricted = $isRestricted;

    return $this;
  }

  public function getRules(): ?string
  {
    return $this->rules;
  }

  public function setRules(string $rules): self
  {
    if (!is_null($rules)) {
      $converter = new HtmlConverter();
      $rules = $converter->convert($rules);
    } else {
      $rules = "";
    }
    $this->rules = $rules;

    return $this;
  }

  /**
   * @return Collection|DisciplinePower[]
   */
  public function getPowers(): Collection
  {
    return $this->powers;
  }

  public function addPower(DisciplinePower $power): self
  {
    if (!$this->powers->contains($power)) {
      $this->powers[] = $power;
      $power->setDiscipline($this);
    }

    return $this;
  }

  public function removePower(DisciplinePower $power): self
  {
    if ($this->powers->removeElement($power)) {
      // set the owning side to null (unless already changed)
      if ($power->getDiscipline() === $this) {
        $power->setDiscipline(null);
      }
    }

    return $this;
  }

  public function getMaxLevel(): int
  {
    return count($this->powers);
  }

  public function isThaumaturgy(): ?bool
  {
      return $this->isThaumaturgy;
  }

  public function setIsThaumaturgy(bool $isThaumaturgy): self
  {
      $this->isThaumaturgy = $isThaumaturgy;

      return $this;
  }
}
