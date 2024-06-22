<?php

namespace App\Repository;

use App\Entity\DataRequestRequeststatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataRequestRequeststatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataRequestRequeststatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataRequestRequeststatus[]    findAll()
 * @method DataRequestRequeststatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataRequestRequeststatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataRequestRequeststatus::class);
    }

    // /**
    //  * @return DataRequestRequeststatus[] Returns an array of DataRequestRequeststatus objects
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
    public function findOneBySomeField($value): ?DataRequestRequeststatus
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
