<?php

namespace App\Repository;

use App\Entity\Chronicle;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 *
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Note::class);
  }

  public function save(Note $entity, bool $flush = false): void
  {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function remove(Note $entity, bool $flush = false): void
  {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  //    /**
  //     * @return Note[] Returns an array of Note objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('n')
  //            ->andWhere('n.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('n.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  /**
   * @return Note[] Returns an array of Note objects
   */
  public function findByLinkable(User $user, Note $note): array
  {
    if (is_null($note->getId())) {
      $id = 0;
    } else {
      $id = $note->getId();
    }

    return $this->createQueryBuilder('n')
      ->andWhere('n.chronicle = :chronicle')
      ->andWhere('n.user = :user')
      ->andWhere('n.id != :id')
      ->setParameter('chronicle', $note->getChronicle()->getId(), Types::INTEGER)
      ->setParameter('user', $user->getId(), Types::INTEGER)
      ->setParameter('id', $id, Types::INTEGER)
      ->orderBy('n.category', 'ASC')
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Note[] Returns an array of Note objects
   */
  public function findFromSearch(string $search, User $user, ?Chronicle $chronicle): array
  {
    $qb = $this->createQueryBuilder('n');
    return $qb
      ->Where('n.chronicle = :chronicle')
      ->andWhere('n.user = :user')
      ->andWhere($qb->expr()->orX($qb->expr()->like('n.content', ':search'), $qb->expr()->like('n.title', ':search')))
      // ->andWhere('(n.title LIKE "%:search%" OR n.content LIKE %:search% OR n.category.name LIKE %:search%)')
      ->setParameter('chronicle', $chronicle->getId(), Types::INTEGER)
      ->setParameter('user', $user->getId(), Types::INTEGER)
      ->setParameter('search', "%".$search."%", Types::STRING)
      ->orderBy('n.category', 'ASC')
      ->getQuery()
      ->getResult();
  }

  //    public function findOneBySomeField($value): ?Note
  //    {
  //        return $this->createQueryBuilder('n')
  //            ->andWhere('n.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
