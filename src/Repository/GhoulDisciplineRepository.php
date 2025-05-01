<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\GhoulDiscipline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GhoulDiscipline|null find($id, $lockMode = null, $lockVersion = null)
 * @method GhoulDiscipline|null findOneBy(array $criteria, array $orderBy = null)
 * @method GhoulDiscipline[]    findAll()
 * @method GhoulDiscipline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GhoulDisciplineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GhoulDiscipline::class);
    }

    // /**
    //  * @return GhoulDiscipline[] Returns an array of GhoulDiscipline objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GhoulDiscipline
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
