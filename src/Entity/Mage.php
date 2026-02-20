<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MageRepository;
use App\Form\Mage\MageForm;

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
  #[ORM\OrderBy(["level" => "DESC"])]
  private Collection $arcana;

  /**
   * @var Collection<int, SpellRote>
   */
  #[ORM\ManyToMany(targetEntity: SpellRote::class)]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $rotes;

  /**
   * @var Collection<int, SpellRote>
   */
  #[ORM\OneToMany(targetEntity: SpellRote::class, mappedBy: 'creator')]
  #[ORM\OrderBy(["name" => "ASC"])]
  private Collection $createdRotes;

  #[ORM\ManyToOne]
  private ?Legacy $legacy = null;

  #[ORM\Column]
  private ?bool $hasOwnLegacy = null;


  public function __construct(bool $isAncient = false, bool $isNpc = false, ?Chronicle $chronicle = null)
  {
    parent::__construct(isAncient: $isAncient, isNpc: $isNpc, chronicle: $chronicle);

    $this->arcana = new ArrayCollection();
    // $this->devotions = new ArrayCollection();
    // $this->rituals = new ArrayCollection();
    $this->rotes = new ArrayCollection();
    $this->createdRotes = new ArrayCollection();
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
    return MageForm::class;
  }

  public function setPowerRating(): self
  {
    // Human + template
    $sum = parent::setPowerRating()->getPowerRating() + 50;

    // Arcana
    $sum += $this->gnosis * 8;
    foreach ($this->arcana as $arcanum) {
      /** @var MageArcanum $discipline */
      $sum += $this->weightPower[$arcanum->getLevel()] * 7;
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

  public function getArcanaLevel(): array
  {
    $arcana = [];
    foreach ($this->arcana as $arcanum) {
      $arcana[$arcanum->getArcanum()->getId()] = $arcanum->getLevel();
    }

    return $arcana;
  }

  public function getArcanumLevel($find): int
  {
    foreach ($this->arcana as $arcanum) {
      if ($arcanum->getIdentifier() == $find) {
        return $arcanum->getLevel();
      }
    }

    return 0;
  }

  public function highestArcanumLevel(): int
  {
    $max = 0;
    foreach ($this->arcana as $arcanum) {
      $max = max($max, $arcanum->getLevel());
    }

    return $max;
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

  /**
   * @return Collection<int, SpellRote>
   */
  public function getRotes(): Collection
  {
    return $this->rotes;
  }

  public function addRote(SpellRote $rote): static
  {
    if (!$this->rotes->contains($rote)) {
      $this->rotes->add($rote);
    }

    return $this;
  }

  public function removeRote(SpellRote $rote): static
  {
    $this->rotes->removeElement($rote);

    return $this;
  }

  public function hasRote(SpellRote $rote): bool
  {
    if ($this->rotes->contains($rote) || $this->createdRotes->contains($rote)) {
      return true;
    }

    return false;
  }


  /**
   * @return Collection<int, SpellRote>
   */
  public function getCreatedRotes(): Collection
  {
    return $this->createdRotes;
  }

  public function addCreatedRote(SpellRote $createdRote): static
  {
    if (!$this->createdRotes->contains($createdRote)) {
      $this->createdRotes->add($createdRote);
      $createdRote->setCreator($this);
    }

    return $this;
  }

  public function removeCreatedRote(SpellRote $createdRote): static
  {
    if ($this->createdRotes->removeElement($createdRote)) {
      // set the owning side to null (unless already changed)
      if ($createdRote->getCreator() === $this) {
        $createdRote->setCreator(null);
      }
    }

    return $this;
  }

  /**
   * @param array<string, int> $modifiers
   * @return array<string, mixed>
   */
  public function spellDicePool(SpellRote $rote): array
  {
    $details = [
      'total' => 0,
      'string' => '',
      'modifiers' => [],
    ];

    // Arcanum
    $value = 0;
    foreach ($rote->getSpell()->getArcana() as $spellArcanum) {
      if (!$spellArcanum->isOptional() && $this->hasArcanum($spellArcanum->getArcanum()->getId())) {
        $level = $this->getArcanum($spellArcanum->getArcanum()->getId())->getLevel();
        if ($level > $value) {
          $value = $level;
          $id = $spellArcanum->getName();
        }
      }
    }
    if (isset($id)) {
      $details['arcanum'] = $id;
      $details['total'] += $value;
      $details['string'] .= " {$id} {$value}";
    }

    // Attribute
    $attribute = $rote->getAttribute();
    $value = $this->attributes->get($attribute->getIdentifier());

    $details[$attribute->getIdentifier()] = $value;
    $details['total'] += $value;
    $details['string'] .= " {$attribute->getName()} {$value}";

    // Skill
    $skill = $rote->getSpell()->getSkill();
    $value = $this->skills->get($skill->getIdentifier());
    if ($value == 0) {
      if ($skill->getCategory() == "mental") {
        $value = -3;
      } else {
        $value = -1;
      }
    }
    $details[$skill->getIdentifier()] = $value;
    $details['total'] += $value;
    $details['string'] .= " {$skill->getName()} {$value}";

    return $details;
  }

  public function getLegacy(): ?Legacy
  {
    return $this->legacy;
  }

  public function setLegacy(?Legacy $legacy): static
  {
    $this->legacy = $legacy;

    return $this;
  }

  public function hasOwnLegacy(): ?bool
  {
    return $this->hasOwnLegacy;
  }

  public function setHasOwnLegacy(bool $hasOwnLegacy): static
  {
    $this->hasOwnLegacy = $hasOwnLegacy;

    return $this;
  }

  public function getAttainments(): ?array
  {
    if ($this->legacy) {
      $attainments = [];
      $base = $this->gnosis - 1;
      if ($this->hasOwnLegacy()) {
        $base-= 1;
      }
      $base/=2;
      foreach ($this->legacy->getAttainments() as $attainment) {
        // 1 = 3/4, 2 = 5/6, 3 = 7/8
        if ($attainment->getLevel() <= $base) {
          # 1  2    3  4    5  6   7
          # 0, 0.5, 1, 1.5, 2, 2.5 3
          $attainments[] = $attainment;
        }
      }

      return $attainments;
    }

    return null;
  }
}
