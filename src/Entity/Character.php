<?php

namespace App\Entity;

use App\Repository\CharacterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterRepository::class)
 * @ORM\Table(name="characters")
 */
class Character
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"unsigned":true})
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $player;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $virtue;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $vice;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $concept;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $chronicle;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $faction;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $groupName;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $intelligence;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $wits;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $resolve;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $strength;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $dexterity;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $stamina;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $presence;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $manipulation;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $composure;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $merits = [];

    public function __construct()
    {
        $this->intelligence = 1;
        $this->wits = 1;
        $this->resolve = 1;

        $this->strength = 1;
        $this->dexterity = 1;
        $this->stamina = 1;

        $this->presence = 1;
        $this->manipulation = 1;
        $this->composure = 1;
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function setPlayer(string $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getVirtue(): ?string
    {
        return $this->virtue;
    }

    public function setVirtue(string $virtue): self
    {
        $this->virtue = $virtue;

        return $this;
    }

    public function getVice(): ?string
    {
        return $this->vice;
    }

    public function setVice(string $vice): self
    {
        $this->vice = $vice;

        return $this;
    }

    public function getConcept(): ?string
    {
        return $this->concept;
    }

    public function setConcept(string $concept): self
    {
        $this->concept = $concept;

        return $this;
    }

    public function getChronicle(): ?string
    {
        return $this->chronicle;
    }

    public function setChronicle(?string $chronicle): self
    {
        $this->chronicle = $chronicle;

        return $this;
    }

    public function getFaction(): ?string
    {
        return $this->faction;
    }

    public function setFaction(?string $faction): self
    {
        $this->faction = $faction;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(?string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getWits(): ?int
    {
        return $this->wits;
    }

    public function setWits(int $wits): self
    {
        $this->wits = $wits;

        return $this;
    }

    public function getResolve(): ?int
    {
        return $this->resolve;
    }

    public function setResolve(int $resolve): self
    {
        $this->resolve = $resolve;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getDexterity(): ?int
    {
        return $this->dexterity;
    }

    public function setDexterity(int $dexterity): self
    {
        $this->dexterity = $dexterity;

        return $this;
    }

    public function getStamina(): ?int
    {
        return $this->stamina;
    }

    public function setStamina(int $stamina): self
    {
        $this->stamina = $stamina;

        return $this;
    }

    public function getPresence(): ?int
    {
        return $this->presence;
    }

    public function setPresence(int $presence): self
    {
        $this->presence = $presence;

        return $this;
    }

    public function getManipulation(): ?int
    {
        return $this->manipulation;
    }

    public function setManipulation(int $manipulation): self
    {
        $this->manipulation = $manipulation;

        return $this;
    }

    public function getComposure(): ?int
    {
        return $this->composure;
    }

    public function setComposure(int $composure): self
    {
        $this->composure = $composure;

        return $this;
    }

    public function getMerits(): ?array
    {
        return $this->merits;
    }

    public function setMerits(?array $merits): self
    {
        $this->merits = $merits;

        return $this;
    }
}
