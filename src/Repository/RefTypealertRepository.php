<?php

namespace App\Repository;

use App\Entity\RefTypealert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefTypealert|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefTypealert|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefTypealert[]    findAll()
 * @method RefTypealert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefTypealertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefTypealert::class);
    }

    // /**
    //  * @return RefTypealert[] Returns an array of RefTypealert objects
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
    public function findOneBySomeField($value): ?RefTypealert
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
