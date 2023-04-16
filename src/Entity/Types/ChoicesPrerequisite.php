<?php declare(strict_types=1);

namespace App\Entity\Types;

use App\Entity;

final class ChoicesPrerequisite {
  public const Attribute = Entity\Attribute::class;
  public const Skill = Entity\Skill::class;
  // public const Race = Entity\Race::class;
  public const Discipline = Entity\Discipline::class;
  public const Merit = Entity\Merit::class;
  public const Clan = Entity\Clan::class;
}
