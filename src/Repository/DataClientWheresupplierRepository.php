<?php

namespace App\Repository;

use App\Entity\DataClientWheresupplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataClientWheresupplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataClientWheresupplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataClientWheresupplier[]    findAll()
 * @method DataClientWheresupplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataClientWheresupplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataClientWheresupplier::class);
    }

    // /**
    //  * @return DataClientWheresupplier[] Returns an array of DataClientWheresupplier objects
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
    public function findOneBySomeField($value): ?DataClientWheresupplier
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
