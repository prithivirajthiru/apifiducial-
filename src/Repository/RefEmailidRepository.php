<?php

namespace App\Repository;

use App\Entity\RefEmailid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefEmailid|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefEmailid|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefEmailid[]    findAll()
 * @method RefEmailid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefEmailidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefEmailid::class);
    }

    // /**
    //  * @return RefEmailid[] Returns an array of RefEmailid objects
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
    public function findOneBySomeField($value): ?RefEmailid
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
