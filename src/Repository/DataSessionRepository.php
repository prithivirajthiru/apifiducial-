<?php

namespace App\Repository;

use App\Entity\DataSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataSession[]    findAll()
 * @method DataSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataSession::class);
    }

    // /**
    //  * @return DataSession[] Returns an array of DataSession objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataSession
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
