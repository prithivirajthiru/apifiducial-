<?php

namespace App\Repository;

use App\Entity\RefFunction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefFunction|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefFunction|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefFunction[]    findAll()
 * @method RefFunction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefFunctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefFunction::class);
    }

    // /**
    //  * @return RefFunction[] Returns an array of RefFunction objects
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
    public function findOneBySomeField($value): ?RefFunction
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
