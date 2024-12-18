<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Traits\Typed;
use App\Repository\DerangementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: DerangementRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "derangements")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\DerangementTranslation")]
class Derangement implements Translatable
{
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

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $details = "";

  #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'degenerations', cascade: ['persist', 'remove'])]
  private ?self $previousAilment = null;

  #[ORM\OneToMany(mappedBy: "previousAilment", targetEntity: self::class)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $degenerations;

  #[ORM\Column]
  private bool $isExtreme = false;

  private string $locale;

  public function __construct()
  {
    $this->degenerations = new ArrayCollection();
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

  public function setName(string $name = ""): self
  {
    $this->name = $name;

    return $this;
  }

  public function getDetails(): string
  {
    return $this->details;
  }

  public function setDetails(string $details = ""): self
  {
    $this->details = $details;

    return $this;
  }

  public function getPreviousAilment(): ?self
  {
    return $this->previousAilment;
  }

  public function setPreviousAilment(?self $previousAilment): self
  {
    $this->previousAilment = $previousAilment;

    return $this;
  }

  public function isExtreme(): ?bool
  {
    return $this->isExtreme;
  }

  public function setIsExtreme(bool $isExtreme): self
  {
    $this->isExtreme = $isExtreme;

    return $this;
  }

  public function isMild(): bool
  {
    if (null === $this->getPreviousAilment()) {

      return true;
    }

    return false;
  }

  public function getDegenerations(): Collection
  {
    return $this->degenerations;
  }

  public function setTranslatableLocale(string $locale) : self
  {
    $this->locale = $locale;

    return $this;
  }
}
