<?php

namespace App\Repository;

use App\Entity\ContentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContentType>
 *
 * @method ContentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentType[]    findAll()
 * @method ContentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentTypeRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ContentType::class);
  }

  //    /**
  //     * @return ContentType[] Returns an array of ContentType objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('s.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?ContentType
  //    {
  //        return $this->createQueryBuilder('s')
  //            ->andWhere('s.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
