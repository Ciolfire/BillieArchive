<?php

namespace App\Entity;

use App\Repository\BloodBathFacetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BloodBathFacetRepository::class)]
class BloodBathFacet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $modifier = null;

    #[ORM\Column(length: 255)]
    private ?string $facet = null;

  public function __toString()
  {
    return $this->label;
  }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getModifier(): ?string
    {
        return $this->modifier;
    }

    public function setModifier(string $modifier): static
    {
        $this->modifier = $modifier;

        return $this;
    }

    public function getFacet(): ?string
    {
        return $this->facet;
    }

    public function setFacet(string $facet): static
    {
        $this->facet = $facet;

        return $this;
    }
}
