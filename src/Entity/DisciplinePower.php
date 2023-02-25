<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Vampire;
use App\Repository\DisciplinePowerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\Entity(repositoryClass: DisciplinePowerRepository::class)]
class DisciplinePower
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private $id;

  #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
  private $name;

  #[ORM\Column(type: Types::STRING)]
  private $short = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private $details = "";

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private $level;

  #[ORM\ManyToOne(targetEntity: Discipline::class, inversedBy: "powers")]
  #[ORM\JoinColumn(nullable: false)]
  private $discipline;

  #[ORM\ManyToMany(targetEntity: Attribute::class)]
  private Collection $attributes;

  #[ORM\ManyToMany(targetEntity: Skill::class)]
  private Collection $skills;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $contestedText = null;

  public function __construct($discipline, $level)
  {
    $this->discipline = $discipline;
    $this->level = $level;
    $this->attributes = new ArrayCollection();
    $this->skills = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function __toString()
  {
    if ($this->name === null) {

      return $this->id;
    }
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
    $converter = new HtmlConverter();
    $details = $converter->convert($details);
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

  public function setDiscipline(?Discipline $discipline): self
  {
    $this->discipline = $discipline;

    return $this;
  }

  public function dicePool(Vampire $character)
  {
    $characterDiscipline = $character->getDiscipline($this->discipline->getId());
    if ($characterDiscipline) {
      $level = $characterDiscipline->getLevel();
    } else {
      $level = 0;
    }

    if ($this->discipline->isThaumaturgy()) {
      $bonus =  $level - $this->level;
    } else {
      $bonus = $level;
    }
    
    return $character->dicePool($this->attributes, $this->skills, $bonus);
  }

  public function detailedDicePool(Vampire $character)
  {
    $discipline = $character->getDiscipline($this->discipline->getId());
    // dd($this->discipline->getId());
    
    $modifiers = [
      $this->discipline->getName() => $discipline? $discipline->getLevel() : 0,
    ];
    if ($this->discipline->isThaumaturgy()) {
      $modifiers['thaumaturgy'] = -$this->level;
    }

    if ($this->attributes->isEmpty() && $this->skills->isEmpty()) {
      return null;
    }
    
    return $character->detailedDicePool($this->attributes, $this->skills, $modifiers);
  }

  /**
   * @return Collection<int, Attribute>
   */
  public function getAttributes(): Collection
  {
      return $this->attributes;
  }

  public function addAttribute(Attribute $attribute): self
  {
      if (!$this->attributes->contains($attribute)) {
          $this->attributes->add($attribute);
      }

      return $this;
  }

  public function removeAttribute(Attribute $attribute): self
  {
      $this->attributes->removeElement($attribute);

      return $this;
  }

  /**
   * @return Collection<int, Skill>
   */
  public function getSkills(): Collection
  {
      return $this->skills;
  }

  public function addSkill(Skill $skill): self
  {
      if (!$this->skills->contains($skill)) {
          $this->skills->add($skill);
      }

      return $this;
  }

  public function removeSkill(Skill $skill): self
  {
      $this->skills->removeElement($skill);

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
