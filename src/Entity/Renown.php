<?php

namespace App\Entity;

use App\Entity\Traits\Sourcable;

use App\Repository\RenownRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: RenownRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "renowns")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\TribeTranslation")]
class Renown implements Translatable
{
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 10)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  /**
   * @var Collection<int, Auspice>
   */
  #[ORM\OneToMany(targetEntity: Auspice::class, mappedBy: 'renown')]
  private Collection $auspices;

  public function __construct()
  {
    $this->auspices = new ArrayCollection();
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

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    if (empty($this->description)) {
      $this->description = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  /**
   * @return Collection<int, Auspice>
   */
  public function getAuspices(): Collection
  {
    return $this->auspices;
  }

  public function addAuspice(Auspice $auspice): static
  {
    if (!$this->auspices->contains($auspice)) {
      $this->auspices->add($auspice);
      $auspice->setRenown($this);
    }

    return $this;
  }

  public function removeAuspice(Auspice $auspice): static
  {
    if ($this->auspices->removeElement($auspice)) {
      // set the owning side to null (unless already changed)
      if ($auspice->getRenown() === $this) {
        $auspice->setRenown(null);
      }
    }

    return $this;
  }
}
