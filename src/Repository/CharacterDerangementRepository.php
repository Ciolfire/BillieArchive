<?php

namespace App\Repository;

use App\Entity\CharacterDerangement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterDerangement>
 *
 * @method CharacterDerangement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterDerangement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterDerangement[]    findAll()
 * @method CharacterDerangement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterDerangementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterDerangement::class);
    }

//    /**
//     * @return CharacterDerangement[] Returns an array of CharacterDerangement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CharacterDerangement
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
