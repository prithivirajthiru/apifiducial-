<?php

namespace App\Repository;

use App\Entity\DataAttorney;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataAttorney|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataAttorney|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataAttorney[]    findAll()
 * @method DataAttorney[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataAttorneyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataAttorney::class);
    }

    // /**
    //  * @return DataAttorney[] Returns an array of DataAttorney objects
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
    public function findOneBySomeField($value): ?DataAttorney
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
