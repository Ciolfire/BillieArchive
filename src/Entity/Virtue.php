<?php

namespace App\Entity;

use App\Repository\VirtueRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: VirtueRepository::class)]
class Virtue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private $id;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 10)]
    private $name;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private $details;

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
        $this->details = $details;

        return $this;
    }
}
