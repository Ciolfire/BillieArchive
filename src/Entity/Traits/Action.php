<?php declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

trait Action {
  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $action = null;

  public function getAction(): ?int
  {
    return $this->action;
  }

  public function setAction(int $action): static
  {
    $this->action = $action;

    return $this;
  }

  public function getActionName(): string
  {
    return self::Type[$this->action];
  }

  public const Type = [
    0 => 'roll.action.instant',
    1 => 'roll.action.extended',
    2 => 'roll.action.reflexive',
  ];
}