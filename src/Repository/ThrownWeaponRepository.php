<?php

namespace App\Repository;

use App\Entity\Item\ThrownWeapon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThrownWeapon>
 */
class ThrownWeaponRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ThrownWeapon::class);
  }
}
