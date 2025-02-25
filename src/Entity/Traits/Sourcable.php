<?php declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Book;

trait Sourcable {
  #[ORM\ManyToOne(targetEntity: Book::class)]
  protected ?Book $book = null;

  #[ORM\Column(type: "smallint", nullable: true)]
  protected ?int $page;

  public function getBook(): ?Book
  {
    return $this->book;
  }

  public function setBook(?Book $book): self
  {
    $this->book = $book;

    return $this;
  }

  public function getPage(): ?int
  {
    return $this->page;
  }

  public function setPage(?int $page): self
  {
    $this->page = $page;

    return $this;
  }
}