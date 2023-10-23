<?php

namespace App\Entity;

use App\Repository\CharacterLesserTemplateRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[ORM\Entity(repositoryClass: CharacterLesserTemplateRepository::class)]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'name', type: 'string')]
#[DiscriminatorMap(['ghoul' => Ghoul::class])]
class CharacterLesserTemplate
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column]
  private bool $isActive = true;

  #[ORM\ManyToOne(inversedBy: 'lesserTemplates')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Character $sourceCharacter = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getType()
  {
    return "";
  }

  public function getSetting()
  {
    return "";
  }

  public function getForm() : string
  {
    return "";
  }

  public function detailedDicePool(Collection $attributes, Collection $skills, ?Collection $specials = null, array $modifiers = []) : array
  {
    return $this->sourceCharacter->detailedDicePool($attributes, $skills, $specials, $modifiers);
  }

  public function getChronicle() : ?Chronicle
  {
    return $this->sourceCharacter->getChronicle();
  }

  public function getSourceCharacter(): ?Character
  {
    return $this->sourceCharacter;
  }

  public function setSourceCharacter(?Character $sourceCharacter): static
  {
    $this->sourceCharacter = $sourceCharacter;

    return $this;
  }

  public function isActive(): bool
  {
    return $this->isActive;
  }

  public function setIsActive(bool $isActive): static
  {
    $this->isActive = $isActive;

    return $this;
  }
}
