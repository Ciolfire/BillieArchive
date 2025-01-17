<?php

namespace App\Repository;

use App\Entity\Thaumaturge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterMinorTemplate>
 *
 * @method CharacterMinorTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterMinorTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterMinorTemplate[]    findAll()
 * @method CharacterMinorTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThaumaturgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thaumaturge::class);
    }

//    /**
//     * @return CharacterMinorTemplate[] Returns an array of CharacterMinorTemplate objects
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

//    public function findOneBySomeField($value): ?CharacterMinorTemplate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
