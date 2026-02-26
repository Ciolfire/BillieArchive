<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Item\Armor;
use App\Entity\Traits\Sourcable;
use App\Entity\References\MeritReferences;
use App\Entity\Traits\Ancient;
use App\Form\CharacterForm;
use App\Repository\CharacterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

enum Gender: int
{
  case female = 0;
  case male = 1;
  case other = 2;
}

#[ORM\Table(name: "characters")]
#[ORM\Entity(repositoryClass: CharacterRepository::class)]
// MESSING UP WITH EMBRACE !!!
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: Types::STRING)]
#[ORM\DiscriminatorMap([
  "human" => Human::class,
  "vampire" => Vampire::class,
  "mage" => Mage::class,
  "werewolf" => Werewolf::class
])]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "characters")])]
class Character
{
  use Sourcable;
  use Ancient;

  protected $weightPower = [
    // 0 => 0,
    // 1 => 1,
    // 2 => 4,
    // 3 => 8,
    // 4 => 13,
    // 5 => 19,
    // 6 => 26,
    // 7 => 34,
    // 8 => 43,
    // 9 => 53,
    // 10 => 64,
    0 => 0,
    1 => 1,
    2 => 3,
    3 => 6,
    4 => 10,
    5 => 15,
    6 => 21,
    7 => 28,
    8 => 36,
    9 => 45,
    10 => 55,
  ];

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  protected ?int $id = null;

  #[ORM\Column(length: 30)]
  #[Assert\NotBlank]
  protected ?string $firstName = "";

  #[ORM\Column(length: 60)]
  protected ?string $nickname = "";

  #[ORM\Column(length: 30)]
  protected ?string $lastName = "";

  #[ORM\Column(length: 30)]
  protected ?string $title = "";

  #[ORM\Column(type: Types::INTEGER, enumType: Gender::class)]
  protected Gender $gender = Gender::female;

  #[ORM\Column(length: 30)]
  protected ?string $status = "alive";

  #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ["unsigned" => true])]
  protected ?int $age;

  #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ["unsigned" => true])]
  protected ?int $lookAge = null;

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $birthday = null;

  #[ORM\Column(type: Types::SMALLINT)]
  protected int $moral = 7;

  #[ORM\Column(type: Types::SMALLINT)]
  protected int $size = 5;

  #[ORM\Column(type: Types::SMALLINT)]
  protected int $willpower = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  protected int $currentWillpower = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  protected int $xpTotal = 0;

  #[ORM\Column(type: Types::SMALLINT)]
  protected int $xpUsed = 0;

  #[ORM\Column(type: Types::INTEGER)]
  protected int $powerRating = 0;

  #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
  protected ?string $concept;

  #[ORM\Column(type: Types::STRING, length: 25, nullable: true)]
  protected ?string $faction;

  #[ORM\Column(type: Types::STRING, length: 25, nullable: true)]
  protected ?string $groupName;

  #[ORM\Column(type: Types::BOOLEAN)]
  protected bool $isNpc;

  #[ORM\Column(type: Types::TEXT)]
  protected string $background = "";

  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = "";

  #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
  protected ?string $avatar;

  /** @var array<string, int> */
  #[ORM\Column(type: Types::JSON, nullable: true)]
  protected array $wounds = ['B' => 0, 'L' => 0, 'A' => 0];

  /** @var array<string, mixed> */
  #[ORM\Column(type: Types::JSON)]
  protected array $experienceLogs = [];

  #[ORM\OneToOne(targetEntity: CharacterAttributes::class, inversedBy: "character", orphanRemoval: true, cascade: ["persist"])]
  protected CharacterAttributes $attributes;

  #[ORM\OneToOne(targetEntity: CharacterSkills::class, inversedBy: "character", orphanRemoval: true, cascade: ["persist"])]
  protected CharacterSkills $skills;

  #[ORM\ManyToOne(targetEntity: Virtue::class)]
  protected ?Virtue $virtue;
  #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
  protected ?string $virtueDetail;

  #[ORM\ManyToOne(targetEntity: Vice::class)]
  protected ?Vice $vice;
  #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
  protected ?string $viceDetail;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "characters")]
  protected ?User $player = null;

  #[ORM\ManyToOne(targetEntity: Chronicle::class, inversedBy: "characters")]
  protected ?Chronicle $chronicle = null;

  #[ORM\ManyToOne]
  private ?Organization $organization = null;

  #[ORM\Column]
  protected ?bool $isPremade = false;

  #[ORM\OneToMany(targetEntity: CharacterMerit::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["level" => "DESC", "merit" => "ASC"])]
  protected Collection $merits;

  #[ORM\OneToMany(targetEntity: CharacterSpecialty::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  protected Collection $specialties;

  #[ORM\OneToMany(targetEntity: CharacterNote::class, mappedBy: 'character', orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
  #[ORM\OrderBy(["assignedAt" => "DESC", "id" => "DESC"])]
  protected Collection $notes;

  #[ORM\ManyToMany(targetEntity: Society::class, mappedBy: 'characters')]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
  private Collection $societies;

  #[ORM\OneToMany(targetEntity: CharacterDerangement::class, mappedBy: 'character', orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $derangements;

  #[ORM\OneToMany(targetEntity: CharacterLesserTemplate::class, mappedBy: 'sourceCharacter', orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $lesserTemplates;

  #[ORM\OneToMany(targetEntity: CharacterInfo::class, mappedBy: 'character', orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
  private Collection $infos;

  #[ORM\ManyToMany(targetEntity: CharacterInfo::class, mappedBy: 'accessList', cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
  private Collection $infoAccesses;

  #[ORM\OneToMany(targetEntity: CharacterAccess::class, mappedBy: 'target', orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $characterAccesses;

  #[ORM\OneToMany(targetEntity: CharacterAccess::class, mappedBy: 'accessor', orphanRemoval: true, cascade: ["persist"])]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $peekingRights;

  #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'owner')]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
  #[ORM\OrderBy(["isContainer" => "DESC", "name" => "ASC"])]
  private Collection $items;

  protected int $limit = 5;

  protected string $type;

  /**
   * @var Collection<int, StatusEffect>
   */
  #[ORM\OneToMany(targetEntity: StatusEffect::class, mappedBy: 'owner')]
  private Collection $statusEffects;

  public function __construct($isAncient = false, $isNpc = false, $chronicle = null)
  {
    $this->isAncient = $isAncient;
    $this->chronicle = $chronicle;
    $this->isNpc = $isNpc;
    $this->setAttributes(new CharacterAttributes());
    $this->setSkills(new CharacterSkills());
    $this->specialties = new ArrayCollection();
    $this->merits = new ArrayCollection();
    $this->notes = new ArrayCollection();

    $this->type = lcfirst(substr(get_class($this), strrpos(get_class($this), '\\') + 1));
    $this->societies = new ArrayCollection();
    $this->derangements = new ArrayCollection();
    $this->lesserTemplates = new ArrayCollection();
    $this->infos = new ArrayCollection();
    $this->infoAccesses = new ArrayCollection();
    $this->characterAccesses = new ArrayCollection();
    $this->peekingRights = new ArrayCollection();
    $this->items = new ArrayCollection();
    $this->statusEffects = new ArrayCollection();
  }

  public function __clone()
  {
    if ($this->id) {
      $this->id = null;
      $this->attributes = clone $this->attributes;
      $this->skills = clone $this->skills;
      // Collections/Arrays
      $this->specialties = $this->cloneCollection($this->specialties);
      $this->merits = $this->cloneCollection($this->merits);
      $this->derangements = $this->cloneCollection($this->derangements);
      // TO-DO Need more work on this
      $this->lesserTemplates = $this->cloneCollection($this->lesserTemplates, 'template');
    }
  }

  // cloning a relation which is a OneToMany
  protected function cloneCollection($collection, $method = 'default')
  {
    $collectionClone = new ArrayCollection();
    foreach ($collection as $item) {
      $itemClone = clone $item;
      switch ($method) {
        case 'template':
          $itemClone->setSourceCharacter($this);
          break;
        default:
          $itemClone->setCharacter($this);
          break;
      }
      $collectionClone->add($itemClone);
    }
    return $collectionClone;
  }

  public function __toString()
  {
    return $this->getName();
  }

  /**
   * @return array<string>
   */
  public function getProperties(): array
  {
    return get_object_vars($this);
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): self
  {
    $this->id = $id;
    return $this;
  }

  public function getPowerRating(): int
  {
    return $this->powerRating;
  }

  public function setPowerRating(): self
  {
    $sum = 0;

    foreach ($this->attributes->list as $attribute) {
      $sum += $this->weightPower[$this->attributes->get($attribute)] * 5;
    }

    foreach ($this->skills->list as $skill) {
      $sum += $this->weightPower[$this->skills->get($skill)] * 3;
    }

    foreach ($this->merits as $merit) {
      /** @var CharacterMerit $merit */
      if (!is_null($merit->getMerit()->getCategory())) {
        $sum += $this->weightPower[$merit->getLevel()] * 2;
      }
    }

    if ($this->getLesserTemplate()) {
      $sum += $this->getLesserTemplate()->getPowerRating($this->weightPower);
    }
    // foreach ($this->lesserTemplates as $lesserTemplate) {
    //   $sum += $lesserTemplate->getPowerRating($this->weightPower);
    // }

    $sum += count($this->getSpecialties()) * 3;

    $this->powerRating = $sum;
    return $this;
  }

  public function getType(): string
  {
    if ($this->getLesserTemplate()) {
      return $this->getLesserTemplate()->getType();
    }

    return "human";
  }

  public function getForm(): string
  {
    return CharacterForm::class;
  }

  public function getSetting(): string
  {
    if ($this->getLesserTemplate()) {

      return $this->getLesserTemplate()->getSetting();
    }

    return $this->getType();
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

  public function getName(): string
  {
    if (!empty($this->nickname)) {

      return "{$this->title} {$this->firstName} “{$this->nickname}” {$this->lastName}";
    }

    return trim("{$this->title} {$this->firstName} {$this->lastName}");
  }

  public function getSimpleName(): string
  {
    if (!empty($this->nickname)) {

      return "{$this->firstName} “{$this->nickname}” {$this->lastName}";
    }

    return trim("{$this->firstName} {$this->lastName}");
  }

  public function setName(string $firstName, ?string $lastName, ?string $nickname): self
  {
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->nickname = $nickname;

    return $this;
  }

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;

    return $this;
  }

  public function getNickname(): ?string
  {
    return $this->nickname;
  }

  public function setNickname(string $nickname): self
  {
    $this->nickname = $nickname;

    return $this;
  }

  /** The name known for this character by another character */
  public function getPublicName(?Character $peeker = null): string
  {
    $name = "";
    if (is_null($peeker)) {
      return $name;
    }
    $rights = $peeker->getSpecificPeekingRights($this)->getRights();

    if (in_array('title', $rights)) {
      $name .= "{$this->title} ";
    }

    if (in_array('firstname', $rights)) {
      $name .= "{$this->firstName} ";
    }
    if (in_array('nickname', $rights) && $this->nickname) {
      $name .= "“{$this->nickname}” ";
    }
    if (in_array('lastname', $rights)) {
      $name .= "{$this->lastName}";
    }

    // If the first name is not known, we remove the quotation marks as the nickname is the "official name"
    if (str_starts_with($name, "“")) {

      $name = str_replace(["“", "”"], "", $name);
    }

    return trim($name);
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getStatus(): ?string
  {
    return $this->status;
  }

  public function setStatus(string $status): self
  {
    $this->status = $status;

    return $this;
  }

  public function getAvatar(): ?string
  {
    if ($this->avatar) {

      return $this->avatar;
    }

    return "default.jpg";
  }

  public function setAvatar(?string $avatar): self
  {
    $this->avatar = $avatar;

    return $this;
  }

  public function updateAvatar(): self
  {
    preg_replace("(\?.+)", "", $this->avatar);
    $this->avatar .= "?timestamp=" . time();

    return $this;
  }

  public function getLookAge(): ?int
  {
    return $this->lookAge;
  }

  public function setLookAge(?int $lookAge): self
  {
    $this->lookAge = $lookAge;

    return $this;
  }

  public function getAge(): ?int
  {
    if ($this->chronicle instanceof Chronicle && $this->chronicle->getCurrentlyAt() && $this->birthday) {
      $this->age = $this->birthday->diff($this->chronicle->getCurrentlyAt())->y;
    }
    return $this->age;
  }

  public function setAge(?int $age): self
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

  public function addAttribute(string $attribute, int $value): self
  {
    $this->attributes->set($attribute, $this->attributes->get($attribute) + $value);

    return $this;
  }

  public function getSpecialties(): Collection
  {
    return $this->specialties;
  }

  /**
   * @return array<CharacterSpecialty>
   */
  public function getSkillSpecialties(string $filter): array
  {
    $result = [];
    foreach ($this->specialties as $specialty) {
      /** @var Skill $skill */
      $skill = $specialty->getSkill();
      /** @var CharacterSpecialty $specialty */
      if ($filter == $skill->getIdentifier()) {
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
      $specialty->setCharacter(null);
    }

    return $this;
  }

  public function getWillpower(bool $includeModifiers = true): int
  {
    if ($includeModifiers) {
      foreach ($this->getStatusEffects() as $effect) {
        if ($effect->getType() == 'willpower') {
          return max(0, $this->willpower + $effect->getValue());
        }
      }
    }

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

  public function getAttributes(): CharacterAttributes
  {
    return $this->attributes;
  }

  /**
   * @return array<string>
   */
  public function getPositiveAttributes(): array
  {
    return $this->attributes->getAll(false);
  }

  /**
   * @return array<string>
   */
  public function getLearnedSkills(): array
  {
    return $this->skills->getAll(false);
  }

  public function setAttributes(CharacterAttributes $attributes): self
  {
    $this->attributes = $attributes;
    $attributes->setCharacter($this);

    return $this;
  }

  public function getSkills(): CharacterSkills
  {
    return $this->skills;
  }

  public function setSkills(CharacterSkills $skills): self
  {
    $this->skills = $skills;
    $skills->setCharacter($this);

    return $this;
  }

  /**
   * @return array<string, int>
   */
  public function getWounds(): array
  {
    if (!$this->wounds) {
      $this->wounds = ['B' => 0, 'L' => 0, 'A' => 0];
    }

    return $this->wounds;
  }

  /**
   * @param array<string, int> $wounds
   */
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

    $bonus = 0;
    foreach ($this->getStatusEffects() as $effect) {
      if ($effect->getType() == 'health') {
        $bonus += $effect->getRealValue();
      }
    }

    return $base + $bonus + $this->attributes->get('stamina');
  }

  public function getMaxHealth(): ?int
  {
    $base = $this->size + $this->getLimit();

    return $base;
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

  public function getCurrentWillpower(): int
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
    foreach ($this->getStatusEffects() as $effect) {
      if ($effect->getType() == 'speed') {
        $bonus += $effect->getRealValue();
      }
    }

    $merit = $this->hasMerit(MeritReferences::FLEET_OF_FOOT);
    if ($merit) {
      $bonus += $merit->getLevel();
    }

    return $this->attributes->getStrength() + $this->attributes->getDexterity() + 5 + $bonus;
  }

  /**
   * @return array<string, mixed>
   */
  public function getSpeedDetails(): array
  {
    $bonus = 0;
    foreach ($this->getStatusEffects() as $effect) {
      if ($effect->getType() == 'speed') {
        $bonus += $effect->getRealValue();
      }
    }

    $merit = $this->hasMerit(MeritReferences::FLEET_OF_FOOT);
    if (!is_null($merit)) {
      $bonus += $merit->getLevel();
    }

    $equipment = 0;
    foreach ($this->getItems() as $item) {
      if ($item instanceof Armor) {
        $equipment += $item->getSpeed();
      }
    }

    $details = [
      'base' => 5,
      'strength' => $this->attributes->getStrength(),
      'dexterity' => $this->attributes->getDexterity(),
      'bonus' => $bonus,
      'equipment' => $equipment,
    ];

    return [
      'total' => (int)array_sum($details),
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

  /**
   * @return array<string, mixed>
   */
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
      'total' => (int)array_sum($details),
      'details' => $details,
    ];
  }

  public function getDefense(): int
  {
    $bonus = 0;
    foreach ($this->getStatusEffects() as $effect) {
      if ($effect->getType() == 'defense') {
        $bonus += $effect->getRealValue();
      }
    }
    $equipment = 0;
    foreach ($this->getItems() as $item) {
      if ($item instanceof Armor) {
        $equipment += $item->getDefense();
      }
    }

    return min($this->attributes->getDexterity(), $this->attributes->getWits()) + $bonus + $equipment;
  }

  /**
   * @return array<string, mixed>
   */
  public function getDefenseDetails(): array
  {
    $bonus = 0;
    foreach ($this->getStatusEffects() as $effect) {
      if ($effect->getType() == 'defense') {
        $bonus += $effect->getRealValue();
      }
    }

    $equipment = 0;
    foreach ($this->getItems() as $item) {
      if ($item instanceof Armor) {
        $equipment += $item->getDefense();
      }
    }

    $details = [
      'dexterity' => $this->attributes->getDexterity(),
      'wits' => $this->attributes->getWits(),
      'bonus' => $bonus,
      'equipment' => $equipment,
    ];

    return [
      'total' => (int)min($this->attributes->getDexterity(), $this->attributes->getWits()) + $bonus + $equipment,
      'details' => $details,
    ];
  }

  public function getArmor(): array
  {
    $bonus = [0, 0];
    foreach ($this->getStatusEffects() as $effect) {
      if ($effect->getType() == 'armor') {
        $bonus += $effect->getRealValue();
      }
    }

    return $bonus;
  }

  /**
   * @return array<string, mixed>
   */
  public function getArmorDetails(): array
  {
    $items = 0;
    $melee = 0;
    $ranged = 0;
    $bonus = 0;

    foreach ($this->getStatusEffects() as $effect) {
      if ($effect->getType() == 'armor') {
        if ($effect->getItem()) {
          if ($effect->getItem()->isEquipped()) {
            $items += $effect->getRealValue();
          }
        } else {
          $bonus += $effect->getRealValue();
        }
      }
    }
    foreach ($this->getItems() as $item) {
      if ($item instanceof Armor && $item->isEquipped()) {
        $melee += $item->getRatingMelee();
        $ranged += $item->getRatingRanged();
      }
    }

    $details = [
      'item' => $items,
      'melee' => $melee,
      'ranged' => $ranged,
      'bonus' => $bonus,
    ];

    return [
      'total' => [
        'melee' => $melee + $items + $bonus,
        'ranged' => $ranged + $items + $bonus,
      ],
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

  public function getFilteredMerits($filter): array
  {
    $merits = [];

    foreach ($this->merits as $merit) {
      if ($merit instanceof CharacterMerit && $merit->getMerit()->getCategory() == $filter) {
        $key = $merit->getMerit()->getName() . (10 - $merit->getLevel()) . $merit->getId();
        $merits[$key] = $merit;
      }
    }
    ksort($merits);

    return $merits;
  }

  public function getRelationMerits(): array
  {
    $merits = [];

    foreach ($this->merits as $merit) {
      if ($merit instanceof CharacterMerit && $merit->getMerit()->isRelation()) {
        $key = (10 - $merit->getLevel()) . $merit->getChoice() . $merit->getMerit()->getId();
        $merits[$key] = $merit;
      }
    }
    ksort($merits);

    return $merits;
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

  public function hasMerit(?int $id): ?CharacterMerit
  {
    if (!is_null($id)) {

      /** @var CharacterMerit $charMerit */
      foreach ($this->merits as $charMerit) {
        /** @var Merit $merit */
        $merit = $charMerit->getMerit();
        if ($merit->getId() == $id) {

          return $charMerit;
        }
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

  public function refundXp(int $refunded): self
  {
    $this->xpUsed -= $refunded;

    return $this;
  }

  public function addXp(int $value): self
  {
    $this->xpTotal += $value;

    return $this;
  }

  public function removeXp(int $value): self
  {
    $this->xpTotal -= $value;

    return $this;
  }

  public function refundElement(int $value, int $from, int $to): self
  {
    while ($from > $to) {
      $this->refundXp($value * $from);
      $from -= 1;
    }

    return $this;
  }

  /**
   * @return int
   */
  public function dicePool(Collection $attributes, Collection $skills, int $bonus = 0): int
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

  /**
   * @param array<string, int> $modifiers
   * @return array<string, mixed>
   */
  public function detailedDicePool(Collection $attributes, Collection $skills, ?Collection $specials = null, array $modifiers = []): array
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
    if (!is_null($specials)) {
      $value = 0;
      foreach ($specials as $special) {
        if ($this instanceof Vampire) {
          /** @var Vampire $this */
          $discipline = $this->getDiscipline($special->getId());
          if ($discipline instanceof VampireDiscipline) {
            $value = $discipline->getLevel();
          } else {
            $value = 0;
          }
        }
        $details[(string)$special->getId()] = $value;
        $details['total'] += $value;
        $details['string'] .= " {$special->getName()} {$value}";
      }
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

  public function getBackground(): string
  {
    return $this->background;
  }

  public function setBackground(string $background = ""): self
  {
    if ($this->background == "") {
      $this->background = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $background);
    } else {
      $this->background = $background;
    }

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  /**
   * @return array<string, mixed>
   */
  public function getExperienceLogs(): array
  {
    return $this->experienceLogs;
  }

  /**
   * @param array<string, mixed> $experienceLogs
   */
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

  public function isPremade(): ?bool
  {
    return $this->isPremade;
  }

  public function setIsPremade(bool $isPremade): self
  {
    $this->isPremade = $isPremade;

    return $this;
  }

  /**
   * @return Collection<int, Society>
   */
  public function getSocieties(): Collection
  {
    return $this->societies;
  }

  public function addSociety(Society $society): self
  {
    if (!$this->societies->contains($society)) {
      $this->societies->add($society);
      $society->addCharacter($this);
    }

    return $this;
  }

  public function removeSociety(Society $society): self
  {
    if ($this->societies->removeElement($society)) {
      $society->removeCharacter($this);
    }

    return $this;
  }

  /**
   * @return Collection<int, CharacterDerangement>
   */
  public function getDerangements(): Collection
  {
    return $this->derangements;
  }

  public function addDerangement(CharacterDerangement $characterDerangement): static
  {
    if (!$this->derangements->contains($characterDerangement)) {
      $this->derangements->add($characterDerangement);
      $characterDerangement->setCharacter($this);
    }

    return $this;
  }

  public function removeDerangement(CharacterDerangement $characterDerangement): static
  {
    if ($this->derangements->removeElement($characterDerangement)) {
      // set the owning side to null (unless already changed)
      if ($characterDerangement->getCharacter() === $this) {
        $characterDerangement->setCharacter(null);
      }
    }

    return $this;
  }

  public function getMoralityDerangement(int $morality): ?CharacterDerangement
  {
    foreach ($this->getDerangements() as $derangement) {
      if ($morality === $derangement->getMoralityLink()) {

        return $derangement;
      }
    }

    return null;
  }

  public function getstandardDerangements(): array
  {
    $derangements = [];

    foreach ($this->getDerangements() as $derangement) {
      if (null === $derangement->getMoralityLink()) {
        $derangements[] = $derangement;
      }
    }

    return $derangements;
  }

  public function getMaxMorality(): int
  {
    return 10;
  }

  /** Get the current active lesser template */
  public function getLesserTemplate(): ?CharacterLesserTemplate
  {
    foreach ($this->lesserTemplates as $template) {
      /** @var CharacterLesserTemplate $template */
      if ($template->isActive()) {
        return $template;
      }
    }

    return null;
  }

  /**
   * @return Collection<int, CharacterLesserTemplate>
   */
  public function getLesserTemplates(): Collection
  {
    return $this->lesserTemplates;
  }

  public function addLesserTemplate(CharacterLesserTemplate $lesserTemplate): static
  {
    foreach ($this->lesserTemplates as $template) {
      /** @var CharacterLesserTemplate $template */
      if ($template->getType() === $lesserTemplate->getType()) {
        $template->setIsActive(true);

        return $this;
      }
    }

    $this->lesserTemplates->add($lesserTemplate);
    $lesserTemplate->setSourceCharacter($this);

    return $this;
  }

  public function findLesserTemplate(string $type): ?CharacterLesserTemplate
  {
    foreach ($this->lesserTemplates as $template) {
      /** @var CharacterLesserTemplate $template */
      if ($template->getType() === $type) {

        return $template;
      }
    }

    return null;
  }

  public function cleanLesserTemplates()
  {
    foreach ($this->lesserTemplates as $lesserTemplate) {
      if ($lesserTemplate instanceof CharacterLesserTemplate)
        $this->lesserTemplates->removeElement($lesserTemplate);
    }

    return $this;
  }

  public function removeLesserTemplate(CharacterLesserTemplate $template)
  {
    foreach ($this->lesserTemplates as $lesserTemplate) {
      if ($lesserTemplate === $template)
        $this->lesserTemplates->removeElement($lesserTemplate);
    }
  }

  public function isLesser()
  {
    if ($this->getLesserTemplate()) {
      return true;
    }

    return false;
  }

  /**
   * @return Collection<int, CharacterInfo>
   */
  public function getInfos(): Collection
  {
    return $this->infos;
  }

  public function addInfo(CharacterInfo $info): static
  {
    if (!$this->infos->contains($info)) {
      $this->infos->add($info);
      $info->setCharacter($this);
    }

    return $this;
  }

  public function removeInfo(CharacterInfo $info): static
  {
    $this->infos->removeElement($info);

    return $this;
  }

  /**
   * @return Collection<int, CharacterInfo>
   */
  public function getInfoAccesses(): Collection
  {
    return $this->infoAccesses;
  }

  public function addInfoAccess(CharacterInfo $info): static
  {
    if (!$this->infoAccesses->contains($info)) {
      $this->infoAccesses->add($info);
      $info->addAccessList($this);
    }

    return $this;
  }

  public function removeInfoAccess(CharacterInfo $info): static
  {
    if ($this->infoAccesses->removeElement($info)) {
      $info->removeAccessList($this);
    }

    return $this;
  }

  /**
   * @return Collection<int, CharacterAccess>
   */
  public function getCharacterAccesses(): Collection
  {
    return $this->characterAccesses;
  }

  public function addCharacterAccess(CharacterAccess $characterAccess): static
  {
    if (!$this->characterAccesses->contains($characterAccess)) {
      $this->characterAccesses->add($characterAccess);
      $characterAccess->setTarget($this);
    }

    return $this;
  }

  public function removeCharacterAccess(CharacterAccess $characterAccess): static
  {
    if ($this->characterAccesses->removeElement($characterAccess)) {
      // set the owning side to null (unless already changed)
      if ($characterAccess->getTarget() === $this) {
        $characterAccess->setTarget(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, CharacterAccess>
   */
  public function getPeekingRights(): Collection
  {
    return $this->peekingRights;
  }

  /**
   * @return Collection<int, CharacterAccess>
   */
  public function getOrderedPeekingRights(): array
  {
    $list = $this->peekingRights->toArray();

    usort($list, function (CharacterAccess $a, CharacterAccess $b) {
      $nameA = $a->getTarget()->getPublicName($this);
      $nameB = $b->getTarget()->getPublicName($this);

      return strcasecmp($nameA, $nameB);
    });

    return $list;
  }

  public function hasSpecificPeekingRights(Character $character): bool
  {
    if ($this->peekingRights->findFirst(function (int $key, CharacterAccess $access) use ($character): bool {
      if ($access->getTarget() === $character) {

        return true;
      }

      return false;
    })) {

      return true;
    }

    return false;
  }

  public function getSpecificPeekingRights(Character $character): CharacterAccess
  {
    $rights = $this->peekingRights->findFirst(function (int $key, CharacterAccess $access) use ($character): bool {
      if ($access->getTarget() === $character) {

        return true;
      }

      return false;
    });

    if (is_null($rights)) {
      return new CharacterAccess()->setAccessor($this)->setTarget($character);
    }

    return $rights;
  }

  public function addPeekingRight(CharacterAccess $peekingRight): static
  {
    if (!$this->peekingRights->contains($peekingRight)) {
      $this->peekingRights->add($peekingRight);
      $peekingRight->setAccessor($this);
    }

    return $this;
  }

  public function removePeekingRight(CharacterAccess $peekingRight): static
  {
    if ($this->peekingRights->removeElement($peekingRight)) {
      // set the owning side to null (unless already changed)
      if ($peekingRight->getAccessor() === $this) {
        $peekingRight->setAccessor(null);
      }
    }

    return $this;
  }

  public function getKnownCharacters(): ?array
  {
    if ($this->chronicle) {
      $characters = [];
      foreach ($this->peekingRights as $peekingRight) {
        $characters[] = $peekingRight->getTarget();
      }

      return $characters;
    }

    return null;
  }

  /**
   * @return Collection<int, Item>
   */
  public function getItems(): Collection
  {
    return $this->items;
  }

  public function getItemContainers(): array
  {
    $containers = [];
    foreach ($this->items as $item) {
      /** @var Item $item */
      if ($item->isContainer()) {
        $containers[] = $item;
      }
    }

    return $containers;
  }

  public function hasSharedItem(): bool
  {
    foreach ($this->items as $item) {
      if ($item->isShared()) {
        return true;
      }
    }

    return false;
  }

  public function addItem(Item $item): static
  {
    if (!$this->items->contains($item)) {
      $this->items->add($item);
      $item->setOwner($this);
    }

    return $this;
  }

  public function removeItem(Item $item): static
  {
    if ($this->items->removeElement($item)) {
      // set the owning side to null (unless already changed)
      if ($item->getOwner() === $this) {
        $item->setOwner(null);
      }
    }

    return $this;
  }

  public function getBirthday(): ?\DateTimeInterface
  {
    return $this->birthday;
  }

  public function setBirthday(?\DateTimeInterface $birthday): static
  {
    $this->birthday = $birthday;

    return $this;
  }

  public function getOrganization(): ?Organization
  {
    return $this->organization;
  }

  public function setOrganization(?Organization $organization): static
  {
    $this->organization = $organization;

    return $this;
  }

  public function getMainOrganization(): ?Organization
  {
    return $this->organization;
  }

  /**
   * @return Collection<int, StatusEffect>
   */
  public function getStatusEffects(): Collection
  {
    return $this->statusEffects;
  }

  public function addStatusEffect(StatusEffect $statusEffect): static
  {
    if (!$this->statusEffects->contains($statusEffect)) {
      $this->statusEffects->add($statusEffect);
      $statusEffect->setOwner($this);
    }

    return $this;
  }

  public function removeStatusEffect(StatusEffect $statusEffect): static
  {
    if ($this->statusEffects->removeElement($statusEffect)) {
      // set the owning side to null (unless already changed)
      if ($statusEffect->getOwner() === $this) {
        $statusEffect->setOwner(null);
      }
    }

    return $this;
  }

  public function hasStatus(?DisciplinePower $power): bool
  {
    foreach ($this->statusEffects as $effect) {
      if ($power && $effect->getDisciplinePower() === $power) {

        return true;
      }
    }

    return false;
  }
}
