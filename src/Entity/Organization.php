<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Form\OrganizationType;
use App\Repository\OrganizationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "organizations")])]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: Types::STRING)]
#[ORM\DiscriminatorMap(["organization" => Organization::class, "covenant" => Covenant::class, "order" => MageOrder::class])]
class Organization
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  protected ?int $id = null;

  #[ORM\Column(length: 100)]
  protected ?string $name = null;

  #[ORM\Column(length: 255, nullable: true)]
  protected ?string $emblem = null;

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $description = "";

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $overview = "";

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $members = "";

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $philosophy = "";

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $observances = "";

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $titles = "";

  #[ORM\Column(type: Types::TEXT)]
  private ?string $short = "";

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
    return OrganizationType::class;
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
    $this->description = $description;

    return $this;
  }

  public function getOverview(): ?string
  {
    return $this->overview;
  }

  public function setOverview(string $overview): static
  {
    $this->overview = $overview;

    return $this;
  }

  public function getMembers(): ?string
  {
    return $this->members;
  }

  public function setMembers(string $members): static
  {
    $this->members = $members;

    return $this;
  }

  public function getPhilosophy(): ?string
  {
    return $this->philosophy;
  }

  public function setPhilosophy(string $philosophy): static
  {
    $this->philosophy = $philosophy;

    return $this;
  }

  public function getObservances(): ?string
  {
    return $this->observances;
  }

  public function setObservances(string $observances): static
  {
    $this->observances = $observances;

    return $this;
  }

  public function getTitles(): ?string
  {
    return $this->titles;
  }

  public function setTitles(string $titles): static
  {
    $this->titles = $titles;

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
}
