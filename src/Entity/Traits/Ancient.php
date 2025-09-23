<?php declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Chronicle;
use Doctrine\ORM\Mapping as ORM;

trait Ancient {
  #[ORM\Column(nullable: true)]
  private ?bool $isAncient = null;

  public function isAncient(): ?bool
  {
    return $this->isAncient;
  }

  public function setIsAncient(?bool $isAncient): self
  {
    $this->isAncient = $isAncient;

    return $this;
  }
}
