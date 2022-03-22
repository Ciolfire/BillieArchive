<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="characters")
 */
class Character
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=50, nullable=true)
   */
  private $name;

  /**
   * @ORM\Column(type="integer", nullable=true, options={"unsigned":true})
   */
  private $age;

  /**
   * @ORM\Column(type="string", length=25, nullable=true)
   */
  private $player;

  /**
   * @ORM\Column(type="string", length=50, nullable=true)
   */
  private $concept;

  /**
   * @ORM\Column(type="string", length=25, nullable=true)
   */
  private $chronicle;

  /**
   * @ORM\Column(type="string", length=25, nullable=true)
   */
  private $faction;

  /**
   * @ORM\Column(type="string", length=25, nullable=true)
   */
  private $groupName;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $intelligence = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $wits = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $resolve = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $strength = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $dexterity = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $stamina = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $presence = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $manipulation = 1;

  /**
   * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
   */
  private $composure = 1;

  /**
   * @ORM\OneToMany(targetEntity=Specialty::class, mappedBy="character", orphanRemoval=true, cascade={"persist"})
   */
  private $specialties;

  /**
   * @ORM\Column(type="smallint")
   */
  private $willpower;

  /**
   * @ORM\ManyToOne(targetEntity=Virtue::class)
   */
  private $virtue;

  /**
   * @ORM\ManyToOne(targetEntity=Vice::class)
   */
  private $vice;

  /**
   * @ORM\Column(type="smallint")
   */
  private $moral = 7;

  /**
   * @ORM\OneToOne(targetEntity=CharacterSkills::class, cascade={"persist", "remove"})
   */
  private $skills;

  /**
   * @ORM\Column(type="json", nullable=true)
   */
  private $wounds = ['B' => 0, 'L' => 0, 'A' => 0];

  /**
   * @ORM\Column(type="smallint")
   */
  private $size = 5;

  /**
   * @ORM\Column(type="smallint")
   */
  private $currentWillpower;

  /**
   * @ORM\OneToMany(targetEntity=CharacterMerit::class, mappedBy="character", orphanRemoval=true)
   */
  private $merits;

  /**
   * @ORM\Column(type="smallint")
   */
  private $xpTotal = 0;

  /**
   * @ORM\Column(type="smallint")
   */
  private $xpUsed = 0;

  public function __construct()
  {
    $this->willpower = $this->composure + $this->resolve;
    $this->currentWillpower = $this->willpower;
    $this->specialties = new ArrayCollection();
    $this->merits = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
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

  public function getAge(): ?int
  {
    return $this->age;
  }

  public function setAge(int $age): self
  {
    $this->age = $age;

    return $this;
  }

  public function getPlayer(): ?string
  {
    return $this->player;
  }

  public function setPlayer(string $player): self
  {
    $this->player = $player;

    return $this;
  }

  public function getConcept(): ?string
  {
    return $this->concept;
  }

  public function setConcept(string $concept): self
  {
    $this->concept = $concept;

    return $this;
  }

  public function getChronicle(): ?string
  {
    return $this->chronicle;
  }

  public function setChronicle(?string $chronicle): self
  {
    $this->chronicle = $chronicle;

    return $this;
  }

  public function getFaction(): ?string
  {
    return $this->faction;
  }

  public function setFaction(?string $faction): self
  {
    $this->faction = $faction;

    return $this;
  }

  public function getGroupName(): ?string
  {
    return $this->groupName;
  }

  public function setGroupName(?string $groupName): self
  {
    $this->groupName = $groupName;

    return $this;
  }

  public function getIntelligence(): ?int
  {
    return $this->intelligence;
  }

  public function setIntelligence(int $intelligence): self
  {
    $this->intelligence = $intelligence;

    return $this;
  }

  public function getWits(): ?int
  {
    return $this->wits;
  }

  public function setWits(int $wits): self
  {
    $this->wits = $wits;

    return $this;
  }

  public function getResolve(): ?int
  {
    return $this->resolve;
  }

  public function setResolve(int $resolve): self
  {
    $this->resolve = $resolve;

    return $this;
  }

  public function getStrength(): ?int
  {
    return $this->strength;
  }

  public function setStrength(int $strength): self
  {
    $this->strength = $strength;

    return $this;
  }

  public function getDexterity(): ?int
  {
    return $this->dexterity;
  }

  public function setDexterity(int $dexterity): self
  {
    $this->dexterity = $dexterity;

    return $this;
  }

  public function getStamina(): ?int
  {
    return $this->stamina;
  }

  public function setStamina(int $stamina): self
  {
    $this->stamina = $stamina;

    return $this;
  }

  public function getPresence(): ?int
  {
    return $this->presence;
  }

  public function setPresence(int $presence): self
  {
    $this->presence = $presence;

    return $this;
  }

  public function getManipulation(): ?int
  {
    return $this->manipulation;
  }

  public function setManipulation(int $manipulation): self
  {
    $this->manipulation = $manipulation;

    return $this;
  }

  public function getComposure(): ?int
  {
    return $this->composure;
  }

  public function setComposure(int $composure): self
  {
    $this->composure = $composure;

    return $this;
  }

  /**
   * @return Collection|Specialty[]
   */
  public function getSpecialties(): Collection
  {
    return $this->specialties;
  }

  /**
   * @return array|Specialty[]
  */
  public function getSkillSpecialties($filter): array
  {
    $result = [];
    foreach ($this->specialties as $specialty) {
      /** @var Specialty $specialty */
      if ($filter == $specialty->getSkill()->getName()) {
        $result[] = $specialty;
      }
    }

    return $result;
  }

  public function addSpecialty(Specialty $specialty): self
  {
    if (!$this->specialties->contains($specialty)) {
      $this->specialties[] = $specialty;
    }

    return $this;
  }

  public function removeSpecialty(Specialty $specialty): self
  {
    if ($this->specialties->removeElement($specialty)) {
    }

    return $this;
  }

  public function getWillpower(): ?int
  {
    return $this->willpower;
  }

  public function setWillpower(int $willpower): self
  {
    $this->willpower = $willpower;

    return $this;
  }

  public function getVirtue(): ?Virtue
  {
    return $this->virtue;
  }

  public function setVirtue(?Virtue $virtue): self
  {
    $this->virtue = $virtue;

    return $this;
  }

  public function getVice(): ?Vice
  {
    return $this->vice;
  }

  public function setVice(?Vice $vice): self
  {
    $this->vice = $vice;

    return $this;
  }

  public function getMoral(): ?int
  {
    return $this->moral;
  }

  public function setMoral(int $moral): self
  {
    $this->moral = $moral;

    return $this;
  }

  public function getSkills(): ?CharacterSkills
  {
    return $this->skills;
  }

  public function setSkills(?CharacterSkills $skills): self
  {
    $this->skills = $skills;

    return $this;
  }

  public function getWounds(): array
  {
    if (!$this->wounds) {
      $this->wounds = ['B' => 0, 'L' => 0, 'A' => 0];
    }

    return $this->wounds;
  }

  public function setWounds(array $wounds): self
  {
    $this->wounds = $wounds;

    return $this;
  }

  public function getSize(): ?int
  {
    return $this->size;
  }

  public function setSize(int $size): self
  {
    $this->size = $size;

    return $this;
  }

  public function getHealth(): ?int
  {
    return $this->size + $this->stamina;
  }

  public function getCurrentWillpower(): ?int
  {
    return $this->currentWillpower;
  }

  public function getSpeed(): ?int
  {
    return $this->strength + $this->dexterity + 5;
  }

  public function setCurrentWillpower(int $currentWillpower): self
  {
    $this->currentWillpower = $currentWillpower;

    return $this;
  }

  public function getInitiative(): int
  {
    $bonus = 0;

    return $this->dexterity + $this->composure + $bonus;
  }

  public function getDefense(): int
  {

    return min($this->dexterity, $this->wits);
  }

  /**
   * @return Collection|CharacterMerit[]
   */
  public function getMerits(): Collection
  {
      return $this->merits;
  }

  public function addMerit(CharacterMerit $merit): self
  {
      if (!$this->merits->contains($merit)) {
          $this->merits[] = $merit;
          $merit->setCharacter($this);
      }

      return $this;
  }

  public function removeMerit(CharacterMerit $merit): self
  {
      if ($this->merits->removeElement($merit)) {
          // set the owning side to null (unless already changed)
          if ($merit->getCharacter() === $this) {
              $merit->setCharacter(null);
          }
      }

      return $this;
  }

  public function hasMerit(int $id): bool
  {
    foreach ($this->merits as $merit) {
      /** @var CharacterMerit $merit */
      if ($merit->getMerit()->getId() == $id) {

        return true;
      }
    }

    return false;
  }

  public function getXpTotal(): ?int
  {
      return $this->xpTotal;
  }

  public function setXpTotal(int $xpTotal): self
  {
      $this->xpTotal = $xpTotal;

      return $this;
  }

  public function getXpUsed(): ?int
  {
      return $this->xpUsed;
  }

  public function setXpUsed(int $xpUsed): self
  {
      $this->xpUsed = $xpUsed;

      return $this;
  }

  public function spendXp(int $spent): self
  {
      $this->xpUsed += $spent;

      return $this;
  }
}
