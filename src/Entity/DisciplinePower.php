<?php

namespace App\Entity;

use App\Entity\Vampire;
use App\Repository\DisciplinePowerRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;

use function PHPUnit\Framework\isNull;

#[ORM\Entity(repositoryClass: DisciplinePowerRepository::class)]
class DisciplinePower
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private $id;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 50, nullable: true)]
  private $name;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 150)]
  private $short = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT)]
  private $details = "";

  #[ORM\Column(type: "smallint", nullable: true)]
  private $level;

  #[ORM\ManyToOne(targetEntity: Discipline::class, inversedBy: "powers")]
  #[ORM\JoinColumn(nullable: false)]
  private $discipline;

  #[ORM\ManyToOne(targetEntity: Attribute::class, fetch: "EAGER")]
  private $attribute;

  #[ORM\ManyToOne(targetEntity: Skill::class, fetch: "EAGER")]
  private $skill;

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

  public function dicePool(Vampire $character)
  {
    $discipline = $character->getDiscipline($this->discipline->getId());
    if ($discipline) {
      $level = $discipline->getLevel();
    } else {
      $level = 0;
    }
    
    return $character->dicePool($this->attribute, $this->skill, $level);
  }

  public function detailedDicePool(Vampire $character)
  {
    $discipline = $character->getDiscipline($this->discipline->getId());
    if ($discipline) {
      $level = $discipline->getLevel();
    } else {
      $level = 0;
    }

    $pool = $character->detailedDicePool($this->attribute, $this->skill, $level);
    
    return [
      'total' => array_sum($pool),
      'detail' => "{$this->attribute} {$pool['attribute']} + {$this->skill} {$pool['skill']} + {$this->discipline} {$pool['bonus']}"
    ];
  }
}
