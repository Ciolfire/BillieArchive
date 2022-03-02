<?php

namespace App\Repository;

use App\Entity\Vice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vice[]    findAll()
 * @method Vice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vice::class);
    }

    // /**
    //  * @return Vice[] Returns an array of Vice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vice
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
