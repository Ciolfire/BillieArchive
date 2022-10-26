<?php

namespace App\Repository;

use App\Entity\CharacterSpecialty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CharacterSpecialty|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterSpecialty|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterSpecialty[]    findAll()
 * @method CharacterSpecialty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterSpecialtyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterSpecialty::class);
    }

    // /**
    //  * @return CharacterSpecialty[] Returns an array of CharacterSpecialty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CharacterSpecialty
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
