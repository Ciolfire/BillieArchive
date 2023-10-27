<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\References\DisciplineReferences;
use App\Entity\References\MeritReferences;
use App\Repository\CharacterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\Table(name: "characters")]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: Types::STRING)]
// Probably not needed
#[ORM\DiscriminatorMap(["human" => Human::class, "vampire" => Vampire::class, "mage" => Mage::class, "werewolf" => Werewolf::class])]
class Character
{
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

  #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ["unsigned" => true])]
  protected ?int $age;

  #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ["unsigned" => true])]
  protected ?int $lookAge = null;

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

  /** @var array<string, int> */
  #[ORM\Column(type: Types::JSON, nullable: true)]
  protected array $wounds = ['B' => 0, 'L' => 0, 'A' => 0];

  /** @var array<string, mixed> */
  #[ORM\Column(type: Types::JSON)]
  protected array $experienceLogs = [];


  #[ORM\OneToOne(targetEntity: CharacterAttributes::class, inversedBy: "character", cascade: ["persist", "remove"], fetch: "EAGER")]
  protected CharacterAttributes $attributes;

  #[ORM\OneToOne(targetEntity: CharacterSkills::class, inversedBy: "character", cascade: ["persist", "remove"], fetch: "EAGER")]
  protected CharacterSkills $skills;

  #[ORM\OneToMany(targetEntity: CharacterSpecialty::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist", "remove"])]
  protected Collection $specialties;

  #[ORM\ManyToOne(targetEntity: Virtue::class)]
  protected ?Virtue $virtue;
  #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
  protected ?string $virtueDetail;

  #[ORM\ManyToOne(targetEntity: Vice::class)]
  protected ?Vice $vice;
  #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
  protected ?string $viceDetail;

  #[ORM\OneToMany(targetEntity: CharacterMerit::class, mappedBy: "character", orphanRemoval: true, cascade: ["persist", "remove"])]
  #[ORM\OrderBy(["level" => "DESC", "merit" => "ASC"])]
  protected Collection $merits;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "characters")]
  protected ?User $player = null;

  #[ORM\ManyToOne(targetEntity: Chronicle::class, inversedBy: "characters")]
  protected ?Chronicle $chronicle = null;

  #[ORM\OneToMany(mappedBy: 'character', targetEntity: CharacterNote::class, orphanRemoval: true)]
  #[ORM\OrderBy(["assignedAt" => "DESC", "id" => "DESC"])]
  protected Collection $notes;

  protected int $limit = 5;

  #[ORM\Column]
  protected ?bool $isPremade = false;

  protected string $type;

  #[ORM\ManyToMany(targetEntity: Society::class, mappedBy: 'characters')]
  private Collection $societies;

  #[ORM\OneToMany(mappedBy: 'character', targetEntity: CharacterDerangement::class, orphanRemoval: true)]
  private Collection $derangements;

  #[ORM\OneToMany(mappedBy: 'sourceCharacter', targetEntity: CharacterLesserTemplate::class)]
  private Collection $lesserTemplates;

  public function __construct()
  {
    $this->setAttributes(new CharacterAttributes());
    $this->setSkills(new CharacterSkills());
    $this->specialties = new ArrayCollection();
    $this->merits = new ArrayCollection();
    $this->notes = new ArrayCollection();

    $this->type = lcfirst(substr(get_class($this), strrpos(get_class($this), '\\') + 1));
    $this->societies = new ArrayCollection();
    $this->derangements = new ArrayCollection();
    $this->lesserTemplates = new ArrayCollection();
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

  public function getPowerRating(): ?int
  {
    $sum = $this->setPowerRating();

    return $sum;
  }

  public function setPowerRating(): ?int
  {
    $sum = 0;
    $weight = [
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

    foreach ($this->attributes->list as $attribute) {
      $sum += $weight[$this->attributes->get($attribute)] * 4;
    }

    foreach ($this->skills->list as $skill) {
      $sum += $weight[$this->skills->get($skill)] * 2;
    }

    foreach ($this->merits as $merit) {
      /** @var CharacterMerit $merit */
      $sum += $weight[$merit->getLevel()] * 1;
    }

    return $sum;
  }


  public function getType(): string
  {
    if ($this->getLesserTemplate()) {
      return $this->getLesserTemplate()->getType();
    }

    return "human";
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

      return "{$this->firstName} “{$this->nickname}” {$this->lastName}";
    } else {

      return "{$this->firstName} {$this->lastName}";
    }
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

  public function getWillpower(): int
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

    // if ($this->getType() == "vampire") {
    //   /**@var Vampire $this */
    //   $resilience = $this->getDiscipline(DisciplineReferences::RESILIENCE);
    //   if (!is_null($resilience)) {
    //     $base = $base + $resilience->getLevel();
    //   }
    // }

    return $base + $this->attributes->getStamina();
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

    $merit = $this->hasMerit(MeritReferences::FLEET_OF_FOOT);
    if ($merit) {
      $bonus = $merit->getLevel();
    }

    return $this->attributes->getStrength() + $this->attributes->getDexterity() + 5 + $bonus;
  }

  /**
   * @return array<string, mixed>
   */
  public function getSpeedDetails(): array
  {
    $bonus = 0;

    $merit = $this->hasMerit(MeritReferences::FLEET_OF_FOOT);
    if (!is_null($merit)) {
      $bonus = $merit->getLevel();
    }

    $details = [
      'base' => 5,
      'strength' => $this->attributes->getStrength(),
      'dexterity' => $this->attributes->getDexterity(),
      'bonus' => $bonus,
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

    return min($this->attributes->getDexterity(), $this->attributes->getWits());
  }

  /**
   * @return array<string, mixed>
   */
  public function getDefenseDetails(): array
  {
    $bonus = 0;

    $details = [
      'dexterity' => $this->attributes->getDexterity(),
      'wits' => $this->attributes->getWits(),
      'bonus' => $bonus,
    ];

    return [
      'total' => (int)min($this->attributes->getDexterity(), $this->attributes->getWits()) + $bonus,
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
        $key = $merit->getMerit()->getName().(10-$merit->getLevel().$merit->getId());
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

  /**
   * @return int
   */
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
    $converter = new HtmlConverter();
    $this->background = $converter->convert($background);

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

  public function findLesserTemplate(string $type) : ?CharacterLesserTemplate
  {
    foreach ($this->lesserTemplates as $template) {
      /** @var CharacterLesserTemplate $template */
      if ($template->getType() === $type) {

        return $template;
      }
    }

    return null;
  }

  public function cleanLesserTemplates() : static
  {
    foreach ($this->lesserTemplates as $lesserTemplate) {
      if ($lesserTemplate instanceof CharacterLesserTemplate)
      $lesserTemplate->setSourceCharacter(null);
    }

    return $this;
  }
}
