<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\ClanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;


#[Gedmo\TranslationEntity(class: "App\Entity\Translation\ClanTranslation")]
#[ORM\Table(name: "clan")]
#[ORM\Entity(repositoryClass: ClanRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "clans"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "clans")])]
class Clan
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private ?int $id;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 40)]
  private string $name = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $description = "";

  #[ORM\ManyToMany(targetEntity: Attribute::class)]
  private Collection $attributes;

  #[ORM\ManyToMany(targetEntity: Discipline::class)]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $disciplines;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $short = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 100)]
  private string $keywords = "";

  #[ORM\ManyToOne(targetEntity: Clan::class, inversedBy: "bloodlines")]
  private ?Clan $parentClan = null;

  #[ORM\OneToMany(targetEntity: Clan::class, mappedBy: "parentClan")]
  private Collection $bloodlines;

  #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
  private ?string $emblem;

  #[ORM\Column(length: 50)]
  private string $nickname = "";

  #[ORM\Column(type: Types::TEXT)]
  private string $weakness = "";

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $quote = null;

  #[ORM\Column]
  private bool $isBloodline = false;

  #[ORM\OneToMany(mappedBy: 'bloodline', targetEntity: Devotion::class)]
  private Collection $devotions;

  public function __construct(bool $isBloodline = false)
  {
    $this->isBloodline = $isBloodline;

    $this->attributes = new ArrayCollection();
    $this->disciplines = new ArrayCollection();
    $this->bloodlines = new ArrayCollection();
    $this->devotions = new ArrayCollection();
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
    $converter = new HtmlConverter();
    $this->description = $converter->convert($description);

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
    $this->weakness = $weakness;

    return $this;
  }

  public function getQuote(): ?string
  {
    return $this->quote;
  }

  public function setQuote(string $quote = ""): self
  {
    $converter = new HtmlConverter();
    $this->quote = $converter->convert($quote);

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
}
