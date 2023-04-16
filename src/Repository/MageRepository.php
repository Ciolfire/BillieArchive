<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mage[]    findAll()
 * @method Mage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mage::class);
    }

    // /**
    //  * @return Mage[] Returns an array of Mage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mage
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
