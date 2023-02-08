<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\SkillTranslation")]
class Skill
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type:Types::INTEGER)]
  private $id;

  #[ORM\Column(type: Types::STRING, length: 20)]
  private $identifier;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 20)]
  private $name;

  #[ORM\Column(type: Types::STRING, length: 20)]
  private $category;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private $description;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private $fluff;

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getIdentifier(): ?string
  {
    return $this->identifier;
  }

  public function setIdentifier(string $identifier): self
  {
    $this->identifier = $identifier;

    return $this;
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

  public function getCategory(): ?string
  {
    return $this->category;
  }

  public function setCategory(string $category): self
  {
    $this->category = $category;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getFluff(): ?string
  {
    return $this->fluff;
  }

  public function setFluff(?string $fluff): self
  {
    $this->fluff = $fluff;

    return $this;
  }
}
