<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\GhoulFamilyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "ghoulFamilies"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "ghoulFamilies")])]
#[ORM\Entity(repositoryClass: GhoulFamilyRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\GhoulFamilyTranslation")]
class GhoulFamily implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $short = "";

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $emblem = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 50)]
  private ?string $nickname = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $strength = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $weakness = "";

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $quote = null;

  #[ORM\ManyToOne(inversedBy: 'ghoulFamilies')]
  private ?Clan $clan = null;

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

  public function getNickname(): ?string
  {
    return $this->nickname;
  }

  public function setNickname(string $nickname): static
  {
    $this->nickname = $nickname;

    return $this;
  }

  public function getStrength(): ?string
  {
    return $this->strength;
  }

  public function setStrength(string $strength): static
  {
    $this->strength = $strength;

    return $this;
  }

  public function getWeakness(): ?string
  {
    return $this->weakness;
  }

  public function setWeakness(string $weakness): static
  {
    $this->weakness = $weakness;

    return $this;
  }

  public function getQuote(): ?string
  {
    return $this->quote;
  }

  public function setQuote(string $quote): static
  {
    $this->quote = $quote;

    return $this;
  }

  public function getClan(): ?Clan
  {
      return $this->clan;
  }

  public function setClan(?Clan $clan): static
  {
      $this->clan = $clan;

      return $this;
  }
}
