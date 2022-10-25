<?php

namespace App\Entity;

use App\Repository\ChronicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChronicleRepository::class)
 */
class Chronicle
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @var int|null
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @var string|null
   */
  private $name;

  /**
   * @ORM\OneToMany(targetEntity=Character::class, mappedBy="chronicle")
   * @var \Doctrine\Common\Collections\Collection<\App\Entity\Character>
   */
  private $characters;

  /**
   * @ORM\ManyToMany(targetEntity=User::class, mappedBy="chronicles")
   * @var \Doctrine\Common\Collections\Collection<\App\Entity\User>
   */
  private $players;

  /**
   * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stories")
   * @var \App\Entity\User|null
   */
  private $storyteller;

  /**
   * @ORM\Column(type="string", length=50)
   * @var string|null
   */
  private $type;

  public function __construct()
  {
    $this->characters = new ArrayCollection();
    $this->players = new ArrayCollection();
  }

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

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection|Character[]
   */
  public function getCharacters(): Collection
  {
    return $this->characters;
  }

  public function addCharacter(Character $character): self
  {
    if (!$this->characters->contains($character)) {
      $this->characters[] = $character;
      $character->setChronicle($this);
    }

    return $this;
  }

  public function removeCharacter(Character $character): self
  {
    if ($this->characters->removeElement($character)) {
      // set the owning side to null (unless already changed)
      if ($character->getChronicle() === $this) {
        $character->setChronicle(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|User[]
   */
  public function getPlayers(): Collection
  {
    return $this->players;
  }

  public function addPlayer(User $player): self
  {
    if (!$this->players->contains($player)) {
      $this->players[] = $player;
      $player->addChronicle($this);
    }

    return $this;
  }

  public function removePlayer(User $player): self
  {
    if ($this->players->removeElement($player)) {
      $player->removeChronicle($this);
    }

    return $this;
  }

  public function getStoryteller(): ?User
  {
    return $this->storyteller;
  }

  public function setStoryteller(?User $storyteller): self
  {
    $this->storyteller = $storyteller;

    return $this;
  }


  public function getCharacter(User $user): ?Character
  {
    foreach ($this->characters as $character) {
      /** @var Character $character */
      if ($character->getPlayer() == $user) {
        return $character;
      }
    }

    return null;
  }

  public function getNpc(): array
  {
    $npc = [];
    foreach ($this->characters as $character) {
      /** @var Character $character */
      if ($character->isNpc() == true) {
        $npc[] = $character;
      }
    }

    return $npc;
  }

  public function getType(): ?string
  {
      return $this->type;
  }

  public function setType(string $type): self
  {
      $this->type = $type;

      return $this;
  }
}
