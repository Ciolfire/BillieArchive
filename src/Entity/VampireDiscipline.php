<?php

namespace App\Entity;

use App\Repository\VampireDisciplineRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: VampireDisciplineRepository::class)]
class VampireDiscipline
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private $id;

  #[ORM\ManyToOne(targetEntity: Discipline::class, fetch: "EAGER")]
  #[ORM\JoinColumn(nullable: false)]
  private $discipline;

  #[ORM\ManyToOne(targetEntity: Vampire::class, inversedBy: "disciplines",cascade: ["persist"])]
  #[ORM\JoinColumn(nullable: false)]
  private $character;

  #[ORM\Column(type: "smallint")]
  private $level = 1;

  public function __construct(Vampire $character, Discipline $discipline, int $level)
  {
    $this->character = $character;
    $this->discipline = $discipline;
    $this->level = $level;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->discipline->getName();
  }

  public function getDiscipline(): ?Discipline
  {
    return $this->discipline;
  }

  public function setDiscipline(?Discipline $discipline): self
  {
    $this->discipline = $discipline;

    return $this;
  }

  public function getVampire(): ?Vampire
  {
    return $this->character;
  }

  public function setVampire(?Vampire $vampire): self
  {
    $this->character = $vampire;

    return $this;
  }

  public function getLevel(): ?int
  {
    return $this->level;
  }

  public function setLevel(int $level): self
  {
    $this->level = $level;

    return $this;
  }

  public function getType(): string
  {
    if ($this->discipline->isSorcery()) {
      return 'sorcery';
    } else if ($this->discipline->isCoil()) {
      return 'coil';
    } else if ($this->discipline->isThaumaturgy()) {
      return 'thaumaturgy';
    }
    return 'discipline';
  }
}
