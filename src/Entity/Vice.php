<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ViceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: ViceRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\ViceTranslation")]
class Vice implements Translatable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private int $id;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 12)]
  private string $name;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private string $details;

  /**
   * @var Collection<int, PossessedVestment>
   */
  #[ORM\OneToMany(targetEntity: PossessedVestment::class, mappedBy: 'vice')]
  #[ORM\OrderBy(["level" => "ASC", "name" => "ASC"])]
  private Collection $possessedVestments;

  #[ORM\Column(length: 255)]
  private ?string $otherNames = null;

  public function __construct()
  {
      $this->possessedVestments = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function __toString(): string
  {
    return $this->name;
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

  public function getDetails(): ?string
  {
    return $this->details;
  }

  public function setDetails(string $details): self
  {
    if ($this->details == "") {
      $this->details = preg_replace("/(?<!(\n|  ))\n(?!\n)/m", " ", $details);
    } else {
      $this->details = $details;
    }

    return $this;
  }

  /**
   * @return Collection<int, PossessedVestment>
   */
  public function getPossessedVestments(): Collection
  {
      return $this->possessedVestments;
  }

  public function addPossessedVestment(PossessedVestment $possessedVestment): static
  {
      if (!$this->possessedVestments->contains($possessedVestment)) {
          $this->possessedVestments->add($possessedVestment);
          $possessedVestment->setVice($this);
      }

      return $this;
  }

  public function removePossessedVestment(PossessedVestment $possessedVestment): static
  {
      if ($this->possessedVestments->removeElement($possessedVestment)) {
          // set the owning side to null (unless already changed)
          if ($possessedVestment->getVice() === $this) {
              $possessedVestment->setVice(null);
          }
      }

      return $this;
  }

  public function getOtherNames(): ?string
  {
      return $this->otherNames;
  }

  public function setOtherNames(string $otherNames): static
  {
      $this->otherNames = $otherNames;

      return $this;
  }
}
