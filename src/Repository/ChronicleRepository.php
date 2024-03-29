<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Chronicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chronicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chronicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chronicle[]    findAll()
 * @method Chronicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChronicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chronicle::class);
    }

    // /**
    //  * @return Chronicle[] Returns an array of Chronicle objects
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

    /*
    public function findOneBySomeField($value): ?Chronicle
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
