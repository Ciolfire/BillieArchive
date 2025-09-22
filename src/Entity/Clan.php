<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\ClanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: ClanRepository::class)]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "clans"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "clans")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\ClanTranslation")]
class Clan implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: Types::STRING, length: 40)]
  private string $name = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $description = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $short = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 100)]
  private string $keywords = "";

  #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
  private ?string $emblem;

  #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
  private ?string $symbol;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 50)]
  private string $nickname = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $weakness = "";

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $quote = null;

  #[ORM\Column]
  private bool $isBloodline = false;

  #[ORM\ManyToOne(targetEntity: Clan::class, inversedBy: "bloodlines")]
  private ?Clan $parentClan = null;

  #[ORM\OneToMany(targetEntity: Clan::class, mappedBy: "parentClan")]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $bloodlines;

  #[ORM\ManyToMany(targetEntity: Attribute::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $attributes;

  #[ORM\ManyToMany(targetEntity: Discipline::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $disciplines;

  #[ORM\OneToMany(mappedBy: 'bloodline', targetEntity: Devotion::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $devotions;

  #[ORM\OneToMany(mappedBy: 'clan', targetEntity: GhoulFamily::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $ghoulFamilies;

  public function __construct(bool $isBloodline = false, $element = null)
  {
    $this->isBloodline = $isBloodline;

    if ($element instanceof Chronicle) {
      $this->setHomebrewFor($element);
    } else if ($element instanceof Book) {
      $this->setBook($element);
    }

    $this->attributes = new ArrayCollection();
    $this->disciplines = new ArrayCollection();
    $this->bloodlines = new ArrayCollection();
    $this->devotions = new ArrayCollection();
    $this->ghoulFamilies = new ArrayCollection();
  }

  public function __toString()
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

  /**
   * @return Collection|Attribute[]
   */
  public function getAttributes(): Collection
  {
    return $this->attributes;
  }

  public function addAttribute(Attribute $attribute): self
  {
    if (!$this->attributes->contains($attribute)) {
      $this->attributes[] = $attribute;
    }

    return $this;
  }

  public function removeAttribute(Attribute $attribute): self
  {
    $this->attributes->removeElement($attribute);

    return $this;
  }

  /**
   * @return Collection|Discipline[]
   */
  public function getDisciplines(): Collection
  {
    return $this->disciplines;
  }

  public function addDiscipline(Discipline $discipline): self
  {
    if (!$this->disciplines->contains($discipline)) {
      $this->disciplines[] = $discipline;
    }

    return $this;
  }

  public function removeDiscipline(Discipline $discipline): self
  {
    $this->disciplines->removeElement($discipline);

    return $this;
  }

  public function hasDiscipline(Discipline $discipline): bool
  {
    if (in_array($discipline, $this->disciplines->toArray())) {

      return true;
    }

    return false;
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

  public function getKeywords(): ?string
  {
    return $this->keywords;
  }

  public function setKeywords(string $keywords): self
  {
    $this->keywords = $keywords;

    return $this;
  }

  public function isFavored(Discipline $discipline): bool
  {
    if ($this->disciplines->contains($discipline)) {

      return true;
    }

    return false;
  }

  public function getParentClan(): ?self
  {
    return $this->parentClan;
  }

  public function setParentClan(?self $parentClan): self
  {
    $this->parentClan = $parentClan;

    return $this;
  }

  public function getBloodlines(): Collection
  {
    return $this->bloodlines;
  }

  public function addBloodline(self $bloodline): self
  {
    if (!$this->bloodlines->contains($bloodline)) {
      $this->bloodlines[] = $bloodline;
      $bloodline->setParentClan($this);
    }

    return $this;
  }

  public function removeBloodline(self $bloodline): self
  {
    if ($this->bloodlines->removeElement($bloodline)) {
      // set the owning side to null (unless already changed)
      if ($bloodline->getParentClan() === $this) {
        $bloodline->setParentClan(null);
      }
    }

    return $this;
  }

  public function getEmblem(): ?string
  {
    return $this->emblem;
  }

  public function setEmblem(?string $emblem): self
  {
    $this->emblem = $emblem;

    return $this;
  }

  public function getSymbol(): ?string
  {
    return $this->symbol;
  }

  public function setSymbol(?string $symbol): self
  {
    $this->symbol = $symbol;

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

  public function getWeakness(): string
  {
    return $this->weakness;
  }

  public function setWeakness(string $weakness): self
  {
    if ($this->weakness == "") {
      $this->weakness = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $weakness);
    } else {
      $this->weakness = $weakness;
    }

    return $this;
  }

  public function getQuote(): ?string
  {
    return $this->quote;
  }

  public function setQuote(string $quote = ""): self
  {
    $this->quote = $quote;

    return $this;
  }

  public function isBloodline(): bool
  {
      return $this->isBloodline;
  }

  public function setIsBloodline(bool $isBloodline): self
  {
      $this->isBloodline = $isBloodline;

      return $this;
  }

  /**
   * @return Collection<int, Devotion>
   */
  public function getDevotions(): Collection
  {
      return $this->devotions;
  }

  public function addDevotion(Devotion $devotion): self
  {
      if (!$this->devotions->contains($devotion)) {
          $this->devotions->add($devotion);
          $devotion->setBloodline($this);
      }

      return $this;
  }

  public function removeDevotion(Devotion $devotion): self
  {
      if ($this->devotions->removeElement($devotion)) {
          // set the owning side to null (unless already changed)
          if ($devotion->getBloodline() === $this) {
              $devotion->setBloodline(null);
          }
      }

      return $this;
  }

  /**
   * @return Collection<int, GhoulFamily>
   */
  public function getGhoulFamilies(): Collection
  {
      return $this->ghoulFamilies;
  }

  public function addGhoulFamily(GhoulFamily $ghoulFamily): static
  {
      if (!$this->ghoulFamilies->contains($ghoulFamily)) {
          $this->ghoulFamilies->add($ghoulFamily);
          $ghoulFamily->setClan($this);
      }

      return $this;
  }

  public function removeGhoulFamily(GhoulFamily $ghoulFamily): static
  {
      if ($this->ghoulFamilies->removeElement($ghoulFamily)) {
          // set the owning side to null (unless already changed)
          if ($ghoulFamily->getClan() === $this) {
              $ghoulFamily->setClan(null);
          }
      }

      return $this;
  }
}
