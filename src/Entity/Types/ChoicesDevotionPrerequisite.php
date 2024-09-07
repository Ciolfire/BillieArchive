<?php

declare(strict_types=1);

namespace App\Entity\Types;

use App\Entity;

final class ChoicesDevotionPrerequisite
{
    public const discipline = Entity\Discipline::class;
    public const devotion = Entity\Devotion::class;
    public const potency = "potency";
    public const special = "special";
    //   public const Attribute = Entity\Attribute::class;
    //   public const Skill = Entity\Skill::class;
    //   public const Race = Entity\Race::class;
    //   public const Merit = Entity\Merit::class;
    //   public const Clan = Entity\Clan::class;
}
