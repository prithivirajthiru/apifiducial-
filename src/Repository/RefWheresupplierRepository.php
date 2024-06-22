<?php

namespace App\Repository;

use App\Entity\RefWheresupplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefWheresupplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefWheresupplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefWheresupplier[]    findAll()
 * @method RefWheresupplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefWheresupplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefWheresupplier::class);
    }

    // /**
    //  * @return RefWheresupplier[] Returns an array of RefWheresupplier objects
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
    public function findOneBySomeField($value): ?RefWheresupplier
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
