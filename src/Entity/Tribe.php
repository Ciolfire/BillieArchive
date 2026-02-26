<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;

use App\Repository\TribeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: TribeRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "tribes"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "tribes")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\TribeTranslation")]
class Tribe implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 50)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $short = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $emblem = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 50)]
  private ?string $nickname = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $quote = null;

  /**
   * @var Collection<int, GiftList>
   */
  #[ORM\ManyToMany(targetEntity: GiftList::class)]
  private Collection $gifts;

  /**
   * @var Collection<int, Werewolf>
   */
  #[ORM\OneToMany(targetEntity: Werewolf::class, mappedBy: 'tribe')]
  private Collection $werewolves;

  #[ORM\ManyToOne]
  private ?Renown $renown = null;

  public function __construct()
  {
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

  public function getNickname(): ?string
  {
    return $this->nickname;
  }

  public function setNickname(string $nickname): static
  {
    $this->nickname = $nickname;

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
          $werewolf->setTribe($this);
      }

      return $this;
  }

  public function removeWerewolf(Werewolf $werewolf): static
  {
      if ($this->werewolves->removeElement($werewolf)) {
          // set the owning side to null (unless already changed)
          if ($werewolf->getTribe() === $this) {
              $werewolf->setTribe(null);
          }
      }

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
}
