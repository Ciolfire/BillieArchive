<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Ancient;
use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\DevotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: DevotionRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "devotions"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "devotions")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\DevotionTranslation")]
class Devotion implements Translatable
{
  use Homebrewable;
  use Sourcable;
  use Ancient;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private string $name = "";

  #[ORM\ManyToMany(targetEntity: Prerequisite::class, inversedBy: 'devotions', cascade: ['persist', 'remove'])]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $prerequisites;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $cost = 0;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $description = "";

  #[ORM\Column(length: 255)]
  private string $short = "";

  #[ORM\ManyToMany(targetEntity: Attribute::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $attributes;

  #[ORM\ManyToMany(targetEntity: Skill::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $skills;

  #[ORM\ManyToMany(targetEntity: Discipline::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $disciplines;

  #[ORM\ManyToOne(inversedBy: 'devotions')]
  private ?Clan $bloodline = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $contestedText = null;
  
  #[ORM\Column]
  private ?bool $usePotency = null;

  #[ORM\ManyToMany(targetEntity: Covenant::class, mappedBy: 'devotions')]
  private Collection $covenants;


  public function __construct()
  {
    $this->prerequisites = new ArrayCollection();
    $this->description = "";
    $this->attributes = new ArrayCollection();
    $this->skills = new ArrayCollection();
    $this->disciplines = new ArrayCollection();
    $this->covenants = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection<int, Prerequisite>
   */
  public function getPrerequisites(): Collection
  {
    return $this->prerequisites;
  }

  public function addPrerequisite(Prerequisite $prerequisite): self
  {
    if (!$this->prerequisites->contains($prerequisite)) {
      $this->prerequisites->add($prerequisite);
    }

    return $this;
  }

  public function removePrerequisite(Prerequisite $prerequisite): self
  {
    $this->prerequisites->removeElement($prerequisite);

    return $this;
  }

  public function getCost(): int
  {
    return $this->cost;
  }

  public function setCost(int $cost): self
  {
    $this->cost = $cost;

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

  public function getShort(): ?string
  {
      return $this->short;
  }

  public function setShort(string $short): self
  {
      $this->short = $short;

      return $this;
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

  /**
   * @return Collection<int, Discipline>
   */
  public function getDisciplines(): Collection
  {
      return $this->disciplines;
  }

  public function addDiscipline(Discipline $discipline): self
  {
      if (!$this->disciplines->contains($discipline)) {
          $this->disciplines->add($discipline);
      }

      return $this;
  }

  public function removeDiscipline(Discipline $discipline): self
  {
      $this->disciplines->removeElement($discipline);

      return $this;
  }

  public function getBloodline(): ?Clan
  {
      return $this->bloodline;
  }

  public function setBloodline(?Clan $bloodline): self
  {
      $this->bloodline = $bloodline;

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

    if (preg_match("/Cost[*]*:([^:]+)/i", $this->getDescription(), $matches)) {

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

  /**
   * @return Collection<int, Covenant>
   */
  public function getCovenants(): Collection
  {
      return $this->covenants;
  }

  public function addCovenant(Covenant $covenant): static
  {
      if (!$this->covenants->contains($covenant)) {
          $this->covenants->add($covenant);
          $covenant->addDevotion($this);
      }

      return $this;
  }

  public function removeCovenant(Covenant $covenant): static
  {
      if ($this->covenants->removeElement($covenant)) {
          $covenant->removeDevotion($this);
      }

      return $this;
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

  public function getPrerequisiteDisciplines() : string
  {
    $prerequisites = "";
    foreach ($this->prerequisites as $prerequisite) {
      if ($prerequisite->getEntity() instanceof Discipline) {
        $prerequisites .= $prerequisite->getEntity()->getName();
      }
    }
    return $prerequisites;
  }

  public function getDisciplinesLevel()
  {
    $disciplines = [];
    foreach ($this->prerequisites as $prerequisite) {
      /** @var Prerequisite $prerequisite */
      $entity = $prerequisite->getEntity();
      if ($entity instanceof Discipline && !$prerequisite->getChoiceGroup()) {
        $disciplines[$entity->getId()] = $prerequisite->getValue();
      }
    }
    return $disciplines;
  }

  public function getMinPotency()
  {
    foreach ($this->prerequisites as $prerequisite) {
      /** @var Prerequisite $prerequisite */
      if ($prerequisite->getType() == "potency") {
        return $prerequisite->getValue();
      }
    }

    return 0;
  }
}
