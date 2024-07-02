<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Traits\Typed;
use App\Repository\RollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\Entity(repositoryClass: RollRepository::class)]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\RollTranslation")]
class Roll implements Translatable
{
  public const Type = [
    0 => 'roll.action.instant',
    1 => 'roll.action.extended',
    2 => 'roll.action.reflexive',
  ];

  use Typed;
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private string $name = "";

  #[ORM\ManyToMany(targetEntity: Attribute::class, inversedBy: 'rolls')]
  private Collection $attributes;

  #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'rolls')]
  private Collection $skills;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $action = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $details = "";

  #[ORM\Column]
  private bool $isImportant = false;

  #[ORM\Column]
  private bool $isContested = false;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $contestedText = null;

  private string $locale;

  public function __construct()
  {
    $this->attributes = new ArrayCollection();
    $this->skills = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setTranslatableLocale(string $locale) : self
  {
    $this->locale = $locale;

    return $this;
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

  public function getAction(): ?int
  {
    return $this->action;
  }

  public function setAction(int $action): self
  {
    $this->action = $action;

    return $this;
  }

  public function getActionName(): string
  {
    return self::Type[$this->action];
  }

  public function getDetails(): string
  {
    return $this->details;
  }

  public function setDetails(string $details): self
  {
    $converter = new HtmlConverter();
    $this->details = $converter->convert($details);

    return $this;
  }

  public function isImportant(): bool
  {
    return $this->isImportant;
  }

  public function setIsImportant(bool $isImportant): self
  {
    $this->isImportant = $isImportant;

    return $this;
  }

  public function isContested(): bool
  {
      return $this->isContested;
  }

  public function setIsContested(bool $isContested): self
  {
      $this->isContested = $isContested;

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
}
