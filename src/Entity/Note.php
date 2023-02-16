<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
#[UniqueEntity(fields: ['title', 'chronicle', 'user'], errorPath: 'port', message: 'This name is already in use on that chronicle.',)]
#[ORM\UniqueConstraint(columns: ['title', 'user_id', 'chronicle_id'])]
class Note
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $title = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $assignedAt = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $content = "";

  #[ORM\ManyToOne]
  private ?Chronicle $chronicle = null;

  #[ORM\ManyToOne(inversedBy: 'notes')]
  private ?User $user = null;

  #[ORM\ManyToOne]
  private ?Character $character = null;

  #[ORM\ManyToOne(inversedBy: 'notes')]
  private ?NoteCategory $category = null;

  #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'linkToNotes')]
  private Collection $linkedNotes;

  #[ORM\JoinTable(name: 'note_links')]
  #[ORM\JoinColumn(name: 'note_source', referencedColumnName: 'id')]
  #[ORM\InverseJoinColumn(name: 'note_target', referencedColumnName: 'id')]
  #[ORM\ManyToMany(targetEntity: 'Note', inversedBy: 'linkedNotes')]
  private Collection $linkToNotes;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $plainText = null;

  public function __construct()
  {
    $this->linkedNotes = new ArrayCollection();
    $this->linkToNotes = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->title;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(?string $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getAssignedAt(): ?\DateTimeImmutable
  {
    return $this->assignedAt;
  }

  public function setAssignedAt(\DateTimeImmutable $assignedAt): self
  {
    $this->assignedAt = $assignedAt;

    return $this;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): self
  {
    $this->plainText = strip_tags($content);
    $converter = new HtmlConverter();
    $this->content = $converter->convert($content);

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

  public function getCharacter(): ?Character
  {
    return $this->character;
  }

  public function setCharacter(?Character $character): self
  {
    $this->character = $character;

    return $this;
  }

  public function getCategory(): ?NoteCategory
  {
    return $this->category;
  }

  public function setCategory(?NoteCategory $category): self
  {
    $this->category = $category;

    return $this;
  }

  /**
   * @return Collection<int, self>
   */
  public function getNotes(): Collection
  {
    return new ArrayCollection(
      array_merge(
        $this->linkedNotes->toArray(),
        $this->linkToNotes->toArray()
      )
    );
  }

  public function addNote(self $linkedNote): self
  {
    if (!$this->linkedNotes->contains($linkedNote) && $linkedNote !== $this) {
      $this->linkToNotes->add($linkedNote);
    }

    return $this;
  }

  public function removeNote(self $linkedNote): self
  {
    $this->linkToNotes->removeElement($linkedNote);

    return $this;
  }

  public function getPlainText(): ?string
  {
      return $this->plainText;
  }

  public function setPlainText(string $plainText): self
  {
      $this->plainText = $plainText;

      return $this;
  }
}
