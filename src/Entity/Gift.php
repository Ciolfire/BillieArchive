<?php

namespace App\Entity;

use App\Entity\Traits\Action;
use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;

use App\Repository\GiftRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: GiftRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "gifts"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "gifts")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\GiftListTranslation")]
class Gift implements Translatable
{
  use Sourcable;
  use Homebrewable;
  use Action;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Gedmo\Translatable]
  private ?string $name = null;

  #[ORM\Column(length: 255)]
  #[Gedmo\Translatable]
  private ?string $short = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Gedmo\Translatable]
  private ?string $details = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $level = null;

  #[ORM\ManyToOne(inversedBy: 'gifts')]
  #[ORM\JoinColumn(nullable: false)]
  private ?GiftList $list = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $cost = "None";

  #[ORM\ManyToOne]
  private ?Attribute $attribute = null;

  #[ORM\ManyToOne]
  private ?Skill $skill = null;

  #[ORM\ManyToOne]
  private ?Renown $renown = null;

  #[ORM\Column]
  private bool $isContested = false;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $contestedText = null;

  public function __construct(GiftList $list)
  {
    $this->list = $list;
    $this->level = count($list->getGifts()) + 1;
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

  public function getList(): ?GiftList
  {
    return $this->list;
  }

  public function setList(?GiftList $list): static
  {
    $this->list = $list;

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

  public function getAttribute(): ?Attribute
  {
    return $this->attribute;
  }

  public function setAttribute(?Attribute $attribute): static
  {
    $this->attribute = $attribute;

    return $this;
  }

  public function getSkill(): ?Skill
  {
    return $this->skill;
  }

  public function setSkill(?Skill $skill): static
  {
    $this->skill = $skill;

    return $this;
  }

  public function getRenown(): ?Renown
  {
    return $this->renown;
  }

  public function setRenown(?Renown $renown): static
  {
    $this->renown = $renown;

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
