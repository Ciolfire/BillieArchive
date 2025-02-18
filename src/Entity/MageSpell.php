<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\MageSpellRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "spells"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "spells")])]
#[ORM\Entity(repositoryClass: MageSpellRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\MageSpellTranslation")]
class MageSpell implements Translatable
{
  public const Type = [
    0 => 'roll.action.instant',
    1 => 'roll.action.extended',
    2 => 'roll.action.reflexive',
  ];

  use Homebrewable;
  use Sourcable;

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
  private ?string $description = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $action = null;

  #[ORM\Column]
  private ?bool $isVulgar = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $cost = "None";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $rules = null;

  #[ORM\ManyToOne]
  private ?Skill $skill = null;

  #[ORM\ManyToOne(inversedBy: 'spells')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Arcanum $arcanum = null;

  #[ORM\ManyToOne(inversedBy: 'spells')]
  #[ORM\JoinColumn(nullable: false)]
  private ?MagicalPractice $practice = null;

  #[ORM\Column(type: Types::STRING)]
  private ?string $duration = null;

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

  public function getAction(): ?int
  {
    return $this->action;
  }

  public function setAction(int $action): static
  {
    $this->action = $action;

    return $this;
  }

  public function getActionName(): string
  {
    return self::Type[$this->action];
  }

  public function isVulgar(): ?bool
  {
    return $this->isVulgar;
  }

  public function setIsVulgar(bool $isVulgar): static
  {
    $this->isVulgar = $isVulgar;

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

  public function getRules(): ?string
  {
    return $this->rules;
  }

  public function setRules(string $rules): static
  {
    if ($this->rules == "") {
      $this->rules = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $rules);
    } else {
      $this->rules = $rules;
    }

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

  public function getArcanum(): ?Arcanum
  {
    return $this->arcanum;
  }

  public function setArcanum(?Arcanum $arcanum): static
  {
    $this->arcanum = $arcanum;

    return $this;
  }

  public function getPractice(): ?MagicalPractice
  {
    return $this->practice;
  }

  public function setPractice(?MagicalPractice $practice): static
  {
    $this->practice = $practice;

    return $this;
  }

  public function getDuration(): ?string
  {
    return $this->duration;
  }

  public function setDuration(string $duration): static
  {
    $this->duration = $duration;

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
}
