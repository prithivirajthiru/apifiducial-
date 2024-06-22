<?php

namespace App\Repository;

use App\Entity\RefEmailStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefEmailStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefEmailStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefEmailStatus[]    findAll()
 * @method RefEmailStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefEmailStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefEmailStatus::class);
    }

    // /**
    //  * @return RefEmailStatus[] Returns an array of RefEmailStatus objects
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
    public function findOneBySomeField($value): ?RefEmailStatus
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
