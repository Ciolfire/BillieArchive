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
   * @var int|null
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=50, nullable=true)
   * @var string|null
   */
  private $name;

  /**
   * @ORM\ManyToOne(targetEntity=Discipline::class, inversedBy="powers")
   * @ORM\JoinColumn(nullable=false)
   * @var \App\Entity\Discipline|null
   */
  private $discipline;

  /**
   * @ORM\ManyToOne(targetEntity=Attribute::class, fetch="EAGER"))
   * @var \App\Entity\Attribute|null
   */
  private $attribute;

  /**
   * @ORM\ManyToOne(targetEntity=Skill::class, fetch="EAGER"))
   * @var \App\Entity\Skill|null
   */
  private $skill;

  /**
   * @ORM\Column(type="text")
   * @var string|null
   */
  private $short = "";

  /**
   * @ORM\Column(type="text")
   * @var string|null
   */
  private $details;

  /**
   * @ORM\Column(type="smallint", nullable=true)
   * @var int|null
   */
  private $level;

  public function __construct($discipline, $level)
  {
    $this->discipline = $discipline;
    $this->level = $level;
  }

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

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): self
  {
    $this->short = $short;

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
    // dd($character->getDisciplines(), $this);
    $discipline = $character->getDisciplines()->filter(function($element) {
      if ($element->getDiscipline() == $this->discipline) {
        return $element;
      }
    })->first();
    if ($discipline) {
      $level = $discipline->getLevel();
    } else {
      $level = 0;
    }
    
    return $character->dicePool($this->attribute, $this->skill, $level);
  }
}
