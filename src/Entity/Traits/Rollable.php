<?php declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Roll;
use Doctrine\ORM\Mapping as ORM;

trait Rollable {
  #[ORM\OneToOne(targetEntity: Roll::class, cascade:["persist"])]
  private ?Roll $roll;

  public function getRoll(): ?Roll
  {
    return $this->roll;
  }

  public function setRoll(?Roll $roll): self
  {
    $this->roll = $roll;

    return $this;
  }
}
