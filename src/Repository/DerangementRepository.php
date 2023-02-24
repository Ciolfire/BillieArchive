<?php

namespace App\Repository;

use App\Entity\Derangement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Derangement>
 *
 * @method Derangement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Derangement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Derangement[]    findAll()
 * @method Derangement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DerangementRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Derangement::class);
  }

  public function save(Derangement $entity, bool $flush = false): void
  {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function remove(Derangement $entity, bool $flush = false): void
  {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  //    /**
  //     * @return Derangement[] Returns an array of Derangement objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('d')
  //            ->andWhere('d.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('d.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Derangement
  //    {
  //        return $this->createQueryBuilder('d')
  //            ->andWhere('d.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
