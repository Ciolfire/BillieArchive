<?php

namespace App\Entity;

use App\Entity\Types\TypeNote;
use App\Repository\CharacterNoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\Entity(repositoryClass: CharacterNoteRepository::class)]
class CharacterNote
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $content = "";

  #[ORM\ManyToOne(inversedBy: 'notes')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Character $character = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $assignedAt = null;

  #[ORM\ManyToOne(inversedBy: 'userCharacterNotes')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $author = null;

  #[ORM\Column(length: 50, nullable: true)]
  private ?string $title = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $type = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): self
  {
    $converter = new HtmlConverter();
    $this->content = $converter->convert($content);

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

  public function getAssignedAt(): ?\DateTimeImmutable
  {
    return $this->assignedAt;
  }

  public function setAssignedAt(\DateTimeImmutable $assignedAt): self
  {
    $this->assignedAt = $assignedAt;

    return $this;
  }

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): self
  {
    $this->author = $author;

    return $this;
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

  public function getType(): ?int
  {
      return $this->type;
  }

  public function getTypeName(): ?string
  {
    return TypeNote::typeNames[$this->type];
  }

  public function setType(int $type): self
  {
      $this->type = $type;

      return $this;
  }
}
