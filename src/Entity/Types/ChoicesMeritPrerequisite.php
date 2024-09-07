<?php

declare(strict_types=1);

namespace App\Entity\Types;

use App\Entity;

final class ChoicesMeritPrerequisite
{
    public const attribute = Entity\Attribute::class;
    public const skill = Entity\Skill::class;
    public const merit = Entity\Merit::class;
    public const discipline = Entity\Discipline::class;
    public const clan = Entity\Clan::class;
    public const potency = "potency";
    public const special = "special";
    //   public const Devotion = Entity\Devotion::class;
    //   public const Race = Entity\Race::class;
}
