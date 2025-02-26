<?php

namespace App\Repository;

use App\Entity\BloodBathFacet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BloodBathFacet>
 */
class BloodBathFacetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BloodBathFacet::class);
    }

    public function queryfindByFacet(string $facet)
    {
      $query = $this->createQueryBuilder('m')
        ->where('m.facet = :facet')
        ->orderBy('m.label', 'ASC')
        ->setParameter('facet', $facet)
      ;
      return $query;
    }

    //    /**
    //     * @return BloodBathFacet[] Returns an array of BloodBathFacet objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BloodBathFacet
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
