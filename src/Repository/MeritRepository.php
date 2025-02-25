<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Merit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Merit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Merit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Merit[]    findAll()
 * @method Merit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeritRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Merit::class);
  }

  public function findAll(): array
  {
    return $this->findBy([], ['name' => 'ASC', 'category' => 'ASC']);
  }

  public function queryfindByType(string $type)
  {
    $query = $this->createQueryBuilder('m')
      ->innerJoin('m.type', 'ct')
      ->where('ct.name = :type')
      ->orderBy('m.name', 'ASC')
      ->setParameter('type', $type)
    ;
    return $query;
  }

  public function findByType(string $type)
  {
    return $this->createQueryBuilder('m')
      ->innerJoin('m.type', 'ct')
      ->where('ct.name = :type')
      ->orderBy('m.name', 'ASC')
      ->setParameter('type', $type)
      ->getQuery()
      ->getResult()
    ;
  }


  // /**
  //  * @return Merit[] Returns an array of Merit objects
  //  */
  /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

  /*
    public function findOneBySomeField($value): ?Merit
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
