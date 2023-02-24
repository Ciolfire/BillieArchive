<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Traits\Typed;
use App\Repository\DerangementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\Entity(repositoryClass: DerangementRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\DerangementTranslation")]
class Derangement
{
  use Typed;
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Locale]
  private $locale;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $details = "";

  #[ORM\OneToOne(targetEntity: self::class, cascade: ['persist', 'remove'])]
  private ?self $previousAilment = null;


  #[ORM\Column]
  private ?bool $isExtreme = null;

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setTranslatableLocale($locale)
  {
    $this->locale = $locale;
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

  public function getDetails(): ?string
  {
    return $this->details;
  }

  public function setDetails(string $details): self
  {
    $converter = new HtmlConverter();
    $this->details = $converter->convert($details);

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

  public function getSeverity(): string
  {
    if (null === $this->getPreviousAilment()) {

      return "mild";
    }

    return "severe";
  }
}
