<?php

namespace App\Entity;

use App\Repository\MagicalPracticeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: MagicalPracticeRepository::class)]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\MagicalPracticeTranslation")]
class MagicalPractice implements Translatable
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column]
  private ?int $level = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  /**
   * @var Collection<int, MageSpell>
   */
  #[ORM\OneToMany(targetEntity: MageSpell::class, mappedBy: 'practice', orphanRemoval: true)]
  private Collection $spells;

  public function __construct()
  {
    $this->spells = new ArrayCollection();
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
    $this->description = $description;

    return $this;
  }

  /**
   * @return Collection<int, MageSpell>
   */
  public function getSpells(): Collection
  {
    return $this->spells;
  }

  public function addSpell(MageSpell $spell): static
  {
    if (!$this->spells->contains($spell)) {
      $this->spells->add($spell);
      $spell->setPractice($this);
    }

    return $this;
  }

  public function removeSpell(MageSpell $spell): static
  {
    if ($this->spells->removeElement($spell)) {
      // set the owning side to null (unless already changed)
      if ($spell->getPractice() === $this) {
        $spell->setPractice(null);
      }
    }

    return $this;
  }
}
