<?php declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Chronicle;
use Doctrine\ORM\Mapping as ORM;

trait Homebrewable {
  #[ORM\ManyToOne(targetEntity: Chronicle::class)]
  private ?Chronicle $homebrewFor;

  public function getHomebrewFor(): ?Chronicle
  {
    return $this->homebrewFor;
  }

  public function setHomebrewFor(?Chronicle $chronicle): self
  {
    $this->homebrewFor = $chronicle;

    return $this;
  }

  public function isAvailable(?Chronicle $chronicle): bool {
    if (is_null($this->homebrewFor) || $chronicle === $this->homebrewFor ) {
      return true;
    }

    return false;
  }
}
