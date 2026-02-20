<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Chronicle;
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
  public function findAllBloodlines(?Chronicle $chronicle = null) : array
  {
    $isAncient = false;
    if ($chronicle) {
      $isAncient = $chronicle->isAncient();
    }
    return $this->createQueryBuilder('c')
      ->andWhere('c.isBloodline = true')
      ->andWhere('c.homebrewFor IS NULL OR c.homebrewFor = :chronicle')
      ->andWhere('c.isAncient IS NULL OR c.isAncient = :isAncient')
      ->orderBy('c.name', 'ASC')
      ->setParameter('chronicle', $chronicle)
      ->setParameter('isAncient', $isAncient)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Clan[] Returns an array of Clan objects
   */
  public function findBloodlinesByClan(Clan $clan, ?Chronicle $chronicle = null) : array
  {
    $isAncient = false;
    if ($chronicle) {
      $isAncient = $chronicle->isAncient();
    }
    return $this->createQueryBuilder('c')
      ->andWhere('c.isBloodline = true')
      ->andWhere('c.parentClan IS NULL OR c.parentClan = :clan')
      ->andWhere('c.homebrewFor IS NULL OR c.homebrewFor = :chronicle')
      ->andWhere('c.isAncient IS NULL OR c.isAncient = :isAncient')
      ->orderBy('c.name', 'ASC')
      ->setParameter('clan', $clan)
      ->setParameter('chronicle', $chronicle)
      ->setParameter('isAncient', $isAncient)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Clan[] Returns an array of Clan objects
   */
  public function findAllClan(?Chronicle $chronicle = null, $isAncient = false) : array
  {
    if ($chronicle) {
      $isAncient = $chronicle->isAncient();
    }

    return $this->createQueryBuilder('c')
      ->andWhere('c.isBloodline = false')
      ->andWhere('c.homebrewFor IS NULL OR c.homebrewFor = :chronicle')
      ->andWhere('c.isAncient IS NULL OR c.isAncient = :isAncient')
      ->orderBy('c.name', 'ASC')
      ->setParameter('chronicle', $chronicle)
      ->setParameter('isAncient', $isAncient)
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
