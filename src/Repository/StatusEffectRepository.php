<?php

namespace App\Repository;

use App\Entity\StatusEffect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StatusEffect>
 */
class StatusEffectRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, StatusEffect::class);
  }

  //    /**
  //     * @return StatusEffect[] Returns an array of StatusEffect objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('s.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?StatusEffect
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
