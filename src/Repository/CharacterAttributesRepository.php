<?php

namespace App\Repository;

use App\Entity\CharacterAttributes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterAttributes|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterAttributes|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterAttributes[]    findAll()
 * @method CharacterAttributes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterAttributesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterAttributes::class);
    }

    // /**
    //  * @return CharacterAttributes[] Returns an array of CharacterAttributes objects
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
    public function findOneBySomeField($value): ?CharacterAttributes
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
