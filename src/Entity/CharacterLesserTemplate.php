<?php

namespace App\Entity;

use App\Repository\CharacterLesserTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;

#[ORM\Entity(repositoryClass: CharacterLesserTemplateRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'name', type: 'string')]
#[DiscriminatorMap([
  'bloodBather' => BloodBather::class,
  'bodyThief' => BodyThief::class,
  'feral' => Feral::class, 
  'ghoul' => Ghoul::class, 
  'innocents' => Innocent::class,
  'possessed' => Possessed::class, 
  'psychic' => Psychic::class, 
  'purified' => Purified::class,
  'thaumaturge' => Thaumaturge::class, 
])]
class CharacterLesserTemplate
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column]
  private bool $isActive = true;

  #[ORM\ManyToOne(inversedBy: 'lesserTemplates')]
  #[ORM\JoinColumn(nullable: false)]
  protected ?Character $sourceCharacter = null;

  public function __clone()
  {
    $this->id = null;
  }

  // cloning a relation which is a OneToMany
  protected function cloneCollection($collection)
  {
    $collectionClone = new ArrayCollection();
    foreach ($collection as $item) {
        $itemClone = clone $item;
        $itemClone->setCharacter($this);
        $collectionClone->add($itemClone);
    }
    return $collectionClone;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getType()
  {
    return "";
  }

  public function getSetting()
  {
    return "";
  }

  public static function getForm() : ?string
  {
    return null;
  }

  public function getPowerRating(array $weight) : int
  {
    return 0;
  }

  public function detailedDicePool(Collection $attributes, Collection $skills, ?Collection $specials = null, array $modifiers = []) : array
  {
    return $this->sourceCharacter->detailedDicePool($attributes, $skills, $specials, $modifiers);
  }

  public function getChronicle() : ?Chronicle
  {
    return $this->sourceCharacter->getChronicle();
  }

  public function getSourceCharacter(): ?Character
  {
    return $this->sourceCharacter;
  }

  public function setSourceCharacter(?Character $sourceCharacter): static
  {
    $this->sourceCharacter = $sourceCharacter;

    return $this;
  }

  public function isActive(): bool
  {
    return $this->isActive;
  }

  public function setIsActive(bool $isActive): static
  {
    $this->isActive = $isActive;

    return $this;
  }
}
