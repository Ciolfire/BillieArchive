<?php

namespace App\Repository;

use App\Entity\Chronicle;
use App\Entity\MageOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MageOrder>
 */
class MageOrderRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, MageOrder::class);
  }

  // /**
  //  * @return MageOrder[] Returns an array of MageOrder objects
  //  */
  // public function findByExampleField($value): array
  // {
  //   return $this->createQueryBuilder('o')
  //     ->andWhere('o.exampleField = :val')
  //     ->setParameter('val', $value)
  //     ->orderBy('o.id', 'ASC')
  //     ->setMaxResults(10)
  //     ->getQuery()
  //     ->getResult()
  //   ;
  // }

  // public function findOneBySomeField($value): ?MageOrder
  // {
  //   return $this->createQueryBuilder('o')
  //     ->andWhere('o.exampleField = :val')
  //     ->setParameter('val', $value)
  //     ->getQuery()
  //     ->getOneOrNullResult()
  //   ;
  // }

  /**
   * @return Order[] Returns an array of Clan objects
   */
  public function findAllByChronicle(?Chronicle $chronicle = null): array
  {
    return $this->createQueryBuilder('o')
      ->andWhere('o.homebrewFor IS NULL OR o.homebrewFor = :chronicle')
      ->orderBy('o.name', 'ASC')
      ->setParameter('chronicle', $chronicle)
      ->getQuery()
      ->getResult();
  }
}
