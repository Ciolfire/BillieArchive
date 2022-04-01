<?php

namespace App\Entity;

use App\Entity\Vampire;
use App\Repository\DisciplinePowerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisciplinePowerRepository::class)
 */
class DisciplinePower
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=50, nullable=true)
   */
  private $name;

  /**
   * @ORM\ManyToOne(targetEntity=Discipline::class, inversedBy="powers")
   * @ORM\JoinColumn(nullable=false)
   */
  private $discipline;

  /**
   * @ORM\ManyToOne(targetEntity=Attribute::class)
   */
  private $attribute;

  /**
   * @ORM\ManyToOne(targetEntity=Skill::class)
   */
  private $skill;

  /**
   * @ORM\Column(type="text")
   */
  private $details;

  /**
   * @ORM\Column(type="smallint", nullable=true)
   */
  private $level;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(?string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getLevelDots(): ?string
  {
    return str_repeat('•', $this->level);
  }

  public function getDiscipline(): ?Discipline
  {
    return $this->discipline;
  }

  public function setDiscipline(?Discipline $discipline): self
  {
    $this->discipline = $discipline;

    return $this;
  }

  public function getAttribute(): ?Attribute
  {
    return $this->attribute;
  }

  public function setAttribute(?Attribute $attribute): self
  {
    $this->attribute = $attribute;

    return $this;
  }

  public function getSkill(): ?Skill
  {
    return $this->skill;
  }

  public function setSkill(?Skill $skill): self
  {
    $this->skill = $skill;

    return $this;
  }

  public function getDetails(): ?string
  {
    return $this->details;
  }

  public function setDetails(string $details): self
  {
    $this->details = $details;

    return $this;
  }

  public function getLevel(): ?int
  {
    return $this->level;
  }

  public function setLevel(?int $level): self
  {
    $this->level = $level;

    return $this;
  }

  public function dicePool(Vampire $character)
  {
    $discipline = $character->getDisciplines()->get($this->discipline->getId());
    return $character->dicePool($this->attribute, $this->skill, $discipline->getLevel());
  }
}
