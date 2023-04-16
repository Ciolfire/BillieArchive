<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\CharacterNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CharacterNote>
 *
 * @method CharacterNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterNote[]    findAll()
 * @method CharacterNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterNoteRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, CharacterNote::class);
  }

  public function save(CharacterNote $entity, bool $flush = false): void
  {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function remove(CharacterNote $entity, bool $flush = false): void
  {
    $this->getEntityManager()->remove($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  //    /**
  //     * @return CharacterNote[] Returns an array of CharacterNote objects
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

  //    public function findOneBySomeField($value): ?CharacterNote
  //    {
  //        return $this->createQueryBuilder('c')
  //            ->andWhere('c.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
