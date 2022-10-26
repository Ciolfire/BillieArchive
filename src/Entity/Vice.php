<?php

namespace App\Entity;

use App\Repository\ViceRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ViceRepository::class)]
class Vice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
    private $id;

    #[ORM\Column(type: "string", length: 8)]
    private $name;

    #[ORM\Column(type: "string")]
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
