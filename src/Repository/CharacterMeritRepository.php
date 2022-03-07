<?php

namespace App\Repository;

use App\Entity\CharacterMerit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterMerit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterMerit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterMerit[]    findAll()
 * @method CharacterMerit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterMeritRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterMerit::class);
    }

    // /**
    //  * @return CharacterMerit[] Returns an array of CharacterMerit objects
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
    public function findOneBySomeField($value): ?CharacterMerit
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
