<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WerewolfRenownRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: WerewolfRenownRepository::class)]
#[ORM\Table(name: "werewolf_renown")]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class WerewolfRenown
{
  /** @var array<string> */
  public array $list = [
    'cunning',
    'glory',
    'honor',
    'purity',
    'wisdom',
  ];

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]
  private int $cunning = 0;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]
  private int $glory = 0;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]
  private int $honor = 0;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]
  private int $purity = 0;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]
  private int $wisdom = 0;

  #[ORM\OneToOne(targetEntity: Werewolf::class, mappedBy: "renowns")]
  private Werewolf $werewolf;

  public function __construct() {}

  public function __clone()
  {
    if ($this->id) {
      $this->id = null;
    }
    $this->getAll();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setCharacter(Werewolf $werewolf): self
  {
    $this->werewolf = $werewolf;

    return $this;
  }

  public function get(string $renown, bool $includeModifiers = false): ?int
  {

    // if ($includeModifiers) {
    //   foreach ($this->werewolf->getStatusEffects() as $effect) {
    //     if ($effect->getType() == 'renown' && $effect->getChoice() == $renown) {
    //       return max(0, $this->$renown + $effect->getRealValue());
    //     }
    //   }
    // }

    return $this->$renown;
    // return min($this->werewolf->getLimit(), $this->$renown);
  }

  public function set(string $renown, int $value): self
  {
    $this->$renown = $value;

    return $this;
  }

  public function getAll(bool $any = true): array
  {
    $renowns = [];

    foreach ($this->list as $renown) {
      if ($any || $this->$renown > 0) {
        $renowns[] = ['id' => $renown, 'value' => $this->$renown];
      }
    }

    return $renowns;
  }

  public function getCunning(): ?int
  {
    return $this->get('cunning');
  }

  public function setCunning(int $cunning): self
  {
    $this->cunning = $cunning;

    return $this;
  }

  public function getGlory(): ?int
  {
    return $this->get('glory');
  }

  public function setGlory(int $glory): self
  {
    $this->glory = $glory;

    return $this;
  }

  public function getHonor(): ?int
  {
    return $this->get('honor');
  }

  public function setHonor(int $honor): self
  {
    $this->honor = $honor;

    return $this;
  }

  public function getPurity(): ?int
  {
    return $this->get('purity');
  }

  public function setPurity(int $purity): self
  {
    $this->purity = $purity;

    return $this;
  }

  public function getWisdom(): ?int
  {
    return $this->get('wisdom');
  }

  public function setWisdom(int $wisdom): self
  {
    $this->wisdom = $wisdom;

    return $this;
  }
}
