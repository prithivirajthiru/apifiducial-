<?php

namespace App\Repository;

use App\Entity\RefDebittype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefDebittype|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefDebittype|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefDebittype[]    findAll()
 * @method RefDebittype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefDebittypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefDebittype::class);
    }

    // /**
    //  * @return RefDebittype[] Returns an array of RefDebittype objects
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
    public function findOneBySomeField($value): ?RefDebittype
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
