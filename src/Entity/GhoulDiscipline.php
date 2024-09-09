<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\GhoulDisciplineRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: GhoulDisciplineRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class GhoulDiscipline
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private int $id;

  #[ORM\ManyToOne(targetEntity: Discipline::class, fetch: "EAGER")]
  #[ORM\JoinColumn(nullable: false)]
  private Discipline $discipline;

  #[ORM\ManyToOne(targetEntity: Ghoul::class, inversedBy: "disciplines",cascade: ["persist"])]
  #[ORM\JoinColumn(nullable: false)]
  private ?Ghoul $character;

  #[ORM\Column(type: "smallint")]
  private int $level = 1;

  public function __construct(Ghoul $character, Discipline $discipline, int $level)
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

  public function getCharacter(): ?Ghoul
  {
    return $this->character;
  }

  public function setCharacter(?Ghoul $Ghoul): self
  {
    $this->character = $Ghoul;

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
