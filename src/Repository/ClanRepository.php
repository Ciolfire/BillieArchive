<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Clan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clan[]    findAll()
 * @method Clan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClanRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Clan::class);
  }

  // /**
  //  * @return Clan[] Returns an array of Clan objects
  //  */
  /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

  /**
   * @return Clan[] Returns an array of Clan objects
   */
  public function findByBook(Book $book) : array
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.isBloodline = false')
      ->andWhere('c.book = :book')
      ->orderBy('c.name', 'ASC')
      ->setParameter('book', $book)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Clan[] Returns an array of Clan objects
   */
  public function findAllBloodlines() : array
  {
    return $this->createQueryBuilder('c')
      ->andWhere('c.isBloodline = true')
      ->orderBy('c.name', 'ASC')
      ->getQuery()
      ->getResult();
  }

  /*
    public function findOneBySomeField($value): ?Clan
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
