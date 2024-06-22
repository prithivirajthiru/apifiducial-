<?php

namespace App\Repository;

use App\Entity\DataEloquaCdo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataEloquaCdo|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataEloquaCdo|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataEloquaCdo[]    findAll()
 * @method DataEloquaCdo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataEloquaCdoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataEloquaCdo::class);
    }

    // /**
    //  * @return DataEloquaCdo[] Returns an array of DataEloquaCdo objects
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
    public function findOneBySomeField($value): ?DataEloquaCdo
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
