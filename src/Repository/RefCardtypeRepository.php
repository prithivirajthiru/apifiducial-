<?php

namespace App\Repository;

use App\Entity\RefCardtype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefCardtype|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefCardtype|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefCardtype[]    findAll()
 * @method RefCardtype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefCardtypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefCardtype::class);
    }

    // /**
    //  * @return RefCardtype[] Returns an array of RefCardtype objects
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
    public function findOneBySomeField($value): ?RefCardtype
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
