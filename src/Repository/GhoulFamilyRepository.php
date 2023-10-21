<?php

namespace App\Repository;

use App\Entity\GhoulFamily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GhoulFamily>
 *
 * @method GhoulFamily|null find($id, $lockMode = null, $lockVersion = null)
 * @method GhoulFamily|null findOneBy(array $criteria, array $orderBy = null)
 * @method GhoulFamily[]    findAll()
 * @method GhoulFamily[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GhoulFamilyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GhoulFamily::class);
    }

//    /**
//     * @return GhoulFamily[] Returns an array of GhoulFamily objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GhoulFamily
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
