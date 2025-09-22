<?php

declare(strict_types=1);

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
use Gedmo\Translatable\Translatable;

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "rituals"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "rituals")])]
#[ORM\Entity(repositoryClass: DisciplinePowerRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\DisciplinePowerTranslation")]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class DisciplinePower implements Translatable
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
  private ?string $name;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING)]
  private string $short = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $details = "";

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?int $level;

  #[ORM\Column(type: Types::BOOLEAN)]
  private bool $isRitual = false;

  #[ORM\ManyToOne(targetEntity: Discipline::class, inversedBy: "powers")]
  #[ORM\JoinColumn(nullable: false)]
  private ?Discipline $discipline = null;

  #[ORM\ManyToMany(targetEntity: Attribute::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $attributes;

  #[ORM\ManyToMany(targetEntity: Skill::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $skills;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $contestedText = null;

  #[ORM\Column]
  private ?bool $usePotency = false;

  #[ORM\Column]
  private ?bool $canToggle = false;

  /**
   * @var Collection<int, StatusEffect>
   */
  #[ORM\OneToMany(targetEntity: StatusEffect::class, mappedBy: 'disciplinePower', cascade: ["persist"])]
  private Collection $statusEffects;

  public function __construct(Discipline $discipline, int $level)
  {
    $this->discipline = $discipline;
    $this->level = $level;
    $this->attributes = new ArrayCollection();
    $this->skills = new ArrayCollection();
    $this->statusEffects = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function __toString(): string
  {
    if ($this->name === null) {

      return (string)$this->id;
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

  public function getLevelDots(): string
  {
    if (is_null($this->level) || $this->level == 0) {

      return '• - •••••';
    }
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

  public function getDetails(): string
  {
    return $this->details;
  }

  public function setDetails(string $details = ""): self
  {
    if ($this->details == "") {
      $this->details = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $details);
    } else {
      $this->details = $details;
    }

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

  public function isRitual(): bool
  {
    return $this->isRitual;
  }

  public function setIsRitual(bool $isRitual): self
  {
    $this->isRitual = $isRitual;

    return $this;
  }

  public function setDiscipline(?Discipline $discipline): self
  {
    $this->discipline = $discipline;

    return $this;
  }

  public function dicePool(Vampire $character): mixed
  {
    /** @var Discipline $discipline */
    $discipline = $this->discipline;
    $id = (int)$discipline->getId();
    $characterDiscipline = $character->getDiscipline($id);
    if ($characterDiscipline) {
      $level = $characterDiscipline->getLevel();
    } else {
      $level = 0;
    }

    if ($discipline->isThaumaturgy()) {
      $bonus =  $level - $this->level;
    } else {
      $bonus = $level;
    }

    if ($this->usePotency) {
      $bonus += $character->getPotency();
    }

    return $character->dicePool($this->attributes, $this->skills, $bonus);
  }

  public function detailedDicePool(Vampire|Ghoul $character): mixed
  {
    /** @var Discipline $discipline */
    $discipline = $this->discipline;
    $id = (int)$discipline->getId();
    if ($character instanceof Ghoul) {
      $charDiscipline = $character->getDiscipline($id);
    } else {
      $charDiscipline = $character->getDiscipline($id);
    }

    $modifiers = [
      $discipline->getName() => $charDiscipline ? $charDiscipline->getLevel() : 0,
    ];
    if ($discipline->isThaumaturgy()) {
      $modifiers['thaumaturgy'] = -$this->level;
    }

    if ($this->usePotency) {
      $modifiers['potency'] = $character->getPotency();
    }

    if ($this->attributes->isEmpty() && $this->skills->isEmpty()) {
      return null;
    }

    return $character->detailedDicePool($this->attributes, $this->skills, null, $modifiers);
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

  public function getCosts(): array
  {
    $costs = [];

    if (preg_match("/Cost[*]*:([^:]+)/i", $this->getDetails(), $matches)) {

      $costsString = $matches[1];

      if (preg_match("/([\d]+) Vitae/i", $costsString, $matches)) {
        $costs['vitae'] = intval($matches[1]);
      }

      if (preg_match("/([\d]+) Willpower(?! dot)/i", $costsString, $matches)) {
        $costs['willpower'] = intval($matches[1]);
      }

      if (preg_match("/([\d]+) Willpower dot/i", $costsString, $matches)) {
        $costs['willpowerDot'] = intval($matches[1]);
      }
    }

    return $costs;
  }

  public function isUsePotency(): ?bool
  {
    return $this->usePotency;
  }

  public function setUsePotency(bool $usePotency): static
  {
    $this->usePotency = $usePotency;

    return $this;
  }

  public function isCanToggle(): ?bool
  {
    return $this->canToggle;
  }

  public function setCanToggle(bool $canToggle): static
  {
    $this->canToggle = $canToggle;

    return $this;
  }

  /**
   * @return Collection<int, StatusEffect>
   */
  public function getStatusEffects(): array
  {
    $effects = $this->statusEffects->toArray();
    foreach ($effects as $key => $effect) {
      /** @var StatusEffect $effect */
      if ($effect->getOwner()) {
        unset($effects[$key]);
      }
    }

    return ($effects);
    // dd($effects);
  }

  public function addStatusEffect(StatusEffect $statusEffect): static
  {
    if (!$this->statusEffects->contains($statusEffect)) {
      $this->statusEffects->add($statusEffect);
      $statusEffect->setDisciplinePower($this);
    }

    return $this;
  }

  public function removeStatusEffect(StatusEffect $statusEffect): static
  {
    if ($this->statusEffects->removeElement($statusEffect)) {
      // set the owning side to null (unless already changed)
      if ($statusEffect->getDisciplinePower() === $this) {
        $statusEffect->setDisciplinePower(null);
      }
    }

    return $this;
  }
}
