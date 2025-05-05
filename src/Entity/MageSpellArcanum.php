<?php

namespace App\Entity;

use App\Repository\MageSpellArcanumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MageSpellArcanumRepository::class)]
class MageSpellArcanum
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'arcana')]
  #[ORM\JoinColumn(nullable: false)]
  private ?MageSpell $spell = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Arcanum $arcanum = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $level = null;

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?int $choiceGroup = null;

  #[ORM\Column]
  private ?bool $isOptional = false;

  public function __construct(MageSpell $spell)
  {
    $spell->addArcana($this);
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName() : string
  {
    return $this->arcanum->getName();
  }

  public function getIdentifier() : string
  {
    return $this->arcanum->getIdentifier();
  }

  public function getSpell(): ?MageSpell
  {
    return $this->spell;
  }

  public function setSpell(?MageSpell $spell): static
  {
    $this->spell = $spell;

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

  public function getLevel(): ?int
  {
    return $this->level;
  }

  public function setLevel(int $level): static
  {
    $this->level = $level;

    return $this;
  }

  public function getChoiceGroup(): ?int
  {
    return $this->choiceGroup;
  }

  public function setChoiceGroup(?int $choiceGroup): static
  {
    $this->choiceGroup = $choiceGroup;

    return $this;
  }

  public function isOptional(): ?bool
  {
    return $this->isOptional;
  }

  public function setIsOptional(bool $isOptional): static
  {
    $this->isOptional = $isOptional;

    return $this;
  }
}
