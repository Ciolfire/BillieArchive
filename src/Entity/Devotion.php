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

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book",inversedBy: "devotions"),])]
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

  public function __construct()
  {
    $this->prerequisites = new ArrayCollection();
    $this->description = "";
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
}
