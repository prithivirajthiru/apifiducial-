<?php

namespace App\Repository;

use App\Entity\RefProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefProduct[]    findAll()
 * @method RefProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefProduct::class);
    }

    // /**
    //  * @return RefProduct[] Returns an array of RefProduct objects
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
    public function findOneBySomeField($value): ?RefProduct
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
