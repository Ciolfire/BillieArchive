<?php

namespace App\Entity;

use App\Repository\SocietyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocietyRepository::class)]
class Society
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'societies')]
  #[ORM\OrderBy(["firstName" => "ASC", "id" => "DESC"])]
  private Collection $characters;

  #[ORM\ManyToOne(inversedBy: 'societies')]
  private ?Chronicle $chronicle = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $type = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $setting = null;

  #[ORM\ManyToOne]
  private ?Organization $organization = null;

  public function __construct(?Chronicle $chronicle)
  {
    $this->chronicle = $chronicle;
    $this->characters = new ArrayCollection();
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

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): self
  {
    $this->description = $description;

    return $this;
  }

  /**
   * @return Collection<int, Character>
   */
  public function getCharacters(): Collection
  {
    return $this->characters;
  }

  public function addCharacter(Character $character): self
  {
    if (!$this->characters->contains($character)) {
      $this->characters->add($character);
    }

    return $this;
  }

  public function removeCharacter(Character $character): self
  {
    $this->characters->removeElement($character);

    return $this;
  }

  public function hasCharacter(Character $character)
  {
    if ($this->characters->contains($character)) {
      return true;
    }
    return false;
  }

  public function getChronicle(): ?Chronicle
  {
    return $this->chronicle;
  }

  public function setChronicle(?Chronicle $chronicle): self
  {
    $this->chronicle = $chronicle;

    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(?string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getSetting(): ?string
  {
    return $this->setting;
  }

  public function setSetting(?string $setting): self
  {
    $this->setting = $setting;

    return $this;
  }

  public function getOrganization(): ?Organization
  {
    return $this->organization;
  }

  public function setOrganization(?Organization $organization): static
  {
    $this->organization = $organization;

    return $this;
  }
}
