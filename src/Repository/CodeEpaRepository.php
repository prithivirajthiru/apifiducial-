<?php

namespace App\Repository;

use App\Entity\CodeEpa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodeEpa|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeEpa|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeEpa[]    findAll()
 * @method CodeEpa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeEpaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeEpa::class);
    }

    // /**
    //  * @return CodeEpa[] Returns an array of CodeEpa objects
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
    public function findOneBySomeField($value): ?CodeEpa
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
