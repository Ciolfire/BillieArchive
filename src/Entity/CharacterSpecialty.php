<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\CharacterSpecialtyRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CharacterSpecialtyRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[ORM\Table(name: "characters_specialty")]
class CharacterSpecialty
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: "string", length: 30)]
  private ?string $name;

  #[ORM\ManyToOne(targetEntity: Character::class, inversedBy: "specialties", cascade: ["persist", "remove"])]
  private ?Character $character;

  #[ORM\ManyToOne(targetEntity: Skill::class)]
  private ?Skill $skill;


  public function __construct(Character $character = null, Skill $skill = null, string $name = null)
  {
    $this->character = $character;
    $this->skill = $skill;
    $this->name = $name;
  }

  public function getId(): ?int
  {
    return $this->id;
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

  public function getCharacter(): ?Character
  {
    return $this->character;
  }

  public function setCharacter(?Character $character): self
  {
    $this->character = $character;

    return $this;
  }

  public function getSkill(): ?Skill
  {
    return $this->skill;
  }

  public function setSkill(?Skill $skill): self
  {
    $this->skill = $skill;

    return $this;
  }

  public function detailedName(): string
  {
    return "{$this->name} ({$this->skill->getName()})";
  }
}
