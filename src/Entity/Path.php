<?php

namespace App\Entity;

use App\Entity\Attribute;
use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\PathRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "paths"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "paths")])]
#[ORM\Entity(repositoryClass: PathRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\PathTranslation")]
class Path
{
  use Sourcable;
  use Homebrewable;
  
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 40)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = "";

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Attribute $attribute = null;

  /**
   * @var Collection<int, Arcanum>
   */
  #[ORM\ManyToMany(targetEntity: Arcanum::class, inversedBy: 'paths')]
  private Collection $rulingArcana;

  #[ORM\ManyToOne(inversedBy: 'inferiorPaths')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Arcanum $inferiorArcanum = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $nimbus = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $short = null;

  #[ORM\Column(length: 255)]
  private ?string $emblem = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $title = null;

  public function __construct()
  {
    $this->rulingArcana = new ArrayCollection();
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

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    $this->description = $description;

    return $this;
  }

  public function getAttribute(): ?Attribute
  {
    return $this->attribute;
  }

  public function setAttribute(?Attribute $attribute): static
  {
    $this->attribute = $attribute;

    return $this;
  }

  /**
   * @return Collection<int, Arcanum>
   */
  public function getRulingArcana(): Collection
  {
    return $this->rulingArcana;
  }

  public function addRulingArcana(Arcanum $rulingArcana): static
  {
    if (!$this->rulingArcana->contains($rulingArcana)) {
      $this->rulingArcana->add($rulingArcana);
    }

    return $this;
  }

  public function removeRulingArcana(Arcanum $rulingArcana): static
  {
    $this->rulingArcana->removeElement($rulingArcana);

    return $this;
  }

  public function getInferiorArcanum(): ?Arcanum
  {
    return $this->inferiorArcanum;
  }

  public function setInferiorArcanum(?Arcanum $inferiorArcanum): static
  {
    $this->inferiorArcanum = $inferiorArcanum;

    return $this;
  }

  public function getNimbus(): ?string
  {
    return $this->nimbus;
  }

  public function setNimbus(string $nimbus): static
  {
    $this->nimbus = $nimbus;

    return $this;
  }

  public function getShort(): ?string
  {
      return $this->short;
  }

  public function setShort(string $short): static
  {
      $this->short = $short;

      return $this;
  }

  public function getEmblem(): ?string
  {
      return $this->emblem;
  }

  public function setEmblem(string $emblem): static
  {
      $this->emblem = $emblem;

      return $this;
  }

  public function getTitle(): ?string
  {
      return $this->title;
  }

  public function setTitle(string $title): static
  {
      $this->title = $title;

      return $this;
  }
}
