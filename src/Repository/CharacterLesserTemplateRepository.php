<?php

namespace App\Repository;

use App\Entity\CharacterLesserTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterLesserTemplate>
 *
 * @method CharacterLesserTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterLesserTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterLesserTemplate[]    findAll()
 * @method CharacterLesserTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterLesserTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CharacterLesserTemplate::class);
    }

//    /**
//     * @return CharacterLesserTemplate[] Returns an array of CharacterLesserTemplate objects
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

//    public function findOneBySomeField($value): ?CharacterLesserTemplate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
