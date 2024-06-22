<?php

namespace App\Repository;

use App\Entity\RefFid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefFid|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefFid|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefFid[]    findAll()
 * @method RefFid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefFidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefFid::class);
    }

    // /**
    //  * @return RefFid[] Returns an array of RefFid objects
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
    public function findOneBySomeField($value): ?RefFid
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
