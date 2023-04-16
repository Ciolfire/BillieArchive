<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Werewolf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Werewolf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Werewolf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Werewolf[]    findAll()
 * @method Werewolf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WerewolfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Werewolf::class);
    }

    // /**
    //  * @return Werewolf[] Returns an array of Werewolf objects
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
    public function findOneBySomeField($value): ?Werewolf
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
