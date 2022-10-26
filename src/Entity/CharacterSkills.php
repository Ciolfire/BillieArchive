<?php

namespace App\Entity;

use App\Repository\CharacterSkillsRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CharacterSkillsRepository::class)]
#[ORM\Table(name: "characters_skills")]
class CharacterSkills
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private $id = 0;

  #[ORM\Column(type: "smallint")]
  private $academics = 0;

  #[ORM\Column(type: "smallint")]
  private $computer = 0;

  #[ORM\Column(type: "smallint")]
  private $crafts = 0;

  #[ORM\Column(type: "smallint")]
  private $investigation = 0;

  #[ORM\Column(type: "smallint")]
  private $medecine = 0;

  #[ORM\Column(type: "smallint")]
  private $occult = 0;

  #[ORM\Column(type: "smallint")]
  private $politics = 0;

  #[ORM\Column(type: "smallint")]
  private $science = 0;

  #[ORM\Column(type: "smallint")]
  private $athletics = 0;

  #[ORM\Column(type: "smallint")]
  private $brawl = 0;

  #[ORM\Column(type: "smallint")]
  private $drive = 0;

  #[ORM\Column(type: "smallint")]
  private $firearms = 0;

  #[ORM\Column(type: "smallint")]
  private $larceny = 0;

  #[ORM\Column(type: "smallint")]
  private $stealth = 0;

  #[ORM\Column(type: "smallint")]
  private $survival = 0;

  #[ORM\Column(type: "smallint")]
  private $weaponry = 0;

  #[ORM\Column(type: "smallint")]
  private $animalKen = 0;

  #[ORM\Column(type: "smallint")]
  private $empathy = 0;

  #[ORM\Column(type: "smallint")]
  private $expression = 0;

  #[ORM\Column(type: "smallint")]
  private $intimidation = 0;

  #[ORM\Column(type: "smallint")]
  private $persuasion = 0;

  #[ORM\Column(type: "smallint")]
  private $socialize = 0;

  #[ORM\Column(type: "smallint")]
  private $streetwise = 0;

  #[ORM\Column(type: "smallint")]
  private $subterfuge = 0;

  #[ORM\OneToOne(targetEntity: Character::class, mappedBy: "skills")]
  private $character;

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

  public function get(string $skill): ?int
  {
    return min($this->character->getLimit(), $this->$skill);
  }

  public function set(string $skill, int $value): self
  {
    $this->$skill = $value;

    return $this;
  }

  public function getAcademics(): ?int
  {
    return $this->academics;
  }

  public function setAcademics(int $academics): self
  {
    $this->academics = $academics;

    return $this;
  }

  public function getComputer(): ?int
  {
    return $this->computer;
  }

  public function setComputer(int $computer): self
  {
    $this->computer = $computer;

    return $this;
  }

  public function getCrafts(): ?int
  {
    return $this->crafts;
  }

  public function setCrafts(int $crafts): self
  {
    $this->crafts = $crafts;

    return $this;
  }

  public function getInvestigation(): ?int
  {
    return $this->investigation;
  }

  public function setInvestigation(int $investigation): self
  {
    $this->investigation = $investigation;

    return $this;
  }

  public function getMedecine(): ?int
  {
    return $this->medecine;
  }

  public function setMedecine(int $medecine): self
  {
    $this->medecine = $medecine;

    return $this;
  }

  public function getOccult(): ?int
  {
    return $this->occult;
  }

  public function setOccult(int $occult): self
  {
    $this->occult = $occult;

    return $this;
  }

  public function getPolitics(): ?int
  {
    return $this->politics;
  }

  public function setPolitics(int $politics): self
  {
    $this->politics = $politics;

    return $this;
  }

  public function getScience(): ?int
  {
    return $this->science;
  }

  public function setScience(int $science): self
  {
    $this->science = $science;

    return $this;
  }

  public function getAthletics(): ?int
  {
    return $this->athletics;
  }

  public function setAthletics(int $athletics): self
  {
    $this->athletics = $athletics;

    return $this;
  }

  public function getBrawl(): ?int
  {
    return $this->brawl;
  }

  public function setBrawl(int $brawl): self
  {
    $this->brawl = $brawl;

    return $this;
  }

  public function getDrive(): ?int
  {
    return $this->drive;
  }

  public function setDrive(int $drive): self
  {
    $this->drive = $drive;

    return $this;
  }

  public function getFirearms(): ?int
  {
    return $this->firearms;
  }

  public function setFirearms(int $firearms): self
  {
    $this->firearms = $firearms;

    return $this;
  }

  public function getLarceny(): ?int
  {
    return $this->larceny;
  }

  public function setLarceny(int $larceny): self
  {
    $this->larceny = $larceny;

    return $this;
  }

  public function getStealth(): ?int
  {
    return $this->stealth;
  }

  public function setStealth(int $stealth): self
  {
    $this->stealth = $stealth;

    return $this;
  }

  public function getSurvival(): ?int
  {
    return $this->survival;
  }

  public function setSurvival(int $survival): self
  {
    $this->survival = $survival;

    return $this;
  }

  public function getWeaponry(): ?int
  {
    return $this->weaponry;
  }

  public function setWeaponry(int $weaponry): self
  {
    $this->weaponry = $weaponry;

    return $this;
  }

  public function getAnimalKen(): ?int
  {
    return $this->animalKen;
  }

  public function setAnimalKen(int $animalKen): self
  {
    $this->animalKen = $animalKen;

    return $this;
  }

  public function getEmpathy(): ?int
  {
    return $this->empathy;
  }

  public function setEmpathy(int $empathy): self
  {
    $this->empathy = $empathy;

    return $this;
  }

  public function getExpression(): ?int
  {
    return $this->expression;
  }

  public function setExpression(int $expression): self
  {
    $this->expression = $expression;

    return $this;
  }

  public function getIntimidation(): ?int
  {
    return $this->intimidation;
  }

  public function setIntimidation(int $intimidation): self
  {
    $this->intimidation = $intimidation;

    return $this;
  }

  public function getPersuasion(): ?int
  {
    return $this->persuasion;
  }

  public function setPersuasion(int $persuasion): self
  {
    $this->persuasion = $persuasion;

    return $this;
  }

  public function getSocialize(): ?int
  {
    return $this->socialize;
  }

  public function setSocialize(int $socialize): self
  {
    $this->socialize = $socialize;

    return $this;
  }

  public function getStreetwise(): ?int
  {
    return $this->streetwise;
  }

  public function setStreetwise(int $streetwise): self
  {
    $this->streetwise = $streetwise;

    return $this;
  }

  public function getSubterfuge(): ?int
  {
    return $this->subterfuge;
  }

  public function setSubterfuge(int $subterfuge): self
  {
    $this->subterfuge = $subterfuge;

    return $this;
  }
}
