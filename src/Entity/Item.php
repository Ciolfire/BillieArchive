<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;
use App\Entity\Items\Equipment;
use App\Entity\Items\RangedWeapon;
use App\Entity\Items\Vehicle;
use App\Entity\Items\Weapon;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "items"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "items")])]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: Types::STRING)]
#[ORM\DiscriminatorMap(["item" => Item::class, "equipment" => Equipment::class, "vehicle" => Vehicle::class, "ranged" => RangedWeapon::class, "melee" => Weapon::class])]
class Item
{
  use Sourcable;
  use Homebrewable;

  const TYPELIST = [
    0 => 'item',
    1 => 'equipment',
    2 => 'vehicle',
    3 => 'ranged_weapon',
    4 => 'weapon',
    5 => 'armor',
  ];

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  protected ?int $id = null;

  #[ORM\Column(length: 255)]
  protected ?string $name = null;

  #[ORM\Column]
  protected ?bool $isContainer = false;

  #[ORM\Column(type: Types::TEXT)]
  protected ?string $description = "";

  #[ORM\Column(length: 10, nullable: true)]
  protected ?string $durability = null;

  #[ORM\Column(length: 10, nullable: true)]
  protected ?string $size = null;

  #[ORM\Column(length: 255, nullable: true)]
  protected ?string $img = null;

  #[ORM\Column(nullable: true)]
  protected ?array $cost = null;

  #[ORM\ManyToOne(inversedBy: 'items')]
  protected ?Character $owner = null;

  #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'containedItems')]
  protected ?self $container = null;

  /**
   * @var Collection<int, self>
   */
  #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'container')]
  private Collection $containedItems;

  public function __construct()
  {
      $this->containedItems = new ArrayCollection();
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

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function isContainer(): ?bool
  {
    return $this->isContainer;
  }

  public function setIsContainer(bool $isContainer): static
  {
    $this->isContainer = $isContainer;

    return $this;
  }

  public function isWeapon(): ?bool
  {
    return false;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    $this->description = $description;

    return $this;
  }

  public function getDurability(): ?string
  {
    return $this->durability;
  }

  public function setDurability(?string $durability): static
  {
    $this->durability = $durability;

    return $this;
  }

  public function getSize(): ?string
  {
    return $this->size;
  }

  public function setSize(?string $size): static
  {
    $this->size = $size;

    return $this;
  }

  public function getStructure(): ?string
  {
    if (is_numeric($this->size) && is_numeric($this->durability)) {
      return $this->size + $this->durability;
    } else return 0;
  }

  public function getImg(): ?string
  {
      return $this->img;
  }

  public function setImg(?string $img): static
  {
      $this->img = $img;

      return $this;
  }

  public function getOwner(): ?Character
  {
      return $this->owner;
  }

  public function setOwner(?Character $owner): static
  {
      $this->owner = $owner;

      return $this;
  }

  public function getContainer(): ?self
  {
      return $this->container;
  }

  public function setContainer(?Item $item): self
  {
    $this->container = $item;

    return $this;
  }

  public function getForm()
  {
    return ItemType::class;
  }

  public function getTypeName()
  {
    return "item";
  }

  public function getCost(): ?array
  {
      return $this->cost;
  }

  public function setCost(?array $cost): static
  {
      $this->cost = $cost;

      return $this;
  }

  /**
   * @return Collection<int, self>
   */
  public function getContainedItems(): Collection
  {
      return $this->containedItems;
  }

  public function addContainedItem(self $containedItem): static
  {
      if (!$this->containedItems->contains($containedItem)) {
          $this->containedItems->add($containedItem);
          $containedItem->setContainer($this);
      }

      return $this;
  }

  public function removeContainedItem(self $containedItem): static
  {
      if ($this->containedItems->removeElement($containedItem)) {
          // set the owning side to null (unless already changed)
          if ($containedItem->getContainer() === $this) {
              $containedItem->setContainer(null);
          }
      }

      return $this;
  }
}
