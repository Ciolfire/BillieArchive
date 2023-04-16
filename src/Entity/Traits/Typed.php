<?php declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Typed {
  #[ORM\Column(length: 255, nullable: true)]
  private ?string $type = null;

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }
}
