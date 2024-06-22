<?php

namespace App\Repository;

use App\Entity\DataRelance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataRelance|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataRelance|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataRelance[]    findAll()
 * @method DataRelance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataRelanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataRelance::class);
    }

    // /**
    //  * @return DataRelance[] Returns an array of DataRelance objects
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
    public function findOneBySomeField($value): ?DataRelance
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
