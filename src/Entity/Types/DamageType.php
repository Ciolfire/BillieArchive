<?php

declare(strict_types=1);

namespace App\Entity\Types;

enum DamageType: int
{
  case bashing = 0;
  case lethal = 1;
  case aggravated = 2;
}
