<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: SkillRepository::class)]
// #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\SkillTranslation")]
class Skill
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type:Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: Types::STRING, length: 20)]
  private string $identifier = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 20)]
  private string $name = "";

  #[ORM\Column(type: Types::STRING, length: 20)]
  private string $category = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private string $description = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private string $fluff = "";

  #[ORM\ManyToMany(targetEntity: Roll::class, mappedBy: 'skills')]
  #[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
  private Collection $rolls;

  public function __construct()
  {
      $this->rolls = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getIdentifier(): string
  {
    return $this->identifier;
  }

  public function setIdentifier(string $identifier): self
  {
    $this->identifier = $identifier;

    return $this;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getCategory(): string
  {
    return $this->category;
  }

  public function setCategory(string $category): self
  {
    $this->category = $category;

    return $this;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description = ""): self
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  public function getFluff(): string
  {
    return $this->fluff;
  }

  public function setFluff(string $fluff = ""): self
  {
    if ($this->fluff == "") {
      $this->fluff = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $fluff);
    } else {
      $this->fluff = $fluff;
    }

    return $this;
  }

  /**
   * @return Collection<int, Roll>
   */
  public function getRolls(): Collection
  {
      return $this->rolls;
  }

  public function addRoll(Roll $roll): self
  {
      if (!$this->rolls->contains($roll)) {
          $this->rolls->add($roll);
          $roll->addSkill($this);
      }

      return $this;
  }

  public function removeRoll(Roll $roll): self
  {
      if ($this->rolls->removeElement($roll)) {
          $roll->removeSkill($this);
      }

      return $this;
  }
}
