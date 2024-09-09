<?php declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\ContentType;
use Doctrine\ORM\Mapping as ORM;

trait Typed {
  // #[ORM\Column(name:'type_id' , length: 255, nullable: true)]
  #[ORM\ManyToOne()]
  private ?ContentType $type = null;

  public function getType(): ?ContentType
  {
    return $this->type;
  }

  public function setType(?ContentType $type): self
  {
    $this->type = $type;

    return $this;
  }
}
