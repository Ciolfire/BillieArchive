<?php

namespace App\Repository;

use App\Entity\CharacterAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterAccess>
 *
 * @method CharacterAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterAccess[]    findAll()
 * @method CharacterAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterAccess::class);
    }

//    /**
//     * @return CharacterAccess[] Returns an array of CharacterAccess objects
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

//    public function findOneBySomeField($value): ?CharacterAccess
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
