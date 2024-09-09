<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emblem = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = "";

    #[ORM\Column(type: Types::TEXT)]
    private ?string $overview = "";

    #[ORM\Column(type: Types::TEXT)]
    private ?string $members = "";

    #[ORM\Column(type: Types::TEXT)]
    private ?string $philosophy = "";

    #[ORM\Column(type: Types::TEXT)]
    private ?string $observances = "";

    #[ORM\Column(type: Types::TEXT)]
    private ?string $titles = "";

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

    public function getEmblem(): ?string
    {
        return $this->emblem;
    }

    public function setEmblem(?string $emblem): static
    {
        $this->emblem = $emblem;

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

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): static
    {
        $this->overview = $overview;

        return $this;
    }

    public function getMembers(): ?string
    {
        return $this->members;
    }

    public function setMembers(string $members): static
    {
        $this->members = $members;

        return $this;
    }

    public function getPhilosophy(): ?string
    {
        return $this->philosophy;
    }

    public function setPhilosophy(string $philosophy): static
    {
        $this->philosophy = $philosophy;

        return $this;
    }

    public function getObservances(): ?string
    {
        return $this->observances;
    }

    public function setObservances(string $observances): static
    {
        $this->observances = $observances;

        return $this;
    }

    public function getTitles(): ?string
    {
        return $this->titles;
    }

    public function setTitles(string $titles): static
    {
        $this->titles = $titles;

        return $this;
    }
}
