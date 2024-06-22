<?php

namespace App\Repository;

use App\Entity\RefWhoclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefWhoclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefWhoclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefWhoclient[]    findAll()
 * @method RefWhoclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefWhoclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefWhoclient::class);
    }

    // /**
    //  * @return RefWhoclient[] Returns an array of RefWhoclient objects
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
    public function findOneBySomeField($value): ?RefWhoclient
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
