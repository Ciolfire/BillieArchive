<?php

namespace App\Entity;

use App\Repository\CharacterMeritRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterMeritRepository::class)
 */
class CharacterMerit
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity=Merit::class)
   * @ORM\JoinColumn(nullable=false)
   */
  private $merit;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $choice;

  /**
   * @ORM\Column(type="json", nullable=true)
   */
  private $details = [];

  /**
   * @ORM\Column(type="smallint")
   */
  private $level;

  /**
   * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="merits")
   * @ORM\JoinColumn(nullable=false)
   */
  private $character;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getMerit(): ?Merit
  {
    return $this->merit;
  }

  public function setMerit(?Merit $merit): self
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

  public function getDetails(): ?array
  {
    return $this->details;
  }

  public function setDetails(?array $details): self
  {
    $this->details = $details;

    return $this;
  }

  public function getLevel(): ?int
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
