<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChronicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChronicleRepository::class)]
class Chronicle
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id;

  #[ORM\Column(type: Types::STRING, length: 255)]
  private string $name;

  #[ORM\OneToMany(targetEntity: Character::class, mappedBy: "chronicle")]
  #[ORM\OrderBy(["groupName" => "ASC", "firstName" => "ASC", "id" => "DESC"])]
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

  #[ORM\OneToMany(mappedBy: 'chronicle', targetEntity: Society::class)]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $societies;

  #[ORM\Column(type: Types::JSON,  nullable: true)]
  private ?array $rules = null;

  #[ORM\OneToMany(targetEntity: GhoulFamily::class, mappedBy: 'homebrewFor')]
  private Collection $ghoulFamilies;

  public function __construct()
  {
    $this->characters = new ArrayCollection();
    $this->players = new ArrayCollection();
    $this->merits = new ArrayCollection();

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
    foreach ($this->characters as $character) {
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

  public function getRituals(): Collection
  {
    return $this->rituals;
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
}
