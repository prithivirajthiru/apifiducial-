<?php

namespace App\Repository;

use App\Entity\DataFid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataFid|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataFid|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataFid[]    findAll()
 * @method DataFid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataFidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataFid::class);
    }

    // /**
    //  * @return DataFid[] Returns an array of DataFid objects
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
    public function findOneBySomeField($value): ?DataFid
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
