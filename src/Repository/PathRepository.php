<?php

namespace App\Repository;

use App\Entity\Chronicle;
use App\Entity\Path;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Path>
 */
class PathRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Path::class);
  }

  // /**
  //  * @return Path[] Returns an array of Path objects
  //  */
  // public function findByExampleField($value): array
  // {
  //   return $this->createQueryBuilder('p')
  //     ->andWhere('p.exampleField = :val')
  //     ->setParameter('val', $value)
  //     ->orderBy('p.id', 'ASC')
  //     ->setMaxResults(10)
  //     ->getQuery()
  //     ->getResult()
  //   ;
  // }

  // public function findOneBySomeField($value): ?Path
  // {
  //   return $this->createQueryBuilder('p')
  //     ->andWhere('p.exampleField = :val')
  //     ->setParameter('val', $value)
  //     ->getQuery()
  //     ->getOneOrNullResult()
  //   ;
  // }


  /**
   * @return Path[] Returns an array of Clan objects
   */
  public function findAllByChronicle(?Chronicle $chronicle = null): array
  {
    return $this->createQueryBuilder('p')
      ->andWhere('p.homebrewFor IS NULL OR p.homebrewFor = :chronicle')
      ->orderBy('p.name', 'ASC')
      ->setParameter('chronicle', $chronicle)
      ->getQuery()
      ->getResult();
  }
}
