<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Ancient;
use App\Repository\ChronicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChronicleRepository::class)]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Chronicle
{
  use Ancient;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: Types::STRING, length: 255)]
  private string $name;

  #[ORM\OneToMany(targetEntity: Character::class, mappedBy: "chronicle")]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["firstName" => "ASC", "id" => "DESC"])]
  private Collection $characters;

  #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "chronicles")]
  #[ORM\OrderBy(["username" => "ASC", "id" => "DESC"])]
  private Collection $players;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "stories")]
  private ?User $storyteller;

  #[ORM\Column(type: "string", length: 50)]
  private string $type;

  #[ORM\OneToMany(targetEntity: Merit::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $merits;

  #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'homebrewFor')]
  private Collection $items;

  #[ORM\OneToMany(mappedBy: 'chronicle', targetEntity: Society::class)]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $societies;

  #[ORM\Column(type: Types::JSON,  nullable: true)]
  private ?array $rules = null;
  
  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $startAt = null;

  #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
  private ?\DateTimeInterface $currentlyAt = null;

  // Human
  #[ORM\OneToMany(targetEntity: Organization::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $organizations;

  // Vampire
  #[ORM\OneToMany(targetEntity: Clan::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $clans;

  #[ORM\OneToMany(targetEntity: Devotion::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $devotions;

  #[ORM\OneToMany(targetEntity: Discipline::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $disciplines;

  #[ORM\OneToMany(targetEntity: DisciplinePower::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["discipline" => "ASC", "level" => "ASC", "name" => "ASC", "id" => "DESC"])]
  private Collection $rituals;

  #[ORM\OneToMany(targetEntity: GhoulFamily::class, mappedBy: 'homebrewFor')]
  private Collection $ghoulFamilies;

  // Mage
  #[ORM\OneToMany(targetEntity: Path::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $paths;

  #[ORM\OneToMany(targetEntity: Legacy::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $legacies;

  #[ORM\OneToMany(targetEntity: MageSpell::class, mappedBy: 'homebrewFor')]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $spells;

  #[ORM\OneToMany(targetEntity: SpellRote::class, mappedBy: 'homebrewFor')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $spellRotes;

  // Werewolf
  #[ORM\OneToMany(targetEntity: Auspice::class, mappedBy: 'homebrewFor')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $auspices;

  #[ORM\OneToMany(targetEntity: Tribe::class, mappedBy: 'homebrewFor')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $tribes;

  #[ORM\OneToMany(targetEntity: GiftList::class, mappedBy: 'homebrewFor')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $giftLists;

  #[ORM\OneToMany(targetEntity: Gift::class, mappedBy: 'homebrewFor')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $gifts;

  #[ORM\OneToMany(targetEntity: Rite::class, mappedBy: 'homebrewFor')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $rites;

  public function __construct()
  {
    $this->characters = new ArrayCollection();
    $this->players = new ArrayCollection();
    $this->merits = new ArrayCollection();
    $this->items = new ArrayCollection();

    // Vampire
    $this->clans = new ArrayCollection();
    $this->devotions = new ArrayCollection();
    $this->societies = new ArrayCollection();
  }

  public function __toString(): string
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
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

  public function getCharacters(): Collection
  {
    return $this->characters;
  }

  public function addCharacter(Character $character): self
  {
    if (!$this->characters->contains($character)) {
      $this->characters[] = $character;
      $character->setChronicle($this);
    }

    return $this;
  }

  public function removeCharacter(Character $character): self
  {
    if ($this->characters->removeElement($character)) {
      // set the owning side to null (unless already changed)
      if ($character->getChronicle() === $this) {
        $character->setChronicle(null);
      }
    }

    return $this;
  }

  public function getPlayers(): Collection
  {
    return $this->players;
  }

  public function addPlayer(User $player): self
  {
    if (!$this->players->contains($player)) {
      $this->players[] = $player;
      $player->addChronicle($this);
    }

    return $this;
  }

  public function removePlayer(User $player): self
  {
    if ($this->players->removeElement($player)) {
      $player->removeChronicle($this);
    }

    return $this;
  }

  public function getStoryteller(): ?User
  {
    return $this->storyteller;
  }

  public function setStoryteller(User $storyteller): self
  {
    $this->storyteller = $storyteller;

    return $this;
  }


  public function getCharacter(User $user): ?Character
  {
    foreach ($this->getPlayerCharacters() as $character) {
      /** @var Character $character */
      if ($character->getPlayer() == $user) {
        return $character;
      }
    }

    return null;
  }

  /**
   * @return array<Character>
   */
  public function getPlayerCharacters(): array
  {
    $playerCharacters = [];
    foreach ($this->characters as $character) {
      /** @var Character $character */
      if ($character->isNpc() == false) {
        $playerCharacters[] = $character;
      }
    }

    return $playerCharacters;
  }

  /**
   * @return array<Character>
   */
  public function getOtherPlayerCharacters(): array
  {
    $playerCharacters = [];
    foreach ($this->characters as $character) {
      /** @var Character $character */
      if ($character->isNpc() == false && !$this->getPlayers()->contains($character->getPlayer())) {
        $playerCharacters[] = $character;
      }
    }

    return $playerCharacters;
  }

  /**
   * @return array<Character>
   */
  public function getNpc(): array
  {
    $npc = [];
    foreach ($this->characters as $character) {
      /** @var Character $character */
      if ($character->isNpc() == true) {
        $npc[] = $character;
      }
    }

    return $npc;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getMerits(): Collection
  {
    return $this->merits;
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
      $society->setChronicle($this);
    }

    return $this;
  }

  public function removeSociety(Society $society): self
  {
    if ($this->societies->removeElement($society)) {
      // set the owning side to null (unless already changed)
      if ($society->getChronicle() === $this) {
        $society->setChronicle(null);
      }
    }

    return $this;
  }

  public function getRules(?string $type): ?array
  {
    if ($type && isset($this->rules[$type])) {

      return $this->rules[$type];
    } else {
      return null;
    }

    return $this->rules;
  }

  public function setRules(?array $rules, string $type): static
  {
    if (is_null($this->rules)) {
      $this->rules = [];
    }

    $this->rules[$type] = $rules;

    return $this;
  }


  private function getFilteredOrganizations(User $user): array
  {
    $list = [];
    $isHidden = false;
    
    if ($user != $this->storyteller) {
      $isHidden = true;
    }
    foreach ($this->organizations as $organization) {
      /** @var Organization $organization */
      if (!($organization->isPrivate() && $isHidden)) {
        $list[] = $organization;
      }
    }
    
    return $list;
  }

  /**
   * @return array<Organization>
   * Return a list of the organizations available to the user based on an eventual filter
   */
  public function getOrganizations(?User $user = null, ?string $type = null): array
  {
    $list = [];
    $organizations = $this->organizations;
    if ($user) {
      $organizations = $this->getFilteredOrganizations($user);
    }

    foreach ($organizations as $organization) {
      switch ($type) {
        case "covenant":
          if ($organization instanceof Covenant) {
            $list[] = $organization;
          }
          break;

        case "order":
          if ($organization instanceof MageOrder) {
            $list[] = $organization;
          }
          break;

        default:
          if ($organization->getType() == "organization") {
            $list[] = $organization;
          }
          break;
      }
    }
    
    return $list;
  }

  /**
   * @return array<Clan>
   */
  public function getClans(): array
  {
    $clans = [];

    foreach ($this->clans as $clan) {
      /** @var Clan $clan */
      if (!$clan->isBloodline()) {

        $clans[] = $clan;
      }
    }

    return $clans;
  }

  public function getItems(): Collection
  {
    return $this->items;
  }

  public function getItemsTotal(): int
  {
    $total = $this->items->count();
    foreach ($this->characters as $character) {
      $total += $character->getItems()->count();
    }

    return $total;
  }

  public function addItem(Item $item): self
  {
    if (!$this->items->contains($item)) {
      $this->items[] = $item;
      $item->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeItem(Item $item): self
  {
    if ($this->items->removeElement($item)) {
      // set the owning side to null (unless already changed)
      if ($item->getHomebrewFor() === $this) {
        $item->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getStartAt(): ?\DateTimeInterface
  {
    return $this->startAt;
  }

  public function setStartAt(?\DateTimeInterface $startAt): static
  {
    $this->startAt = $startAt;

    return $this;
  }

  public function getCurrentlyAt(): ?\DateTimeInterface
  {
    return $this->currentlyAt;
  }

  public function setCurrentlyAt(?\DateTimeInterface $currentlyAt): static
  {
    $this->currentlyAt = $currentlyAt;

    return $this;
  }

  // VAMPIRE

  /**
   * @return array<Clan>
   */
  public function getBloodlines(): array
  {
    $bloodlines = [];

    foreach ($this->clans as $clan) {
      /** @var Clan $clan */
      if ($clan->isBloodline()) {

        $bloodlines[] = $clan;
      }
    }

    return $bloodlines;
  }

  public function getDevotions(): Collection
  {
    return $this->devotions;
  }

  public function getDisciplines(): Collection
  {
    return $this->disciplines;
  }

  public function getDisciplinesFromCategory(string $type = "simple"): array
  {
    $disciplines = [];
    foreach ($this->disciplines as $discipline) {
      switch ($type) {
        case 'sorcery':
          if ($discipline instanceof Discipline && $discipline->isSorcery()) {
            $disciplines[] = $discipline;
          }
          break;

        case 'thaumaturgy':
          if ($discipline instanceof Discipline && $discipline->isThaumaturgy()) {
            $disciplines[] = $discipline;
          }
          break;

        case 'coil':
          if ($discipline instanceof Discipline && $discipline->isCoil()) {
            $disciplines[] = $discipline;
          }
          break;
        default:
          if ($discipline instanceof Discipline && (!$discipline->isSorcery() && !$discipline->isThaumaturgy() && !$discipline->isCoil())) {
            $disciplines[] = $discipline;
          }
          break;
      }
    }

    return $disciplines;
  }

  public function getRituals(): array
  {
    $rituals = [];
    foreach ($this->rituals as $ritual) {
      if ($ritual->isRitual()) {
        $rituals[] = $ritual;
      }
    }

    return $rituals;
  }

  public function getCoils()
  {

    return;
  }

  /**
   * @return array<GhoulFamily>
   */
  public function getGhoulFamilies(): array
  {
    $ghoulFamilies = [];

    foreach ($this->ghoulFamilies as $family) {
      /** @var GhoulFamily $family */
      $ghoulFamilies[] = $family;
    }

    return $ghoulFamilies;
  }

  public function addGhoulFamily(GhoulFamily $family): self
  {
    if (!$this->ghoulFamilies->contains($family)) {
      $this->ghoulFamilies[] = $family;
      $family->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeGhoulFamily(GhoulFamily $family): self
  {
    if ($this->ghoulFamilies->removeElement($family)) {
      // set the owning side to null (unless already changed)
      if ($family->getHomebrewFor() === $this) {
        $family->setHomebrewFor(null);
      }
    }

    return $this;
  }

  // MAGE

  public function getPaths(): Collection
  {
    return $this->paths;
  }

  public function addPath(Path $path): self
  {
    if (!$this->paths->contains($path)) {
      $this->paths[] = $path;
      $path->setHomebrewFor($this);
    }

    return $this;
  }

  public function removePath(Path $path): self
  {
    if ($this->paths->removeElement($path)) {
      // set the owning side to null (unless already changed)
      if ($path->getHomebrewFor() === $this) {
        $path->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getLegacies(): Collection
  {
    return $this->legacies;
  }

  public function addLegacy(Legacy $legacy): self
  {
    if (!$this->legacies->contains($legacy)) {
      $this->legacies[] = $legacy;
      $legacy->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeLegacy(Legacy $legacy): self
  {
    if ($this->legacies->removeElement($legacy)) {
      // set the owning side to null (unless already changed)
      if ($legacy->getHomebrewFor() === $this) {
        $legacy->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getSpells(): Collection
  {
    return $this->spells;
  }

  public function addSpell(MageSpell $spell): self
  {
    if (!$this->spells->contains($spell)) {
      $this->spells[] = $spell;
      $spell->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeSpell(MageSpell $spell): self
  {
    if ($this->spells->removeElement($spell)) {
      // set the owning side to null (unless already changed)
      if ($spell->getHomebrewFor() === $this) {
        $spell->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getSpellRotes(): Collection
  {
    return $this->spellRotes;
  }

  public function addSpellRote(SpellRote $rote): self
  {
    if (!$this->spellRotes->contains($rote)) {
      $this->spellRotes[] = $rote;
      $rote->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeSpellRote(SpellRote $rote): self
  {
    if ($this->spellRotes->removeElement($rote)) {
      // set the owning side to null (unless already changed)
      if ($rote->getHomebrewFor() === $this) {
        $rote->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getAuspices(): Collection
  {
    return $this->auspices;
  }

  public function addAuspice(Auspice $auspice): self
  {
    if (!$this->auspices->contains($auspice)) {
      $this->auspices[] = $auspice;
      $auspice->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeAuspice(Auspice $auspice): self
  {
    if ($this->auspices->removeElement($auspice)) {
      // set the owning side to null (unless already changed)
      if ($auspice->getBook() === $this) {
        $auspice->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getTribes(): Collection
  {
    return $this->tribes;
  }

  public function addTribe(Tribe $tribe): self
  {
    if (!$this->tribes->contains($tribe)) {
      $this->tribes[] = $tribe;
      $tribe->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeTribe(Tribe $tribe): self
  {
    if ($this->tribes->removeElement($tribe)) {
      // set the owning side to null (unless already changed)
      if ($tribe->getBook() === $this) {
        $tribe->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getGifts(): Collection
  {
    return $this->gifts;
  }

  public function addGift(Gift $gift): self
  {
    if (!$this->gifts->contains($gift)) {
      $this->gifts[] = $gift;
      $gift->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeGift(Gift $gift): self
  {
    if ($this->gifts->removeElement($gift)) {
      // set the owning side to null (unless already changed)
      if ($gift->getBook() === $this) {
        $gift->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getGiftLists(): Collection
  {
    return $this->giftLists;
  }

  public function addGiftList(GiftList $giftList): self
  {
    if (!$this->giftLists->contains($giftList)) {
      $this->giftLists[] = $giftList;
      $giftList->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeGiftList(GiftList $giftList): self
  {
    if ($this->giftLists->removeElement($giftList)) {
      // set the owning side to null (unless already changed)
      if ($giftList->getBook() === $this) {
        $giftList->setHomebrewFor(null);
      }
    }

    return $this;
  }

  public function getRites(): Collection
  {
    return $this->rites;
  }

  public function addRite(Rite $rite): self
  {
    if (!$this->rites->contains($rite)) {
      $this->rites[] = $rite;
      $rite->setHomebrewFor($this);
    }

    return $this;
  }

  public function removeRite(Rite $rite): self
  {
    if ($this->rites->removeElement($rite)) {
      // set the owning side to null (unless already changed)
      if ($rite->getBook() === $this) {
        $rite->setHomebrewFor(null);
      }
    }

    return $this;
  }
}
