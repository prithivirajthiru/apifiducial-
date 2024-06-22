<?php

namespace App\Repository;

use App\Entity\RefTypeclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefTypeclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefTypeclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefTypeclient[]    findAll()
 * @method RefTypeclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefTypeclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefTypeclient::class);
    }

    // /**
    //  * @return RefTypeclient[] Returns an array of RefTypeclient objects
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
    public function findOneBySomeField($value): ?RefTypeclient
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
