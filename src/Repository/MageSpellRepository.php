<?php

namespace App\Repository;

use App\Entity\MageSpell;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MageSpell>
 */
class MageSpellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MageSpell::class);
    }

    public function findByArcanum(int $arcanum)
    {
      return $this->createQueryBuilder('s')
        ->leftJoin('s.arcana', "sa", null, "s.id = sa.spell_id")
        ->andWhere('sa.arcanum = :arcanum')
        ->setParameter('arcanum', $arcanum)
        ->orderBy('s.name', 'ASC')
        ->getQuery()
        ->getResult()
      ;
    }

    public function findByArcanumLevel(int $arcanum, int $level)
    {
      return $this->createQueryBuilder('s')
        ->leftJoin('s.arcana', "sa", null, "s.id = sa.spell_id")
        ->andWhere('sa.level < :level')
        ->andWhere('sa.arcanum = :arcanum')
        ->setParameter('arcanum', $arcanum)
        ->setParameter('level', $level)
        ->orderBy('s.name', 'ASC')
        ->getQuery()
        ->getResult()
      ;
    }

    //    /**
    //     * @return MageSpell[] Returns an array of MageSpell objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MageSpell
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
