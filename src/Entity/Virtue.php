<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VirtueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: VirtueRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\VirtueTranslation")]
class Virtue implements Translatable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: Types::INTEGER)]
  private int $id;


  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::STRING, length: 10)]
  private string $name = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private string $details;

  #[ORM\Column(length: 255)]
  private ?string $otherNames = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function __toString(): string
  {
    return $this->name;
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

  public function getDetails(): ?string
  {
    return $this->details;
  }

  public function setDetails(string $details): self
  {
    if ($this->details == "") {
      $this->details = preg_replace("/(?<!(\r\n|  ))\r\n(?!\r\n)/m", " ", $details);
    } else {
      $this->details = $details;
    }

    return $this;
  }

  public function getOtherNames(): ?string
  {
      return $this->otherNames;
  }

  public function setOtherNames(string $otherNames): static
  {
      $this->otherNames = $otherNames;

      return $this;
  }
}
