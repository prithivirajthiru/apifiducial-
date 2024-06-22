<?php

namespace App\Repository;

use App\Entity\RefWhereclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefWhereclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefWhereclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefWhereclient[]    findAll()
 * @method RefWhereclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefWhereclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefWhereclient::class);
    }

    // /**
    //  * @return RefWhereclient[] Returns an array of RefWhereclient objects
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
    public function findOneBySomeField($value): ?RefWhereclient
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
