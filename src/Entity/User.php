<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[UniqueEntity("username")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: 'string', length: 180, unique: true)]
  private string $username;

  #[ORM\Column(type: 'string', length: 180, unique: true)]
  private string $email;

  #[ORM\Column(type: 'string', length: 10)]
  private string $locale = "en";

  /** @var array<string> $roles */
  #[ORM\Column(type: 'json')]
  private array $roles = [];

  #[ORM\Column(type: 'string')]
  private string $password;

  #[ORM\Column(type: 'boolean')]
  private bool $isVerified = false;

  #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'player')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $characters;

  #[ORM\ManyToMany(targetEntity: Chronicle::class, inversedBy: 'players')]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $chronicles;

  #[ORM\OneToMany(targetEntity: Chronicle::class, mappedBy: 'storyteller')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $stories;

  #[ORM\OneToMany(mappedBy: 'author', targetEntity: CharacterNote::class, orphanRemoval: true)]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $userCharacterNotes;

  #[ORM\OneToMany(mappedBy: 'user', targetEntity: Note::class)]
  #[ORM\OrderBy(["chronicle" => "DESC", "id" => "DESC"])]
  private Collection $notes;

  #[ORM\Column(nullable: true)]
  private ?array $preferences = null;

  public function __construct()
  {
    $this->characters = new ArrayCollection();
    $this->chronicles = new ArrayCollection();
    $this->stories = new ArrayCollection();
    $this->userCharacterNotes = new ArrayCollection();
    $this->notes = new ArrayCollection();
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

  /**
   * @param array<string> $roles
   */
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
  public function eraseCredentials() : void
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

  public function getCharacters(): Collection
  {
    return $this->characters;
  }

  public function getChroniclesCharacters(): Array
  {
    $chroniclesCharacters = [];
    foreach ($this->characters as $character) {
      /** @var Character $character */
      if ($character->getChronicle() && !$character->isNpc()) {
        $chroniclesCharacters[] = $character;
      }
    }

    return $chroniclesCharacters;
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

  // public function removeStory(Chronicle $story): self
  // {
  //   if ($this->stories->removeElement($story)) {
  //     // set the owning side to null (unless already changed)
  //     if ($story->getStoryteller() === $this) {
  //       $story->setStoryteller(null);
  //     }
  //   }

  //   return $this;
  // }

  /**
   * @return Collection<int, CharacterNote>
   */
  public function getUserCharacterNotes(): Collection
  {
    return $this->userCharacterNotes;
  }

  public function addUserCharacterNote(CharacterNote $userCharacterNotes): self
  {
    if (!$this->userCharacterNotes->contains($userCharacterNotes)) {
      $this->userCharacterNotes->add($userCharacterNotes);
      $userCharacterNotes->setAuthor($this);
    }

    return $this;
  }

  public function removeUserCharacterNote(CharacterNote $userCharacterNotes): self
  {
    if ($this->userCharacterNotes->removeElement($userCharacterNotes)) {
      // set the owning side to null (unless already changed)
      if ($userCharacterNotes->getAuthor() === $this) {
        $userCharacterNotes->setAuthor(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Note>
   */
  public function getNotes(): Collection
  {
    return $this->notes;
  }

  public function addNote(Note $note): self
  {
    if (!$this->notes->contains($note)) {
      $this->notes->add($note);
      $note->setUser($this);
    }

    return $this;
  }

  public function removeNote(Note $note): self
  {
    if ($this->notes->removeElement($note)) {
      // set the owning side to null (unless already changed)
      if ($note->getUser() === $this) {
        $note->setUser(null);
      }
    }

    return $this;
  }

  /**
   * @return array<Note>
   */
  public function getChronicleNotes(Chronicle $chronicle) : array
  {
    $notes = [];
    foreach ($this->notes as $note) {
      if ($note->getChronicle() == $chronicle) {
        $notes[] = $note;
      }
    }

    return $notes;
  }

  public function getPreferences(): ?array
  {
    return $this->preferences;
  }

  public function setPreferences(?array $preferences): static
  {
    if (is_array($this->preferences)) {
      $this->preferences = array_merge($this->preferences, $preferences);
    } else {
      $this->preferences = $preferences;
    }

    return $this;
  }
}
