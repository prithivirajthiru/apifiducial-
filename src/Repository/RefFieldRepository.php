<?php

namespace App\Repository;

use App\Entity\RefField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefField|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefField|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefField[]    findAll()
 * @method RefField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefField::class);
    }

    // /**
    //  * @return RefField[] Returns an array of RefField objects
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
    public function findOneBySomeField($value): ?RefField
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
