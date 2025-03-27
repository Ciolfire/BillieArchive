<?php

namespace App\Repository;

use App\Entity\Numen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Numen>
 */
class NumenRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Numen::class);
  }

  //    /**
  //     * @return Numen[] Returns an array of Numen objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('n')
  //            ->andWhere('n.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('n.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Numen
  //    {
  //        return $this->createQueryBuilder('n')
  //            ->andWhere('n.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
