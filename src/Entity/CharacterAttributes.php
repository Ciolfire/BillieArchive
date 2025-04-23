<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\CharacterAttributesRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CharacterAttributesRepository::class)]
#[ORM\Table(name: "characters_attributes")]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class CharacterAttributes
{
  /** @var array<string> */
  public array $list = [
    'intelligence',
    'wits',
    'resolve',
    'strength',
    'dexterity',
    'stamina',
    'presence',
    'manipulation',
    'composure'
  ];

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private ?int $id;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $intelligence = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $wits = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $resolve = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $strength = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $dexterity = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $stamina = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $presence = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $manipulation = 1;

  #[ORM\Column(type: "smallint", options: ["unsigned" => true, "default" => 1])]
  protected int $composure = 1;

  #[ORM\OneToOne(targetEntity: Character::class, mappedBy: "attributes")]
  protected Character $character;

  public function __construct(?CharacterAttributes $attributes = null)
  {
    if (!is_null($attributes)) {
      $property_names  = array_keys(get_object_vars($this));
      foreach ($property_names as $property_name) {
        $this->{$property_name} = $attributes->$property_name;
      }
    }
  }

  public function __clone() {
    if ($this->id) {
      $this->id = null;
    }
    $this->getAll();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setCharacter(Character $character) : self
  {
    $this->character = $character;

    return $this;
  }

  public function get(string $attribute, bool $includeModifiers = true) : mixed
  {
    if ($includeModifiers) {
      foreach ($this->character->getStatusEffects() as $effect) {
        if ($effect->getType() == 'attribute' && $effect->getChoice() == $attribute) {
          return max(0, $this->$attribute + $effect->getValue());
        }
      }
    }

    return $this->$attribute;
  }

  public function set(string $attribute, int $value): self
  {
    $setter = "set".ucfirst($attribute);

    $this->$setter($value);

    return $this;
  }

  public function getAll(bool $any=true) : array
  {
    $attributes = [];
    foreach ($this->list as $attribute) {
      if ($any || $this->$attribute > 0) {
        $attributes[] = ['id' => $attribute, 'value' => $this->$attribute];
      }
    }

    return $attributes;
  }

  public function getIntelligence() : int
  {
    return $this->get('intelligence');
  }

  public function setIntelligence(int $intelligence): self
  {
    $this->intelligence = $intelligence;

    return $this;
  }

  public function getWits(): int
  {
    return $this->get('wits');
  }

  public function setWits(int $wits) : self
  {
    $this->wits = $wits;

    return $this;
  }

  public function getResolve(): int
  {
    return $this->get('resolve');
  }

  public function setResolve(int $resolve) : self
  {
    $difference = $resolve - $this->resolve;
    $this->changeWillpower($difference);
    $this->resolve = $resolve;

    return $this;
  }

  public function getStrength(): int
  {
    return $this->get('strength');
  }

  public function setStrength(int $strength): self
  {
    $this->strength = $strength;

    return $this;
  }

  public function getDexterity(): int
  {
    return $this->get('dexterity');
  }

  public function setDexterity(int $dexterity): self
  {
    $this->dexterity = $dexterity;

    return $this;
  }

  public function getStamina(): int
  {
    return $this->get('stamina');
  }

  public function setStamina(int $stamina): self
  {
    $this->stamina = $stamina;

    return $this;
  }

  public function getPresence(): int
  {
    return $this->get('presence');
  }

  public function setPresence(int $presence): self
  {
    $this->presence = $presence;

    return $this;
  }

  public function getManipulation(): int
  {
    return $this->get('manipulation');
  }

  public function setManipulation(int $manipulation): self
  {
    $this->manipulation = $manipulation;

    return $this;
  }

  public function getComposure(): int
  {

    return $this->get('composure');
  }

  public function setComposure(int $composure): self
  {
    $difference = $composure - $this->composure;
    $this->changeWillpower($difference);
    $this->composure = $composure;

    return $this;
  }

  public function changeWillpower(int $difference): self
  {
    $this->character->setWillpower($this->character->getWillpower() + $difference);

    return $this;
  }
}
