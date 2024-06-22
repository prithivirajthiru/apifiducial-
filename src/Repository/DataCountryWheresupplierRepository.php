<?php

namespace App\Repository;

use App\Entity\DataCountryWheresupplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataCountryWheresupplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCountryWheresupplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCountryWheresupplier[]    findAll()
 * @method DataCountryWheresupplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCountryWheresupplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCountryWheresupplier::class);
    }

    // /**
    //  * @return DataCountryWheresupplier[] Returns an array of DataCountryWheresupplier objects
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
    public function findOneBySomeField($value): ?DataCountryWheresupplier
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
