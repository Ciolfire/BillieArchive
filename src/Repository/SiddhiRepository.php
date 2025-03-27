<?php

namespace App\Repository;

use App\Entity\Siddhi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Siddhi>
 */
class SiddhiRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Siddhi::class);
  }

  //    /**
  //     * @return Siddhi[] Returns an array of Siddhi objects
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

  //    public function findOneBySomeField($value): ?Siddhi
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
