<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\DisciplinePower;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DisciplinePower|null find($id, $lockMode = null, $lockVersion = null)
 * @method DisciplinePower|null findOneBy(array $criteria, array $orderBy = null)
 * @method DisciplinePower[]    findAll()
 * @method DisciplinePower[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisciplinePowerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DisciplinePower::class);
    }

    // /**
    //  * @return DisciplinePower[] Returns an array of DisciplinePower objects
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
    public function findOneBySomeField($value): ?DisciplinePower
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
