<?php

namespace App\Entity;

use App\Repository\VampireDisciplineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VampireDisciplineRepository::class)
 */
class VampireDiscipline
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @var int|null
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity=Discipline::class)
   * @ORM\JoinColumn(nullable=false)
   * @var \App\Entity\Discipline|null
   */
  private $discipline;

  /**
   * @ORM\ManyToOne(targetEntity=Vampire::class, inversedBy="disciplines",cascade={"persist"})
   * @ORM\JoinColumn(nullable=false)
   * @var \App\Entity\Vampire|null
   */
  private $character;

  /**
   * @ORM\Column(type="smallint")
   * @var int|null
   */
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
    return $this->vampire;
  }

  public function setVampire(?Vampire $vampire): self
  {
    $this->vampire = $vampire;

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
}
