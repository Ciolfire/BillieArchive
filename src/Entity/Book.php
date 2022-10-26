<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
  private $name;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::SMALLINT)]

  private $ruleset;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 50, nullable: true)]
  private $type;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 10, nullable: true)]
  private $short;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::DATETIME_IMMUTABLE)]
  private $releasedAt;

  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 50)]
  private $setting;

  #[ORM\OneToMany(targetEntity: Merit::class, mappedBy: 'book')]
  private $merits;

  #[ORM\OneToMany(targetEntity: Clan::class, mappedBy: 'book')]
  private $clans;

  #[ORM\OneToMany(targetEntity: Discipline::class, mappedBy: 'book')]
  private $disciplines;

  public function __construct()
  {
    $this->clans = new \Doctrine\Common\Collections\ArrayCollection();
    $this->disciplines = new \Doctrine\Common\Collections\ArrayCollection();
    $this->merits = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
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

  public function getRuleset(): ?int
  {
    return $this->ruleset;
  }

  public function setRuleset(int $ruleset): self
  {
    $this->ruleset = $ruleset;

    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(?string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(?string $short): self
  {
    $this->short = $short;

    return $this;
  }

  public function getReleasedAt(): ?\DateTimeImmutable
  {
    return $this->releasedAt;
  }

  public function setReleasedAt(\DateTimeImmutable $releasedAt): self
  {
    $this->releasedAt = $releasedAt;

    return $this;
  }

  public function getSetting(): ?string
  {
    return $this->setting;
  }

  public function setSetting(string $setting): self
  {
    $this->setting = $setting;

    return $this;
  }


  public function getMerits(): Collection
  {
    return $this->merits;
  }

  public function addMerit(Merit $merit): self
  {
    if (!$this->merits->contains($merit)) {
      $this->merits[] = $merit;
      $merit->setBook($this);
    }

    return $this;
  }

  public function removeMerit(Merit $merit): self
  {
    if ($this->merits->removeElement($merit)) {
      // set the owning side to null (unless already changed)
      if ($merit->getBook() === $this) {
        $merit->setBook(null);
      }
    }

    return $this;
  }
}
