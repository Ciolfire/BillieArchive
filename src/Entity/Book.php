<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\BookTranslation")]
class Book implements Translatable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 255)]
  private string $name ="";

  #[ORM\Column(type: Types::SMALLINT)]

  private int $ruleset = 1;

  #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
  private ?string $type;

  #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
  private ?string $short;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $description = "";

  #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
  private \DateTimeImmutable $releasedAt;

  #[ORM\Column(type: Types::STRING, length: 50)]
  private string $setting = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
  private ?string $cover;

  #[ORM\OneToMany(targetEntity: Derangement::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $derangements;

  #[ORM\OneToMany(targetEntity: Merit::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $merits;

  #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $items;

  #[ORM\Column(nullable: true)]
  private ?bool $displayFirst = null;

  #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["firstName" => "ASC", "id" => "ASC"])]
  private Collection $characters;

  // Human
  // #[ORM\OneToMany(targetEntity: Organization::class, mappedBy: 'homebrewFor')]
  // #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  // private Collection $organizations;

  // Vampire
  #[ORM\OneToMany(targetEntity: Clan::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $clans;

  #[ORM\OneToMany(targetEntity: Devotion::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["name" => "ASC", "id" => "DESC"])]
  private Collection $devotions;
  
  #[ORM\OneToMany(targetEntity: Discipline::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $disciplines;

  #[ORM\OneToMany(targetEntity: DisciplinePower::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["discipline" => "ASC", "level" => "ASC", "name" => "ASC", "id" => "ASC"])]
  private Collection $rituals;

  #[ORM\OneToMany(targetEntity: GhoulFamily::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $ghoulFamilies;

  // Mage
  #[ORM\OneToMany(targetEntity: Arcanum::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $arcana;

  #[ORM\OneToMany(targetEntity: Path::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $paths;

  #[ORM\OneToMany(targetEntity: Legacy::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $legacies;

  #[ORM\OneToMany(targetEntity: MageSpell::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $spells;

  #[ORM\OneToMany(targetEntity: SpellRote::class, mappedBy: 'book')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $spellRotes;

  public function __construct(string $setting="human")
  {
    $this->setting = $setting;
    $this->derangements = new ArrayCollection();
    $this->merits = new ArrayCollection();
    $this->items = new ArrayCollection();
    // vampire
    // $this->clans = new ArrayCollection();
    // $this->devotions = new ArrayCollection();
    // $this->disciplines = new ArrayCollection();
    // $this->rituals = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getDisplayName(): ?string
  {
    return $this->name;
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

  public function getRuleset(): ?int
  {
    return $this->ruleset;
  }

  public function setRuleset(int $ruleset): self
  {
    $this->ruleset = $ruleset;

    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(?string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(?string $short): self
  {
    $this->short = $short;

    return $this;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description = ""): self
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  public function getReleasedAt(): ?\DateTimeImmutable
  {
    return $this->releasedAt;
  }

  public function setReleasedAt(\DateTimeImmutable $releasedAt): self
  {
    $this->releasedAt = $releasedAt;

    return $this;
  }

  public function getSetting(): ?string
  {
    return $this->setting;
  }

  public function setSetting(string $setting): self
  {
    $this->setting = $setting;

    return $this;
  }

  public function getCover(): ?string
  {
      return $this->cover;
  }

  public function setCover(?string $cover): self
  {
      $this->cover = $cover;

      return $this;
  }

  public function getDerangements(): Collection
  {
    return $this->derangements;
  }

  public function isDisplayFirst(): ?bool
  {
      return $this->displayFirst;
  }

  public function setDisplayFirst(?bool $displayFirst): self
  {
      $this->displayFirst = $displayFirst;

      return $this;
  }

  public function getMerits(): Collection
  {
    return $this->merits;
  }

  public function addMerit(Merit $merit): self
  {
    if (!$this->merits->contains($merit)) {
      $this->merits[] = $merit;
      $merit->setBook($this);
    }

    return $this;
  }

  public function removeMerit(Merit $merit): self
  {
    if ($this->merits->removeElement($merit)) {
      // set the owning side to null (unless already changed)
      if ($merit->getBook() === $this) {
        $merit->setBook(null);
      }
    }

    return $this;
  }

  public function getItems(): Collection
  {
    return $this->items;
  }

  public function addItem(Item $item): self
  {
    if (!$this->items->contains($item)) {
      $this->items[] = $item;
      $item->setBook($this);
    }

    return $this;
  }

  public function removeItem(Item $item): self
  {
    if ($this->items->removeElement($item)) {
      // set the owning side to null (unless already changed)
      if ($item->getBook() === $this) {
        $item->setBook(null);
      }
    }

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
      $character->setBook($this);
    }

    return $this;
  }

  public function removeCharacter(Character $character): self
  {
    if ($this->characters->removeElement($character)) {
      // set the owning side to null (unless already changed)
      if ($character->getBook() === $this) {
        $character->setBook(null);
      }
    }

    return $this;
  }

  // /**
  //  * @return array<Organization>
  //  */
  // public function getOrganizations(): array
  // {
  //   $list = [];
  //   foreach ($this->organizations as $organization) {
  //     if ($organization->getType() == "organization") {
  //       $list[] = $organization;
  //     }
  //   }
    
  //   return $list;
  // }

  // VAMPIRE

  /**
   * @return Collection<Clan>
   */
  public function getClansAndBloodlines(): Collection
  {
    return $this->clans;
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

  public function addClan(Clan $clan): self
  {
    if (!$this->clans->contains($clan)) {
      $this->clans[] = $clan;
      $clan->setBook($this);
    }

    return $this;
  }

  public function removeClan(Clan $clan): self
  {
    if ($this->clans->removeElement($clan)) {
      // set the owning side to null (unless already changed)
      if ($clan->getBook() === $this) {
        $clan->setBook(null);
      }
    }

    return $this;
  }

  public function getDevotions(): Collection
  {
    return $this->devotions;
  }

  public function addDevotion(Devotion $devotion): self
  {
    if (!$this->devotions->contains($devotion)) {
      $this->devotions[] = $devotion;
      $devotion->setBook($this);
    }

    return $this;
  }

  public function removeDevotion(Devotion $devotion): self
  {
    if ($this->devotions->removeElement($devotion)) {
      // set the owning side to null (unless already changed)
      if ($devotion->getBook() === $this) {
        $devotion->setBook(null);
      }
    }

    return $this;
  }

  public function getDisciplines(): Collection
  {
    return $this->disciplines;
  }

  public function addDiscipline(Discipline $discipline): self
  {
    if (!$this->disciplines->contains($discipline)) {
      $this->disciplines[] = $discipline;
      $discipline->setBook($this);
    }

    return $this;
  }

  public function removeDiscipline(Discipline $discipline): self
  {
    if ($this->disciplines->removeElement($discipline)) {
      // set the owning side to null (unless already changed)
      if ($discipline->getBook() === $this) {
        $discipline->setBook(null);
      }
    }

    return $this;
  }

  public function getRituals(): Collection
  {
    return $this->rituals->filter(function(DisciplinePower $power) {
      return $power->isRitual() ? true : false;
    });
  }

  public function addRitual(DisciplinePower $ritual): self
  {
    if (!$this->rituals->contains($ritual)) {
      $this->rituals[] = $ritual;
      $ritual->setBook($this);
    }

    return $this;
  }

  public function removeRitual(DisciplinePower $ritual): self
  {
    if ($this->rituals->removeElement($ritual)) {
      // set the owning side to null (unless already changed)
      if ($ritual->getBook() === $this) {
        $ritual->setBook(null);
      }
    }

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
      $family->setBook($this);
    }

    return $this;
  }

  public function removeGhoulFamily(GhoulFamily $family): self
  {
    if ($this->ghoulFamilies->removeElement($family)) {
      // set the owning side to null (unless already changed)
      if ($family->getBook() === $this) {
        $family->setBook(null);
      }
    }

    return $this;
  }

  // MAGE
  public function getArcana(): Collection
  {
    return $this->arcana;
  }

  public function addArcanum(Arcanum $arcanum): self
  {
    if (!$this->arcana->contains($arcanum)) {
      $this->arcana[] = $arcanum;
      $arcanum->setBook($this);
    }

    return $this;
  }

  public function removeArcanum(Arcanum $arcanum): self
  {
    if ($this->arcana->removeElement($arcanum)) {
      // set the owning side to null (unless already changed)
      if ($arcanum->getBook() === $this) {
        $arcanum->setBook(null);
      }
    }

    return $this;
  }

  public function getPaths(): Collection
  {
    return $this->paths;
  }

  public function addPath(Path $path): self
  {
    if (!$this->paths->contains($path)) {
      $this->paths[] = $path;
      $path->setBook($this);
    }

    return $this;
  }

  public function removePath(Path $path): self
  {
    if ($this->paths->removeElement($path)) {
      // set the owning side to null (unless already changed)
      if ($path->getBook() === $this) {
        $path->setBook(null);
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
      $legacy->setBook($this);
    }

    return $this;
  }

  public function removeLegacy(Legacy $legacy): self
  {
    if ($this->legacies->removeElement($legacy)) {
      // set the owning side to null (unless already changed)
      if ($legacy->getBook() === $this) {
        $legacy->setBook(null);
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
      $spell->setBook($this);
    }

    return $this;
  }

  public function removeSpell(MageSpell $spell): self
  {
    if ($this->spells->removeElement($spell)) {
      // set the owning side to null (unless already changed)
      if ($spell->getBook() === $this) {
        $spell->setBook(null);
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
      $rote->setBook($this);
    }

    return $this;
  }

  public function removeSpellRote(SpellRote $rote): self
  {
    if ($this->spellRotes->removeElement($rote)) {
      // set the owning side to null (unless already changed)
      if ($rote->getBook() === $this) {
        $rote->setBook(null);
      }
    }

    return $this;
  }
}
