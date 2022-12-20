<?php

namespace App\Entity;

use App\Repository\NoteCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteCategoryRepository::class)]
#[ORM\UniqueConstraint(columns: ['name', 'user_id', 'chronicle_id'])]
class NoteCategory
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\OneToMany(mappedBy: 'category', targetEntity: Note::class)]
  private Collection $notes;

  #[ORM\ManyToOne]
  private ?Chronicle $chronicle = null;

  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user = null;

  #[ORM\Column(length: 255)]
  private ?string $name = "";

  public function __construct()
  {
    $this->notes = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * @return Collection<int, Note>
   */
  public function getNotes(): Collection
  {
    return $this->notes;
  }

  public function addNote(Note $note): self
  {
    if (!$this->notes->contains($note)) {
      $this->notes->add($note);
      $note->setCategory($this);
    }

    return $this;
  }

  public function removeNote(Note $note): self
  {
    if ($this->notes->removeElement($note)) {
      // set the owning side to null (unless already changed)
      if ($note->getCategory() === $this) {
        $note->setCategory(null);
      }
    }

    return $this;
  }

  public function getChronicle(): ?Chronicle
  {
    return $this->chronicle;
  }

  public function setChronicle(?Chronicle $chronicle): self
  {
    $this->chronicle = $chronicle;

    return $this;
  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(?User $user): self
  {
    $this->user = $user;

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
}
