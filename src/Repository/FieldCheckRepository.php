<?php

namespace App\Repository;

use App\Entity\FieldCheck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FieldCheck|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldCheck|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldCheck[]    findAll()
 * @method FieldCheck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldCheckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldCheck::class);
    }

    // /**
    //  * @return FieldCheck[] Returns an array of FieldCheck objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FieldCheck
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
