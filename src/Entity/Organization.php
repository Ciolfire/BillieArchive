<?php

namespace App\Entity;

use App\Entity\Traits\Ancient;
use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Form\OrganizationForm;
use App\Repository\OrganizationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "organizations")])]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: Types::STRING)]
#[ORM\DiscriminatorMap(["organization" => Organization::class, "covenant" => Covenant::class, "order" => MageOrder::class])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\OrganizationTranslation")]
class Organization implements Translatable
{
  use Sourcable;
  use Homebrewable;
  use Ancient;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  protected ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 100)]
  protected ?string $name = null;

  #[ORM\Column(length: 255, nullable: true)]
  protected ?string $emblem = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  protected ?string $description = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  protected ?string $overview = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  protected ?string $members = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  protected ?string $philosophy = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  protected ?string $observances = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  protected ?string $titles = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $short = "";

  #[ORM\Column]
  private ?bool $isPrivate = null;

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  static public function getType(): string
  {
    return 'organization';
  }

  static public function getForm(): string
  {
    return OrganizationForm::class;
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

  public function getEmblem(): ?string
  {
    return $this->emblem;
  }

  public function setEmblem(?string $emblem): static
  {
    $this->emblem = $emblem;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  public function getOverview(): ?string
  {
    return $this->overview;
  }

  public function setOverview(string $overview): static
  {
    if ($this->overview == "") {
      $this->overview = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $overview);
    } else {
      $this->overview = $overview;
    }

    return $this;
  }

  public function getMembers(): ?string
  {
    return $this->members;
  }

  public function setMembers(string $members): static
  {
    if ($this->members == "") {
      $this->members = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $members);
    } else {
      $this->members = $members;
    }

    return $this;
  }

  public function getPhilosophy(): ?string
  {
    return $this->philosophy;
  }

  public function setPhilosophy(string $philosophy): static
  {
    if ($this->philosophy == "") {
      $this->philosophy = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $philosophy);
    } else {
      $this->philosophy = $philosophy;
    }

    return $this;
  }

  public function getObservances(): ?string
  {
    return $this->observances;
  }

  public function setObservances(string $observances): static
  {
    if ($this->observances == "") {
      $this->observances = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $observances);
    } else {
      $this->observances = $observances;
    }

    return $this;
  }

  public function getTitles(): ?string
  {
    return $this->titles;
  }

  public function setTitles(string $titles): static
  {
    if ($this->titles == "") {
      $this->titles = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $titles);
    } else {
      $this->titles = $titles;
    }

    return $this;
  }

  public function getSetting(): string
  {

    return "human";
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

  public function isPrivate(): ?bool
  {
      return $this->isPrivate;
  }

  public function setIsPrivate(bool $isPrivate): static
  {
      $this->isPrivate = $isPrivate;

      return $this;
  }
}
