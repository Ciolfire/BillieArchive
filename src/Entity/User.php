<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @var int|null
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=180, unique=true)
   * @var string|null
   */
  private $username;

  /**
   * @ORM\Column(type="string", length=180, unique=true)
   * @var string|null
   */
  private $email;

  /**
   * @ORM\Column(type="string", length=10)
   * @var string|null
   */
  private $locale = "en";

  /**
   * @ORM\Column(type="json")
   */
  private $roles = [];

  /**
   * @var string|null The hashed password
   * @ORM\Column(type="string")
   */
  private $password;

  /**
   * @ORM\Column(type="boolean")
   * @var bool|null
   */
  private $isVerified = false;

  /**
   * @ORM\OneToMany(targetEntity=Character::class, mappedBy="player")
   * @var \Doctrine\Common\Collections\Collection<\App\Entity\Character>
   */
  private $characters;

  /**
   * @ORM\ManyToMany(targetEntity=Chronicle::class, inversedBy="players")
   * @var \Doctrine\Common\Collections\Collection<\App\Entity\Chronicle>
   */
  private $chronicles;

  /**
   * @ORM\OneToMany(targetEntity=Chronicle::class, mappedBy="storyteller")
   * @var \Doctrine\Common\Collections\Collection<\App\Entity\Chronicle>
   */
  private $stories;

  public function __construct()
  {
    $this->characters = new ArrayCollection();
    $this->chronicles = new ArrayCollection();
    $this->stories = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->username;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * @deprecated since Symfony 5.3, use getUserIdentifier instead
   */
  public function getUsername(): string
  {
    return (string) $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  public function getEmail(): string
  {
    return (string) $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getLocale(): string
  {
    return (string) $this->locale;
  }

  public function setLocale(string $locale): self
  {
    $this->locale = $locale;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->username;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_PLAYER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;

    return $this;
  }

  public function getRole(): string
  {
    $roles = ['ROLE_GM', 'ROLE_ST', 'ROLE_PLAYER'];

    foreach ($roles as $role) {
      if (in_array($role, $this->roles)) {
        return $role;
      }
    }

    return 'ROLE_PLAYER';
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * Returning a salt is only needed, if you are not using a modern
   * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
   *
   * @see UserInterface
   */
  public function getSalt(): ?string
  {
    return null;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function isVerified(): bool
  {
    return $this->isVerified;
  }

  public function setIsVerified(bool $isVerified): self
  {
    $this->isVerified = $isVerified;

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
      $character->setPlayer($this);
    }

    return $this;
  }

  public function removeCharacter(Character $character): self
  {
    if ($this->characters->removeElement($character)) {
      // set the owning side to null (unless already changed)
      if ($character->getPlayer() === $this) {
        $character->setPlayer(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|Chronicle[]
   */
  public function getChronicles(): Collection
  {
    return $this->chronicles;
  }

  public function addChronicle(Chronicle $chronicle): self
  {
    if (!$this->chronicles->contains($chronicle)) {
      $this->chronicles[] = $chronicle;
    }

    return $this;
  }

  public function removeChronicle(Chronicle $chronicle): self
  {
    $this->chronicles->removeElement($chronicle);

    return $this;
  }

  /**
   * @return Collection|Chronicle[]
   */
  public function getStories(): Collection
  {
    return $this->stories;
  }

  public function addStory(Chronicle $story): self
  {
    if (!$this->stories->contains($story)) {
      $this->stories[] = $story;
      $story->setStoryteller($this);
    }

    return $this;
  }

  public function removeStory(Chronicle $story): self
  {
    if ($this->stories->removeElement($story)) {
      // set the owning side to null (unless already changed)
      if ($story->getStoryteller() === $this) {
        $story->setStoryteller(null);
      }
    }

    return $this;
  }
}
