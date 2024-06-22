<?php

namespace App\Repository;

use App\Entity\RefCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefCountry|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefCountry|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefCountry[]    findAll()
 * @method RefCountry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefCountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefCountry::class);
    }

    // /**
    //  * @return RefCountry[] Returns an array of RefCountry objects
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
    public function findOneBySomeField($value): ?RefCountry
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
