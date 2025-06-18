<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\LegacyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: LegacyRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "legacies"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "legacies")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\LegacyTranslation")]
class Legacy implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = "";

  #[ORM\ManyToOne(inversedBy: 'legacies')]
  private ?Path $path = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $nickname = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $short = "";

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $emblem = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $quote = null;

  #[ORM\ManyToOne(inversedBy: 'legacies')]
  private ?MageOrder $parentOrder = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Arcanum $arcanum = null;

  /**
   * @var Collection<int, Attainment>
   */
  #[ORM\OneToMany(targetEntity: Attainment::class, mappedBy: 'legacy', orphanRemoval: true, cascade: ['persist', 'remove'])]
  #[ORM\OrderBy(["level" => "ASC"])]
  private Collection $attainments;

  #[ORM\Column]
  private ?bool $isLeftHanded = false;

  public function __construct()
  {
      $this->attainments = new ArrayCollection();
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
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  public function getPath(): ?Path
  {
    return $this->path;
  }

  public function setPath(?Path $path): static
  {
    $this->path = $path;

    return $this;
  }

  public function getNickname(): ?string
  {
    return $this->nickname;
  }

  public function setNickname(string $nickname): static
  {
    $this->nickname = $nickname;

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

  public function setEmblem(?string $emblem): static
  {
    $this->emblem = $emblem;

    return $this;
  }

  public function getQuote(): ?string
  {
    return $this->quote;
  }

  public function setQuote(?string $quote): static
  {
    $this->quote = $quote;

    return $this;
  }

  public function getParentOrder(): ?MageOrder
  {
    return $this->parentOrder;
  }

  public function setParentOrder(?MageOrder $parentOrder): static
  {
    $this->parentOrder = $parentOrder;

    return $this;
  }

  public function getArcanum(): ?Arcanum
  {
    return $this->arcanum;
  }

  public function setArcanum(?Arcanum $arcanum): static
  {
    $this->arcanum = $arcanum;

    return $this;
  }

  /**
   * @return Collection<int, Attainment>
   */
  public function getAttainments(): Collection
  {
      return $this->attainments;
  }

  public function addAttainment(Attainment $attainment): static
  {
      if (!$this->attainments->contains($attainment)) {
          $this->attainments->add($attainment);
          $attainment->setLegacy($this);
      }

      return $this;
  }

  public function removeAttainment(Attainment $attainment): static
  {
      if ($this->attainments->removeElement($attainment)) {
          // set the owning side to null (unless already changed)
          if ($attainment->getLegacy() === $this) {
              $attainment->setLegacy(null);
          }
      }

      return $this;
  }

  public function isLeftHanded(): ?bool
  {
      return $this->isLeftHanded;
  }

  public function setIsLeftHanded(bool $isLeftHanded): static
  {
      $this->isLeftHanded = $isLeftHanded;

      return $this;
  }
}
