<?php

namespace App\Entity;

use App\Repository\CharacterDerangementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterDerangementRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class CharacterDerangement
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'derangements')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Character $character = null;

  #[ORM\Column(length: 50, nullable: true)]
  private ?string $details = null;

  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?int $moralityLink = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?Derangement $derangement = null;

  public function __construct(Character $character, ?string $details, ?int $moralityLink, Derangement $derangement)
  {
    $this->character = $character;
    $this->details = $details;
    $this->moralityLink = $moralityLink;
    $this->derangement = $derangement;
  }

  public function __toString()
  {
    return $this->derangement->getName();
  }


  public function getId(): ?int
  {
    return $this->id;
  }

  public function getCharacter(): ?Character
  {
    return $this->character;
  }

  public function setCharacter(?Character $character): static
  {
    $this->character = $character;

    return $this;
  }

  public function getDetails(): ?string
  {
    return $this->details;
  }

  public function setDetails(?string $details): static
  {
    $this->details = $details;

    return $this;
  }

  public function getMoralityLink(): ?int
  {
    return $this->moralityLink;
  }

  public function setMoralityLink(?int $moralityLink): static
  {
    $this->moralityLink = $moralityLink;

    return $this;
  }

  public function getDerangement(): ?Derangement
  {
    return $this->derangement;
  }

  public function setDerangement(?Derangement $derangement): static
  {
    $this->derangement = $derangement;

    return $this;
  }

  public function getName(): string
  {
    return $this->getDerangement()->getName();
  }
}
