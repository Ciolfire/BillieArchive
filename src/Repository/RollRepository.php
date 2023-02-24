<?php

namespace App\Repository;

use App\Entity\Roll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Roll>
 *
 * @method Roll|null find($id, $lockMode = null, $lockVersion = null)
 * @method Roll|null findOneBy(array $criteria, array $orderBy = null)
 * @method Roll[]    findAll()
 * @method Roll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RollRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Roll::class);
  }

  public function save(Roll $entity, bool $flush = false): void
  {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function remove(Roll $entity, bool $flush = false): void
  {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  //    /**
  //     * @return Roll[] Returns an array of Roll objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('r')
  //            ->andWhere('r.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('r.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Roll
  //    {
  //        return $this->createQueryBuilder('r')
  //            ->andWhere('r.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
