<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MageRepository;
use App\Form\Mage\MageType;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: MageRepository::class)]
class Mage extends Character
{
  #[ORM\Column(type: Types::SMALLINT)]
  private int $gnosis = 1;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $mana = 1;

  #[ORM\ManyToOne(targetEntity: Path::class)]
  #[ORM\JoinColumn(nullable: false)]
  private Path $path;

  #[ORM\ManyToOne]
  private ?MageOrder $order = null;

  /**
   * @var Collection<int, MageArcanum>
   */
  #[ORM\OneToMany(targetEntity: MageArcanum::class, mappedBy: 'character', orphanRemoval: true)]
  private Collection $arcana;


  public function __construct(Character $character = null)
  {
    $this->arcana = new ArrayCollection();
    if (is_object($character)) {
      // Initializing class properties
      foreach ($character->getProperties() as $property => $value) {
        $this->$property = $value;
      }
    }
    // $this->devotions = new ArrayCollection();
    // $this->rituals = new ArrayCollection();
  }

  public function __clone()
  {
    if ($this->id) {
      parent::__clone();
      // Collections/Arrays, OneToMany
      $this->arcana = $this->cloneCollection($this->arcana);
    }
  }

  public function getType(): string
  {
    return "mage";
  }

  public function getForm(): string
  {
    return MageType::class;
  }

  public function setPowerRating(): self
  {
    $sum = parent::setPowerRating()->getPowerRating();
    $weight = [
      0 => 0,
      1 => 1,
      2 => 3,
      3 => 6,
      4 => 10,
      5 => 15,
      6 => 21,
      7 => 28,
      8 => 36,
      9 => 45,
      10 => 55,
    ];

    // Arcana
    $sum += $this->gnosis * 8;
    foreach ($this->arcana as $arcanum) {
      /** @var MageArcanum $discipline */
      $sum += $weight[$arcanum->getLevel()] * 7;
    }

    $this->powerRating = $sum;
    return $this;
  }

  public function getLimit(): int
  {
    return max($this->limit, $this->gnosis);
  }

  public function getPath(): ?Path
  {
    return $this->path;
  }

  public function setPath(Path $path): self
  {
    $this->path = $path;

    return $this;
  }

  public function getGnosis(): ?int
  {
    return $this->gnosis;
  }

  public function setGnosis(int $gnosis): self
  {
    $this->gnosis = $gnosis;

    return $this;
  }

  public function getMaxMana(): int
  {
    if (!is_null($this->getChronicle()) && !is_null($this->getChronicle()->getRules('mage'))) {

      return $this->getChronicle()->getRules('mage')['maxMana'][$this->gnosis];
    }
    switch ($this->gnosis) {
      case 10:
        return 100;
      case 9:
        return 50;
      case 8:
        return 30;
      case 7:
        return 20;
      default:
        return $this->gnosis + 9;
    }
  }

  public function getMaxManaPerTurn(): int
  {
    if (!is_null($this->getChronicle()) && !is_null($this->getChronicle()->getRules('mage'))) {

      return $this->getChronicle()->getRules('mage')['maxManaPerTurn'][$this->gnosis];
    }
    switch ($this->gnosis) {
      case 10:
        return 15;
      case 9:
        return 10;
      case 8:
        return 7;
      case 7:
        return 5;
      case 5:
        return 3;
      default:
        return intval(max(1, round($this->gnosis / 2, 0, PHP_ROUND_HALF_DOWN)));
    }
  }

  public function getMana(): ?int
  {
    return $this->mana;
  }

  public function setMana(int $mana): self
  {
    $this->mana = $mana;

    return $this;
  }


  public function getOrder(): ?MageOrder
  {
    return $this->order;
  }

  public function setOrder(?MageOrder $order): static
  {
    $this->order = $order;

    return $this;
  }

  public function getMainOrganization(): ?Organization
  {
    return $this->order;
  }

  /**
   * @return Collection<int, MageArcanum>
   */
  public function getArcana(): Collection
  {
    return $this->arcana;
  }

  public function addArcanum(MageArcanum $arcanum): static
  {
    if (!$this->arcana->contains($arcanum)) {
      $this->arcana->add($arcanum);
      $arcanum->setCharacter($this);
    }

    return $this;
  }

  public function removeArcanum(MageArcanum $arcanum): static
  {
    if ($this->arcana->removeElement($arcanum)) {
      // set the owning side to null (unless already changed)
      if ($arcanum->getCharacter() === $this) {
        $arcanum->setCharacter(null);
      }
    }

    return $this;
  }

  public function getArcanum(int $id): ?MageArcanum
  {
    foreach ($this->arcana as $chArcanum) {
      /** @var MageArcanum $chArcanum */
      if ($chArcanum->getArcanum()->getId() === $id) {

        return $chArcanum;
      }
    }

    return null;
  }

  public function hasArcanum(int $id): bool
  {
    foreach ($this->arcana as $chArcanum) {
      /** @var MageArcanum $chArcanum */
      if ($chArcanum->getArcanum()->getId() === $id) {

        return true;
      }
    }

    return false;
  }

  public function isRulingArcanum(Arcanum $arcanum): bool
  {
    // IF RULING FOR LEGACY
    // RETURN TRUE

    foreach ($this->path->getRulingArcana() as $pathArcanum) {
      /** @var MageArcanum $chArcanum */
      if ($pathArcanum === $arcanum) {

        return true;
      }
    }

    return false;
  }

  public function maxArcanumMastery(Arcanum $arcanum)
  {
    // TODO Maybe ?
    // if (!is_null($this->getChronicle()) && !is_null($this->getChronicle()->getRules('mage'))) {

    //   return $this->getChronicle()->getRules('mage')['maxManaPerTurn'][$this->gnosis];
    // }

    // TODO should calculate the current number of arcana owned to define the max
    switch ($this->gnosis) {
      case 1:
        return 3;
      case 2:
        return 4;
      case 3:
        return 5;
      case 4:
        return 5;
      default:
        return $this->gnosis;
    }
  }
}
