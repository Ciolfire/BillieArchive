<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\CharacterMeritRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CharacterMeritRepository::class)]
class CharacterMerit
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private ?int $id;

  #[ORM\ManyToOne(targetEntity: Merit::class)]
  #[ORM\JoinColumn(nullable: false)]
  private Merit $merit;

  #[ORM\Column(type: "string", length: 255, nullable: true)]
  private ?string $choice;

  /** @var array<string> */
  #[ORM\Column(type: "json", nullable: true)]
  private ?array $details = [];

  #[ORM\Column(type: "smallint")]
  private int $level;

  #[ORM\ManyToOne(targetEntity: Character::class, inversedBy: "merits", cascade: ["persist"])]
  #[ORM\JoinColumn(nullable: false)]
  private ?Character $character;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    if (is_null($this->choice)) {
      return "{$this->merit->getName()} — {$this->level}";
    }

    return "{$this->merit->getName()} ({$this->choice}) — {$this->level}";
  }

  public function getMeritName(): ?string
  {
    if (is_null($this->choice)) {
      return "{$this->merit->getName()}";
    }

    return "{$this->merit->getName()} ({$this->choice})";
  }

  public function getMerit(): ?Merit
  {
    return $this->merit;
  }

  public function setMerit(Merit $merit): self
  {
    $this->merit = $merit;

    return $this;
  }

  public function getChoice(): ?string
  {
    return $this->choice;
  }

  public function setChoice(?string $choice): self
  {
    $this->choice = $choice;

    return $this;
  }

  /**
   * @return array<string>
   */
  public function getDetails(): ?array
  {
    return $this->details;
  }

  /**
   * @param array<string> $details
   */
  public function setDetails(?array $details): self
  {
    $this->details = $details;

    return $this;
  }

  public function getLevel(): int
  {
    return $this->level;
  }

  public function setLevel(int $level): self
  {
    $this->level = $level;

    return $this;
  }

  public function getCharacter(): ?Character
  {
    return $this->character;
  }

  public function setCharacter(?Character $character): self
  {
    $this->character = $character;

    return $this;
  }
}
