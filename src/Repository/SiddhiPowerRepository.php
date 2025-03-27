<?php

namespace App\Repository;

use App\Entity\SiddhiPower;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SiddhiPower>
 */
class SiddhiPowerRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, SiddhiPower::class);
  }

  //    /**
  //     * @return SiddhiPower[] Returns an array of SiddhiPower objects
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

  //    public function findOneBySomeField($value): ?SiddhiPower
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
