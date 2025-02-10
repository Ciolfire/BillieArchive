<?php

namespace App\Entity;

use App\Repository\MageArcanumRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MageArcanumRepository::class)]
class MageArcanum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'arcana', cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mage $character = null;

    #[ORM\ManyToOne(fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Arcanum $arcanum = null;

    #[ORM\Column(type: "smallint")]
    private ?int $level = 1;

    public function __construct(Mage $character, Arcanum $arcanum, int $level)
    {
      $this->character = $character;
      $this->arcanum = $arcanum;
      $this->level = $level;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
      return $this->arcanum->getName();
    }

    public function getCharacter(): ?Mage
    {
        return $this->character;
    }

    public function setCharacter(?Mage $mage): static
    {
        $this->character = $mage;

        return $this;
    }

    public function getArcanum(): ?Arcanum
    {
        return $this->arcanum;
    }

    public function setArcanum(?Arcanum $arcanum): static
    {
        $this->arcanum = $arcanum;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }
}
