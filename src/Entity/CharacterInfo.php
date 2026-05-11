<?php

namespace App\Entity;

use App\Repository\CharacterInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterInfoRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class CharacterInfo
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'infos')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Character $character = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $data = null;

  #[ORM\ManyToMany(targetEntity: Character::class, inversedBy: 'infoAccesses')]
  // #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $accessList;

  #[ORM\Column(length: 255)]
  private ?string $title = null;

  #[ORM\ManyToOne]
  private ?user $creator = null;

  public function __construct()
  {
    $this->accessList = new ArrayCollection();
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

  public function getData(): ?string
  {
    return $this->data;
  }

  public function setData(string $data): static
  {
    $this->data = $data;

    return $this;
  }

  /**
   * @return Collection<int, Character>
   */
  public function getAccessList(): Collection
  {
    return $this->accessList;
  }

  public function addAccessList(Character $character): static
  {
    if (!$this->accessList->contains($character)) {
      $this->accessList->add($character);
    }

    return $this;
  }

  public function removeAccessList(Character $character): static
  {
    $this->accessList->removeElement($character);

    return $this;
  }

  public function hasAccess(Character $character): bool
  {
    if ($this->accessList->contains($character)) {

      return true;
    }

    return false;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): static
  {
    $this->title = $title;

    return $this;
  }

  public function getCreator(): ?user
  {
      return $this->creator;
  }

  public function setCreator(?user $creator): static
  {
      $this->creator = $creator;

      return $this;
  }
}
