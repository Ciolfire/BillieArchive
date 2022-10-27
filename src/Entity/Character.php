<?php

namespace App\Entity;

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

  #[ORM\Column(type: "string", length: 50, nullable: true)]
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

  
  #[ORM\Column(type: "text")]
  private $notes = "";

  public function __construct()
  {
    if (!$this->attributes) {
      $this->setAttributes(new CharacterAttributes());
    }
    $this->specialties = new ArrayCollection();
    $this->merits = new ArrayCollection();
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
    return $this->size + $this->attributes->getStamina();
  }

  public function getCurrentWillpower(): ?int
  {
    return $this->currentWillpower;
  }

  public function getSpeed(): ?int
  {
    return $this->attributes->getStrength() + $this->attributes->getDexterity() + 5;
  }

  public function setCurrentWillpower(int $currentWillpower): self
  {
    $this->currentWillpower = $currentWillpower;

    return $this;
  }

  public function getInitiative(): int
  {
    $bonus = 0;

    return $this->attributes->getDexterity() + $this->attributes->getComposure() + $bonus;
  }

  public function getDefense(): int
  {

    return min($this->attributes->getDexterity(), $this->attributes->getWits());
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

  public function hasMerit(int $id): bool
  {
    foreach ($this->merits as $merit) {
      /** @var CharacterMerit $merit */
      if ($merit->getMerit()->getId() == $id) {

        return true;
      }
    }

    return false;
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

  public function dicePool(Attribute $attribute, Skill $skill, int $bonus = 0)
  {
    $attributeDice = $this->attributes->get($attribute->getIdentifier());
    $skillDice = $this->skills->get($skill->getIdentifier());
    if ($skillDice == 0) {
      if ($skill->getCategory() == "mental") {
        $skillDice = -3;
      } else {
        $skillDice = -1;
      }
    }

    return $attributeDice + $skillDice + $bonus;
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

  public function getNotes(): ?string
  {
    return $this->notes;
  }

  public function setNotes(string $notes): self
  {
    $converter = new HtmlConverter();
    $this->notes = $converter->convert($notes);

    return $this;
  }
}
