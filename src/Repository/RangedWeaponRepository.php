<?php

namespace App\Repository;

use App\Entity\Item\RangedWeapon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RangedWeapon>
 */
class RangedWeaponRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, RangedWeapon::class);
  }
}
