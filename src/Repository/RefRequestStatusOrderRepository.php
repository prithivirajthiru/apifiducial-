<?php

namespace App\Repository;

use App\Entity\RefRequestStatusOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefRequestStatusOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefRequestStatusOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefRequestStatusOrder[]    findAll()
 * @method RefRequestStatusOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefRequestStatusOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefRequestStatusOrder::class);
    }

    // /**
    //  * @return RefRequestStatusOrder[] Returns an array of RefRequestStatusOrder objects
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
    public function findOneBySomeField($value): ?RefRequestStatusOrder
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
