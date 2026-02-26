<?php

namespace App\Entity;

use App\Entity\Traits\Action;
use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;

use App\Repository\RiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: RiteRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "rites"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "rites")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\RiteTranslation")]
class Rite
{

  use Homebrewable;
  use Sourcable;
  use Action;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $short = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $details = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $level = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $cost = null;

  #[ORM\Column]
  private bool $isContested = false;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $contestedText = null;

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

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): static
  {
    $this->short = $short;

    return $this;
  }

  public function getDetails(): ?string
  {
    return $this->details;
  }

  public function setDetails(string $details): static
  {
    if (empty($this->details)) {
      $this->details = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $details);
    } else {
      $this->details = $details;
    }

    return $this;
  }

  public function getLevel(): ?int
  {
    return $this->level;
  }

  public function setLevel(int $level): static
  {
    $this->level = $level;

    return $this;
  }

  public function getCost(): ?string
  {
    return $this->cost;
  }

  public function setCost(string $cost): static
  {
    $this->cost = $cost;

    return $this;
  }

  public function isContested(): bool
  {
    return $this->isContested;
  }

  public function setIsContested(bool $isContested): self
  {
    $this->isContested = $isContested;

    return $this;
  }

  public function getContestedText(): ?string
  {
    return $this->contestedText;
  }

  public function setContestedText(?string $contestedText): self
  {
    $this->contestedText = $contestedText;

    return $this;
  }

  public function getCosts(): array
  {
    $costs = [];


    if (preg_match("/([\d]+) Essence/i", $this->cost, $matches)) {
      $costs['essence'] = intval($matches[1]);
    }

    if (preg_match("/([\d]+) Willpower(?! dot)/i", $this->cost, $matches)) {
      $costs['willpower'] = intval($matches[1]);
    }

    if (preg_match("/([\d]+) Willpower dot/i", $this->cost, $matches)) {
      $costs['willpowerDot'] = intval($matches[1]);
    }

    return $costs;
  }
}
