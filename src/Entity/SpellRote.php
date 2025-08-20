<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Repository\SpellRoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: SpellRoteRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "spellRotes"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "spellRotes")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\SpellRoteTranslation")]
class SpellRote implements Translatable
{
  use Homebrewable;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\ManyToOne(inversedBy: 'rotes')]
  private ?MageOrder $mageOrder = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  #[ORM\ManyToOne(inversedBy: 'rotes')]
  #[ORM\JoinColumn(nullable: false)]
  private ?MageSpell $spell = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Attribute $attribute = null;

  #[ORM\ManyToOne(inversedBy: 'createdRotes')]
  private ?Mage $creator = null;

  public function __construct(?MageSpell $spell = null, ?Mage $mage = null)
  {
    $this->spell = $spell;
    $this->creator = $mage;
    if ($spell instanceof MageSpell) {
      $this->homebrewFor = $spell->getHomebrewFor();
      $this->book = $spell->getBook();
      $this->page = $spell->getPage();
    } else if ($mage instanceof Mage) {
      $this->homebrewFor = $mage->getChronicle();
    }
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

  public function getMageOrder(): ?MageOrder
  {
    return $this->mageOrder;
  }

  public function setMageOrder(?MageOrder $mageOrder): static
  {
    $this->mageOrder = $mageOrder;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;

    return $this;
  }

  public function getSpell(): ?MageSpell
  {
    return $this->spell;
  }

  public function setSpell(?MageSpell $spell): static
  {
    $this->spell = $spell;

    return $this;
  }

  public function getAttribute(): ?Attribute
  {
    return $this->attribute;
  }

  public function setAttribute(?Attribute $attribute): static
  {
    $this->attribute = $attribute;

    return $this;
  }

  public function getLevel()
  {
    return $this->getSpell()->getLevel();
  }

  public function getCreator(): ?Mage
  {
      return $this->creator;
  }

  public function setCreator(?Mage $creator): static
  {
      $this->creator = $creator;

      return $this;
  }
}
