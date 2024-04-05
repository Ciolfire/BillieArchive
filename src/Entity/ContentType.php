<?php

namespace App\Entity;

use App\Repository\ContentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentTypeRepository::class)]
class ContentType
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 50)]
  private ?string $name = null;

  #[ORM\OneToMany(mappedBy: 'type', targetEntity: Merit::class)]
  private Collection $merits;

  public function __construct()
  {
      $this->merits = new ArrayCollection();
  }

  public function __toString()
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

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection<int, Merit>
   */
  public function getMerits(): Collection
  {
      return $this->merits;
  }

  public function addMerit(Merit $merit): static
  {
      if (!$this->merits->contains($merit)) {
          $this->merits->add($merit);
          $merit->setType($this);
      }

      return $this;
  }

  public function removeMerit(Merit $merit): static
  {
      if ($this->merits->removeElement($merit)) {
          // set the owning side to null (unless already changed)
          if ($merit->getType() === $this) {
              $merit->setType(null);
          }
      }

      return $this;
  }
}
