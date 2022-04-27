<?php

namespace App\Entity\Traits;

use App\Entity\Chronicle;

trait Homebrewable {
  /**
   * @ORM\ManyToOne(targetEntity=Chronicle::class)
   */
  private $homebrewFor;

  public function getHomebrewFor(): ?Chronicle
  {
    return $this->homebrewFor;
  }

  public function setHomebrewFor(Chronicle $chronicle): self
  {
    $this->homebrewFor = $chronicle;

    return $this;
  }
}