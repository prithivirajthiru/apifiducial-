<?php

namespace App\Repository;

use App\Entity\RefEpa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefEpa|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefEpa|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefEpa[]    findAll()
 * @method RefEpa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefEpaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefEpa::class);
    }

    // /**
    //  * @return RefEpa[] Returns an array of RefEpa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefEpa
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
