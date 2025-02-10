<?php

namespace App\Entity;

use App\Entity\Traits\Sourcable;
use App\Repository\ArcanumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "orders")])]
#[ORM\Entity(repositoryClass: ArcanumRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\ArcanumTranslation")]
class Arcanum implements Translatable
{
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 50)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $purview = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $short = null;

  /**
   * @var Collection<int, Path>
   */
  #[ORM\ManyToMany(targetEntity: Path::class, mappedBy: 'rulingArcana')]
  private Collection $paths;

  /**
   * @var Collection<int, Path>
   */
  #[ORM\OneToMany(targetEntity: Path::class, mappedBy: 'inferiorArcanum')]
  private Collection $inferiorPaths;

  public function __construct()
  {
    $this->paths = new ArrayCollection();
    $this->inferiorPaths = new ArrayCollection();
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

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getPurview(): ?string
  {
    return $this->purview;
  }

  public function setPurview(string $purview): static
  {
    $this->purview = $purview;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): static
  {
    $this->short = $short;

    return $this;
  }

  /**
   * @return Collection<int, Path>
   */
  public function getPaths(): Collection
  {
    return $this->paths;
  }

  public function addPath(Path $path): static
  {
    if (!$this->paths->contains($path)) {
      $this->paths->add($path);
      $path->addRulingArcana($this);
    }

    return $this;
  }

  public function removePath(Path $path): static
  {
    if ($this->paths->removeElement($path)) {
      $path->removeRulingArcana($this);
    }

    return $this;
  }

  /**
   * @return Collection<int, Path>
   */
  public function getInferiorPaths(): Collection
  {
    return $this->inferiorPaths;
  }

  public function addInferiorPath(Path $inferiorPath): static
  {
    if (!$this->inferiorPaths->contains($inferiorPath)) {
      $this->inferiorPaths->add($inferiorPath);
      $inferiorPath->setInferiorArcanum($this);
    }

    return $this;
  }

  public function removeInferiorPath(Path $inferiorPath): static
  {
    if ($this->inferiorPaths->removeElement($inferiorPath)) {
      // set the owning side to null (unless already changed)
      if ($inferiorPath->getInferiorArcanum() === $this) {
        $inferiorPath->setInferiorArcanum(null);
      }
    }

    return $this;
  }
}
