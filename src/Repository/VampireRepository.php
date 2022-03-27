<?php

namespace App\Repository;

use App\Entity\Vampire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vampire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vampire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vampire[]    findAll()
 * @method Vampire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VampireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vampire::class);
    }

    // /**
    //  * @return Vampire[] Returns an array of Vampire objects
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
    public function findOneBySomeField($value): ?Vampire
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
