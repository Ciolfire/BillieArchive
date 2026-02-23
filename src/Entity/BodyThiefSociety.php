<?php

namespace App\Entity;

use App\Repository\BodyThiefSocietyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Types\BodyThiefTalent;

#[ORM\Entity(repositoryClass: BodyThiefSocietyRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\BodyThiefSocietyTranslation")]
class BodyThiefSociety implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 100)]
  private ?string $name = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: true)]
  private ?Merit $definingMerit = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $advantage = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $weakness = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $creation = null;

  #[ORM\Column(type: Types::INTEGER, enumType: BodyThiefTalent::class)]
  private BodyThiefTalent $talentType = BodyThiefTalent::mental;

  public function __toString()
  {
    return $this->name;
  }

  public function __construct()
  {
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

  public function getDefiningMerit(): ?Merit
  {
    return $this->definingMerit;
  }

  public function setDefiningMerit(?Merit $definingMerit): static
  {
    $this->definingMerit = $definingMerit;

    return $this;
  }

  public function getAdvantage(): ?string
  {
    return $this->advantage;
  }

  public function setAdvantage(string $advantage): static
  {
    if ($this->advantage == "") {
      $this->advantage = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $advantage);
    } else {
      $this->advantage = $advantage;
    }

    return $this;
  }

  public function getWeakness(): ?string
  {
    return $this->weakness;
  }

  public function setWeakness(string $weakness): static
  {
    if ($this->weakness == "") {
      $this->weakness = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $weakness);
    } else {
      $this->weakness = $weakness;
    }

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  public function getCreation(): ?string
  {
    return $this->creation;
  }

  public function setCreation(string $creation): static
  {
    if ($this->creation == "") {
      $this->creation = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $creation);
    } else {
      $this->creation = $creation;
    }

    return $this;
  }

  public function getTalentType(): BodyThiefTalent
  {
    return $this->talentType;
  }

  public function setTalentType(BodyThiefTalent $talentType): static
  {
    $this->talentType = $talentType;

    return $this;
  }
}
