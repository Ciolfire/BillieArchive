<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Traits\Typed;
use App\Repository\FlawRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: FlawRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "flaws"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "flaws")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\FlawTranslation")]
class Flaw implements Translatable
{
  use Typed;
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column(length: 20)]
  private ?string $category = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $effect = null;

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

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getCategory(): ?string
  {
    return $this->category;
  }

  public function setCategory(string $category): static
  {
    $this->category = $category;

    return $this;
  }

  public function getEffect(): ?string
  {
    return $this->effect;
  }

  public function setEffect(string $effect): static
  {
    if ($this->effect == "") {
      $this->effect = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $effect);
    } else {
      $this->effect = $effect;
    }

    return $this;
  }
}
