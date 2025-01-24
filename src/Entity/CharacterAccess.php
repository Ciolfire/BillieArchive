<?php

namespace App\Entity;

use App\Repository\CharacterAccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CharacterAccessRepository::class)]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
class CharacterAccess
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\ManyToOne(inversedBy: 'characterAccesses')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Character $target = null;

  #[ORM\ManyToOne(inversedBy: 'peekingRights')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Character $accessor = null;

  #[ORM\Column]
  private array $rights = [];

  #[ORM\Column(type: "smallint")]
  private int $importance = 1;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTarget(): ?Character
  {
    return $this->target;
  }

  public function setTarget(?Character $target): static
  {
    $this->target = $target;

    return $this;
  }

  public function getAccessor(): ?Character
  {
    return $this->accessor;
  }

  public function setAccessor(?Character $accessor): static
  {
    $this->accessor = $accessor;

    return $this;
  }

  public function getRights(): array
  {
    return $this->rights;
  }

  public function setRights(array $rights): static
  {
    $this->rights = $rights;

    return $this;
  }

  public function hasRight(string $right): bool
  {
    if (in_array($right, $this->rights)) {

      return true;
    }

    return false;
  }

  public function getImportance(): int
  {
    return $this->importance;
  }

  public function setImportance(int $importance): self
  {
    $this->importance = $importance;

    return $this;
  }
}
