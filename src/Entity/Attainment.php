<?php

namespace App\Entity;

use App\Repository\AttainmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AttainmentRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\AttainmentTranslation")]
class Attainment implements Translatable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\ManyToOne(inversedBy: 'attainments')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Legacy $legacy = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $level = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $description = "";

  public function __construct(Legacy $legacy)
  {
    $this->legacy = $legacy;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getLegacy(): ?Legacy
  {
    return $this->legacy;
  }

  public function setLegacy(?Legacy $legacy): static
  {
    $this->legacy = $legacy;

    return $this;
  }

  public function getLevel(): ?int
  {
    return $this->level;
  }

  public function setLevel(int $level): static
  {
    $this->level = $level;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }
}
