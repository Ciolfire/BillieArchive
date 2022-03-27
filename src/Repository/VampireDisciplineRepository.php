<?php

namespace App\Repository;

use App\Entity\VampireDiscipline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VampireDiscipline|null find($id, $lockMode = null, $lockVersion = null)
 * @method VampireDiscipline|null findOneBy(array $criteria, array $orderBy = null)
 * @method VampireDiscipline[]    findAll()
 * @method VampireDiscipline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VampireDisciplineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VampireDiscipline::class);
    }

    // /**
    //  * @return VampireDiscipline[] Returns an array of VampireDiscipline objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VampireDiscipline
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
