<?php

namespace App\Repository;

use App\Entity\CharacterInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterInfo>
 *
 * @method CharacterInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterInfo[]    findAll()
 * @method CharacterInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterInfo::class);
    }

//    /**
//     * @return CharacterInfo[] Returns an array of CharacterInfo objects
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

//    public function findOneBySomeField($value): ?CharacterInfo
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
