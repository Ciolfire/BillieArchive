<?php

declare(strict_types=1);

namespace App\Entity\Types;

use App\Entity;

final class ChoicesMeritPrerequisite
{
    public const Attribute = Entity\Attribute::class;
    public const Skill = Entity\Skill::class;
    public const Merit = Entity\Merit::class;
    public const Discipline = Entity\Discipline::class;
    public const Clan = Entity\Clan::class;
    //   public const Devotion = Entity\Devotion::class;
    //   public const Race = Entity\Race::class;
}
