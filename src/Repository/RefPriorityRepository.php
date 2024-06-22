<?php

namespace App\Repository;

use App\Entity\RefPriority;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefPriority|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefPriority|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefPriority[]    findAll()
 * @method RefPriority[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefPriorityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefPriority::class);
    }

    // /**
    //  * @return RefPriority[] Returns an array of RefPriority objects
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
    public function findOneBySomeField($value): ?RefPriority
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
