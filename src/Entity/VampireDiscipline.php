<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\VampireDisciplineRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: VampireDisciplineRepository::class)]
class VampireDiscipline
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private int $id;

  #[ORM\ManyToOne(targetEntity: Discipline::class, fetch: "EAGER")]
  #[ORM\JoinColumn(nullable: false)]
  private Discipline $discipline;

  #[ORM\ManyToOne(targetEntity: Vampire::class, inversedBy: "disciplines",cascade: ["persist"])]
  #[ORM\JoinColumn(nullable: false)]
  private Vampire $character;

  #[ORM\Column(type: "smallint")]
  private int $level = 1;

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

  public function getDiscipline(): Discipline
  {
    return $this->discipline;
  }

  public function setDiscipline(Discipline $discipline): self
  {
    $this->discipline = $discipline;

    return $this;
  }

  public function getVampire(): ?Vampire
  {
    return $this->character;
  }

  public function setVampire(Vampire $vampire): self
  {
    $this->character = $vampire;

    return $this;
  }

  public function getLevel(): int
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
    /** @var Discipline $discipline */
    $discipline = $this->discipline;
    if ($discipline->isSorcery()) {
      return 'sorcery';
    } else if ($discipline->isCoil()) {
      return 'coil';
    } else if ($discipline->isThaumaturgy()) {
      return 'thaumaturgy';
    }
    return 'discipline';
  }
}
