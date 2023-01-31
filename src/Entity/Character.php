<?php

namespace App\Entity;

use App\Entity\References\DisciplineReferences;
use App\Entity\References\MeritReferences;
use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use League\HTMLToMarkdown\HtmlConverter;


#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: "characters")]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
// Probably not needed
#[ORM\DiscriminatorMap(["human" => Human::class, "vampire" => Vampire::class, "mage" => Mage::class, "werewolf" => Werewolf::class])]
class Character
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  protected $id;

  #[ORM\Column(type: "string", length: 50)]
  protected $name;

  #[ORM\OneToOne(targetEntity: CharacterAttributes::class, inversedBy: "character", cascade: ["persist", "remove"])]
  protected $attributes;

  #[ORM\OneToOne(targetEntity: CharacterSkills::class, inversedBy: "character", cascade: ["persist", "remove"])]
  protected $skills;

  #[ORM\OneToMany(targetEntity: CharacterSpecialty::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist"])]
  protected $specialties;

  #[ORM\Column(type: "integer", nullable: true, options: ["unsigned" => true])]
  protected $age;

  #[ORM\Column(type: "string", length: 50, nullable: true)]
  protected $concept;

  #[ORM\Column(type: "string", length: 25, nullable: true)]
  protected $faction;

  #[ORM\Column(type: "string", length: 25, nullable: true)]
  protected $groupName;

  #[ORM\Column(type: "smallint")]
  protected $willpower = 0;

  #[ORM\ManyToOne(targetEntity: Virtue::class)]
  protected $virtue;

  #[ORM\ManyToOne(targetEntity: Vice::class)]
  protected $vice;

  #[ORM\Column(type: "smallint")]
  protected $moral = 7;

  #[ORM\Column(type: "json", nullable: true)]
  protected $wounds = ['B' => 0, 'L' => 0, 'A' => 0];

  #[ORM\Column(type: "smallint")]
  protected $size = 5;

  #[ORM\Column(type: "smallint")]
  protected $currentWillpower = 0;

  #[ORM\OneToMany(targetEntity: CharacterMerit::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist"])]
  protected $merits;


  #[ORM\Column(type: "smallint")]
  protected $xpTotal = 0;


  #[ORM\Column(type: "smallint")]
  protected $xpUsed = 0;

  protected $limit = 5;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "characters")]
  private $player;

  #[ORM\ManyToOne(targetEntity: Chronicle::class, inversedBy: "characters")]
  private $chronicle;


  #[ORM\Column(type: "boolean")]
  private $isNpc;


  #[ORM\Column(type: "string", length: 50, nullable: true)]
  private $virtueDetail;


  #[ORM\Column(type: "string", length: 50, nullable: true)]
  private $viceDetail;


  #[ORM\Column(type: "text")]
  private $background = "";

  #[ORM\Column]
  private array $experienceLogs = [];

  #[ORM\OneToMany(mappedBy: 'character', targetEntity: CharacterNote::class, orphanRemoval: true)]
  #[ORM\OrderBy(["assignedAt" => "DESC", "id" => "DESC"])]
  private Collection $notes;

  public function __construct()
  {
    if (!$this->attributes) {
      $this->setAttributes(new CharacterAttributes());
    }
    $this->specialties = new ArrayCollection();
    $this->merits = new ArrayCollection();
    $this->notes = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getType(): string
  {
    return lcfirst(substr(get_class($this), strrpos(get_class($this), '\\') + 1));
  }

  public function getLimit(): int
  {
    return $this->limit;
  }

  public function isMortal(): bool
  {
    $race = get_class($this);
    if ($race == Vampire::class) {
      return false;
    }

    return true;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getAge(): ?int
  {
    return $this->age;
  }

  public function setAge(int $age): self
  {
    $this->age = $age;

    return $this;
  }

  public function getPlayer(): ?User
  {
    return $this->player;
  }

  public function setPlayer(?User $player): self
  {
    $this->player = $player;

    return $this;
  }

  public function getConcept(): ?string
  {
    return $this->concept;
  }

  public function setConcept(string $concept): self
  {
    $this->concept = $concept;

    return $this;
  }

  public function getFaction(): ?string
  {
    return $this->faction;
  }

  public function setFaction(?string $faction): self
  {
    $this->faction = $faction;

    return $this;
  }

  public function getGroupName(): ?string
  {
    return $this->groupName;
  }

  public function setGroupName(?string $groupName): self
  {
    $this->groupName = $groupName;

    return $this;
  }

  public function addAttribute(string $attribute, int $value)
  {
    $this->attributes->set($attribute, $this->attributes->get($attribute) + $value);

    return $this;
  }

  public function getSpecialties(): Collection
  {
    return $this->specialties;
  }

  public function getSkillSpecialties($filter): array
  {
    $result = [];
    foreach ($this->specialties as $specialty) {
      /** @var CharacterSpecialty $specialty */
      if ($filter == $specialty->getSkill()->getIdentifier()) {
        $result[] = $specialty;
      }
    }

    return $result;
  }

  public function addSpecialty(CharacterSpecialty $specialty): self
  {
    if (!$this->specialties->contains($specialty)) {
      $this->specialties[] = $specialty;
    }

    return $this;
  }

  public function removeSpecialty(CharacterSpecialty $specialty): self
  {
    if ($this->specialties->removeElement($specialty)) {
    }

    return $this;
  }

  public function getWillpower(): ?int
  {
    return $this->willpower;
  }

  public function setWillpower(int $willpower): self
  {
    $change = $willpower - $this->willpower;
    $this->willpower = $willpower;
    // Max value of current Will
    if ($change < 0) {
      $this->currentWillpower = min($willpower, $this->currentWillpower);
    } else {
      $this->currentWillpower = min($willpower, $this->currentWillpower + $change);
    }

    return $this;
  }

  public function getVirtue(): ?Virtue
  {
    return $this->virtue;
  }

  public function setVirtue(?Virtue $virtue): self
  {
    $this->virtue = $virtue;

    return $this;
  }

  public function getVice(): ?Vice
  {
    return $this->vice;
  }

  public function setVice(?Vice $vice): self
  {
    $this->vice = $vice;

    return $this;
  }

  public function getMoral(): ?int
  {
    return $this->moral;
  }

  public function setMoral(int $moral): self
  {
    $this->moral = $moral;

    return $this;
  }

  public function getAttributes(): ?CharacterAttributes
  {
    return $this->attributes;
  }

  public function setAttributes(?CharacterAttributes $attributes): self
  {
    $this->attributes = $attributes;
    $attributes->setCharacter($this);

    return $this;
  }

  public function getSkills(): ?CharacterSkills
  {
    return $this->skills;
  }

  public function setSkills(?CharacterSkills $skills): self
  {
    $this->skills = $skills;
    $skills->setCharacter($this);

    return $this;
  }

  public function getWounds(): array
  {
    if (!$this->wounds) {
      $this->wounds = ['B' => 0, 'L' => 0, 'A' => 0];
    }

    return $this->wounds;
  }

  public function setWounds(array $wounds): self
  {
    $this->wounds = $wounds;

    return $this;
  }

  public function getSize(): ?int
  {
    return $this->size;
  }

  public function setSize(int $size): self
  {
    $this->size = $size;

    return $this;
  }

  public function getHealth(): ?int
  {
    $base = $this->size;

    // if ($this->getType() == "vampire") {
    //   /**@var Vampire $this */
    //   $resilience = $this->getDiscipline(DisciplineReferences::RESILIENCE);
    //   if (!is_null($resilience)) {
    //     $base = $base + $resilience->getLevel();
    //   }
    // }

    return $base + $this->attributes->getStamina();
  }

  public function getWoundMalus(): ?int
  {
    $threesold = 3;

    $merit = $this->hasMerit(MeritReferences::IRON_STAMINA);
    if ($merit) {
      $threesold -= $merit->getLevel();
    }

    return $threesold;
  }

  public function getCurrentWillpower(): ?int
  {
    return $this->currentWillpower;
  }

  public function setCurrentWillpower(int $currentWillpower): self
  {
    $this->currentWillpower = $currentWillpower;

    return $this;
  }

  public function getSpeed(): ?int
  {
    $bonus = 0;

    $merit = $this->hasMerit(MeritReferences::FLEET_OF_FOOT);
    if ($merit) {
      $bonus = $merit->getLevel();
    }

    return $this->attributes->getStrength() + $this->attributes->getDexterity() + 5 + $bonus;
  }

  public function getSpeedDetails(): array
  {
    $bonus = 0;

    $merit = $this->hasMerit(MeritReferences::FLEET_OF_FOOT);
    if ($merit) {
      $bonus = $merit->getLevel();
    }

    $details = [
      'base' => 5,
      'strength' => $this->attributes->getStrength(),
      'dexterity' => $this->attributes->getDexterity(),
      'bonus' => $bonus,
    ];

    return [
      'total' => array_sum($details),
      'details' => $details,
    ];
  }

  public function getInitiative(): int
  {
    $bonus = 0;

    $merit = $this->hasMerit(MeritReferences::FAST_REFLEXES);
    if ($merit) {
      $bonus = $merit->getLevel();
    }

    return $this->attributes->getDexterity() + $this->attributes->getComposure() + $bonus;
  }

  public function getInitiativeDetails(): array
  {
    $bonus = 0;

    $merit = $this->hasMerit(MeritReferences::FAST_REFLEXES);
    if ($merit) {
      $bonus = $merit->getLevel();
    }

    $details = [
      'dexterity' => $this->attributes->getDexterity(),
      'composure' => $this->attributes->getComposure(),
      'bonus' => $bonus,
    ];

    return [
      'total' => array_sum($details),
      'details' => $details,
    ];
  }

  public function getDefense(): int
  {

    return min($this->attributes->getDexterity(), $this->attributes->getWits());
  }

  public function getDefenseDetails(): array
  {
    $bonus = 0;

    $details = [
      'dexterity' => $this->attributes->getDexterity(),
      'wits' => $this->attributes->getWits(),
      'bonus' => $bonus,
    ];

    return [
      'total' => min($this->attributes->getDexterity(), $this->attributes->getWits()) + $bonus,
      'details' => $details,
    ];
  }

  /**
   * @return Collection|CharacterMerit[]
   */
  public function getMerits(): Collection
  {
    return $this->merits;
  }

  public function addMerit(CharacterMerit $merit): self
  {
    if (!$this->merits->contains($merit)) {
      $this->merits[] = $merit;
      $merit->setCharacter($this);
    }

    return $this;
  }

  public function removeMerit(CharacterMerit $merit): self
  {
    if ($this->merits->removeElement($merit)) {
      // set the owning side to null (unless already changed)
      if ($merit->getCharacter() === $this) {
        $merit->setCharacter(null);
      }
    }

    return $this;
  }

  public function hasMerit(int $id): ?CharacterMerit
  {
    foreach ($this->merits as $merit) {
      /** @var CharacterMerit $merit */
      if ($merit->getMerit()->getId() == $id) {

        return $merit;
      }
    }

    return null;
  }

  public function getXpTotal(): ?int
  {
    return $this->xpTotal;
  }

  public function setXpTotal(int $xpTotal): self
  {
    $this->xpTotal = $xpTotal;

    return $this;
  }

  public function getXpUsed(): ?int
  {
    return $this->xpUsed;
  }

  public function getXpAvailable(): ?int
  {
    return $this->xpTotal - $this->xpUsed;
  }

  public function setXpUsed(int $xpUsed): self
  {
    $this->xpUsed = $xpUsed;

    return $this;
  }

  public function spendXp(int $spent): self
  {
    $this->xpUsed += $spent;

    return $this;
  }

  public function dicePool(Collection $attributes, Collection $skills, int $bonus = 0)
  {
    $total = 0;
    foreach ($attributes as $attribute) {
      $total += $this->attributes->get($attribute->getIdentifier());
    }
    foreach ($skills as $skill) {
      $skillDice = $this->skills->get($skill->getIdentifier());
      if ($skillDice == 0) {
        if ($skill->getCategory() == "mental") {
          $total -= 3;
        } else {
          $total -= 1;
        }
      } else {
        $total += $skillDice;
      }
    }

    return $total + $bonus;
  }

  public function detailedDicePool(Collection $attributes, Collection $skills, array $modifiers = [])
  {
    $details = [
      'total' => 0,
      'string' => '',
      'modifiers' => $modifiers,
    ];

    foreach ($attributes as $attribute) {
      /** @var Attribute $attribute */
      $identifier = $attribute->getIdentifier();
      $value = $this->attributes->get($identifier);
      
      $details[$identifier] = $value;
      $details['total'] += $value;
      $details['string'] .= " {$attribute->getName()} {$value}";
    }
    foreach ($skills as $skill) {
      /** @var Skill $skill */
      $identifier = $skill->getIdentifier();
      $value = $this->skills->get($identifier);
      if ($value == 0) {
        if ($skill->getCategory() == "mental") {
          $value = -3;
        } else {
          $value = -1;
        }
      }
      
      $details[$identifier] = $value;
      $details['total'] += $value;
      $details['string'] .= " {$skill->getName()} {$value}";
    }
    foreach ($modifiers as $key => $value) {
      $details['total'] += $value;
      $details['string'] .= " {$key} {$value}";
    }

    return $details;
  }

  public function getChronicle(): ?Chronicle
  {
    return $this->chronicle;
  }

  public function setChronicle(?Chronicle $chronicle): self
  {
    $this->chronicle = $chronicle;

    return $this;
  }

  public function isNpc(): ?bool
  {
    return $this->isNpc;
  }

  public function setIsNpc(bool $isNpc): self
  {
    $this->isNpc = $isNpc;

    return $this;
  }

  public function getVirtueDetail(): ?string
  {
    return $this->virtueDetail;
  }

  public function setVirtueDetail(string $virtueDetail): self
  {
    $this->virtueDetail = $virtueDetail;

    return $this;
  }

  public function getViceDetail(): ?string
  {
    return $this->viceDetail;
  }

  public function setViceDetail(string $viceDetail): self
  {
    $this->viceDetail = $viceDetail;

    return $this;
  }

  public function getBackground(): ?string
  {
    return $this->background;
  }

  public function setBackground(string $background): self
  {
    $converter = new HtmlConverter();
    $this->background = $converter->convert($background);

    return $this;
  }

  public function getExperienceLogs(): array
  {
    return $this->experienceLogs;
  }

  public function setExperienceLogs(array $experienceLogs): self
  {
    $this->experienceLogs = $experienceLogs;

    return $this;
  }

  /**
   * @return Collection<int, CharacterNote>
   */
  public function getNotes(): Collection
  {
    return $this->notes;
  }

  public function addNote(CharacterNote $note): self
  {
    if (!$this->notes->contains($note)) {
      $this->notes->add($note);
      $note->setCharacter($this);
    }

    return $this;
  }

  public function removeNote(CharacterNote $note): self
  {
    if ($this->notes->removeElement($note)) {
      // set the owning side to null (unless already changed)
      if ($note->getCharacter() === $this) {
        $note->setCharacter(null);
      }
    }

    return $this;
  }
}
