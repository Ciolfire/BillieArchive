<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Book;

trait Sourcable {
  #[ORM\ManyToOne(targetEntity: Book::class)]
  #[ORM\OrderBy(["name" => "ASC"])]
  protected $book;

  #[ORM\Column(type: "smallint", nullable: true)]
  protected $page;

  public function getBook(): ?Book
  {
    return $this->book;
  }

  public function setBook(?Book $book): self
  {
    $this->book = $book;

    return $this;
  }

  public function getPage(): ?string
  {
    return $this->page;
  }

  public function setPage(?string $page): self
  {
    $this->page = $page;

    return $this;
  }
}