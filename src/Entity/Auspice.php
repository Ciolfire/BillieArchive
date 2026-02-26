<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;

use App\Repository\AuspiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: AuspiceRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "auspices"), new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "auspices")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\AuspiceTranslation")]
class Auspice implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 20)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 40)]
  private ?string $extendedName = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $short = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $emblem = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  /**
   * @var Collection<int, Skill>
   */
  #[ORM\ManyToMany(targetEntity: Skill::class)]
  private Collection $specialtySkills;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 50)]
  private ?string $abilityName = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $ability = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $theChange = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $quote = null;

  #[ORM\ManyToOne(inversedBy: 'auspices')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Renown $renown = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?GiftList $phaseGift = null;

  /**
   * @var Collection<int, GiftList>
   */
  #[ORM\ManyToMany(targetEntity: GiftList::class)]
  private Collection $gifts;

  /**
   * @var Collection<int, Werewolf>
   */
  #[ORM\OneToMany(targetEntity: Werewolf::class, mappedBy: 'auspice')]
  private Collection $werewolves;

  public function __construct()
  {
    $this->specialtySkills = new ArrayCollection();
    $this->gifts = new ArrayCollection();
    $this->werewolves = new ArrayCollection();
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

  public function getExtendedName(): ?string
  {
    return $this->extendedName;
  }

  public function setExtendedName(string $extendedName): static
  {
    $this->extendedName = $extendedName;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): static
  {
    $this->short = $short;

    return $this;
  }

  public function getEmblem(): ?string
  {
    return $this->emblem;
  }

  public function setEmblem(?string $emblem): static
  {
    $this->emblem = $emblem;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  /**
   * @return Collection<int, Skill>
   */
  public function getSpecialtySkills(): Collection
  {
    return $this->specialtySkills;
  }

  public function addSpecialtySkill(Skill $specialtySkill): static
  {
    if (!$this->specialtySkills->contains($specialtySkill)) {
      $this->specialtySkills->add($specialtySkill);
    }

    return $this;
  }

  public function removeSpecialtySkill(Skill $specialtySkill): static
  {
    $this->specialtySkills->removeElement($specialtySkill);

    return $this;
  }

  public function getAbilityName(): ?string
  {
    return $this->abilityName;
  }

  public function setAbilityName(string $abilityName): static
  {
    $this->abilityName = $abilityName;

    return $this;
  }

  public function getAbility(): ?string
  {
    return $this->ability;
  }

  public function setAbility(string $ability): static
  {
    $this->ability = $ability;

    return $this;
  }

  public function getTheChange(): ?string
  {
    return $this->theChange;
  }

  public function setTheChange(string $theChange): static
  {
    if ($this->theChange == "") {
      $this->theChange = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $theChange);
    } else {
      $this->theChange = $theChange;
    }

    return $this;
  }

  public function getQuote(): ?string
  {
    return $this->quote;
  }

  public function setQuote(?string $quote): static
  {
    $this->quote = $quote;

    return $this;
  }

  public function getRenown(): ?Renown
  {
    return $this->renown;
  }

  public function setRenown(?Renown $renown): static
  {
    $this->renown = $renown;

    return $this;
  }

  public function getPhaseGift(): ?GiftList
  {
    return $this->phaseGift;
  }

  public function setPhaseGift(?GiftList $phaseGift): static
  {
    $this->phaseGift = $phaseGift;

    return $this;
  }

  /**
   * @return Collection<int, GiftList>
   */
  public function getGifts(): Collection
  {
    return $this->gifts;
  }

  public function addGift(GiftList $gift): static
  {
    if (!$this->gifts->contains($gift)) {
      $this->gifts->add($gift);
    }

    return $this;
  }

  public function removeGift(GiftList $gift): static
  {
    $this->gifts->removeElement($gift);

    return $this;
  }

  /**
   * @return Collection<int, Werewolf>
   */
  public function getWerewolves(): Collection
  {
    return $this->werewolves;
  }

  public function addWerewolf(Werewolf $werewolf): static
  {
    if (!$this->werewolves->contains($werewolf)) {
      $this->werewolves->add($werewolf);
      $werewolf->setAuspice($this);
    }

    return $this;
  }

  public function removeWerewolf(Werewolf $werewolf): static
  {
    if ($this->werewolves->removeElement($werewolf)) {
      // set the owning side to null (unless already changed)
      if ($werewolf->getAuspice() === $this) {
        $werewolf->setAuspice(null);
      }
    }

    return $this;
  }
}
