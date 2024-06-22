<?php

namespace App\Repository;

use App\Entity\DataClientWhereclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataClientWhereclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataClientWhereclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataClientWhereclient[]    findAll()
 * @method DataClientWhereclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataClientWhereclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataClientWhereclient::class);
    }

    // /**
    //  * @return DataClientWhereclient[] Returns an array of DataClientWhereclient objects
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
    public function findOneBySomeField($value): ?DataClientWhereclient
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
