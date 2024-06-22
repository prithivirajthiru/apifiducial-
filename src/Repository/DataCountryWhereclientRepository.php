<?php

namespace App\Repository;

use App\Entity\DataCountryWhereclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataCountryWhereclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCountryWhereclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCountryWhereclient[]    findAll()
 * @method DataCountryWhereclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCountryWhereclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCountryWhereclient::class);
    }

    // /**
    //  * @return DataCountryWhereclient[] Returns an array of DataCountryWhereclient objects
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
    public function findOneBySomeField($value): ?DataCountryWhereclient
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
