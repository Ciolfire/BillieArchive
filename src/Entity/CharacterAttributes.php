<?php

namespace App\Entity;

use App\Repository\CharacterAttributesRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CharacterAttributesRepository::class)]
#[ORM\Table(name: "characters_attributes")]
class CharacterAttributes
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private $id;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $intelligence = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $wits = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $resolve = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $strength = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $dexterity = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $stamina = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $presence = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $manipulation = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected $composure = 1;

  #[ORM\OneToOne(targetEntity: Character::class, mappedBy: "attributes")]
  protected $character;

  public function __construct()
  {
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setCharacter(Character $character)
  {
    $this->character = $character;
  }

  public function get($attribute) {
    return min($this->character->getLimit(), $this->$attribute);
  }

  public function set(string $attribute, int $value): self
  {
    $this->$attribute = $value;

    return $this;
  }

  public function getIntelligence(): ?int
  {
    return min($this->character->getLimit(), $this->intelligence);
  }

  public function setIntelligence(int $intelligence): self
  {
    $this->intelligence = $intelligence;

    return $this;
  }

  public function getWits(): ?int
  {
    return min($this->character->getLimit(), $this->wits);
  }

  public function setWits(int $wits): self
  {
    $this->wits = $wits;

    return $this;
  }

  public function getResolve(): ?int
  {
    return min($this->character->getLimit(), $this->resolve);
  }

  public function setResolve(int $resolve): self
  {
    if ($resolve != $this->resolve) {
      $difference = $resolve - $this->resolve;
      $this->setWillpower($difference);
    }

    $this->resolve = $resolve;

    return $this;
  }

  public function getStrength(): ?int
  {
    return min($this->character->getLimit(), $this->strength);
  }

  public function setStrength(int $strength): self
  {
    $this->strength = $strength;

    return $this;
  }

  public function getDexterity(): ?int
  {
    return min($this->character->getLimit(), $this->dexterity);
  }

  public function setDexterity(int $dexterity): self
  {
    $this->dexterity = $dexterity;

    return $this;
  }

  public function getStamina(): ?int
  {
    return min($this->character->getLimit(), $this->stamina);
  }

  public function setStamina(int $stamina): self
  {
    $this->stamina = $stamina;

    return $this;
  }

  public function getPresence(): ?int
  {
    return min($this->character->getLimit(), $this->presence);
  }

  public function setPresence(int $presence): self
  {
    $this->presence = $presence;

    return $this;
  }

  public function getManipulation(): ?int
  {
    return min($this->character->getLimit(), $this->manipulation);
  }

  public function setManipulation(int $manipulation): self
  {
    $this->manipulation = $manipulation;

    return $this;
  }

  public function getComposure(): ?int
  {

    return min($this->character->getLimit(), $this->composure);
  }

  public function setComposure(int $composure): self
  {
    if ($composure != $this->composure) {
      $difference = $composure - $this->composure;
      $this->setWillpower($difference);
    }
    $this->composure = $composure;

    return $this;
  }

  public function setWillpower(int $difference): self
  {
    $this->character->setWillpower($this->character->getWillpower() + $difference);

    return $this;
  }
}
