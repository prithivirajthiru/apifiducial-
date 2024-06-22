<?php

namespace App\Repository;

use App\Entity\DataTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataTransaction[]    findAll()
 * @method DataTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataTransaction::class);
    }

    // /**
    //  * @return DataTransaction[] Returns an array of DataTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataTransaction
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
