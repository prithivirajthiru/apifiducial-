<?php

namespace App\Repository;

use App\Entity\RefVariable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefVariable|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefVariable|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefVariable[]    findAll()
 * @method RefVariable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefVariableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefVariable::class);
    }

    // /**
    //  * @return RefVariable[] Returns an array of RefVariable objects
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
    public function findOneBySomeField($value): ?RefVariable
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
