<?php

namespace App\Repository;

use App\Entity\DataUserspace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataUserspace|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataUserspace|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataUserspace[]    findAll()
 * @method DataUserspace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataUserspaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataUserspace::class);
    }

    // /**
    //  * @return DataUserspace[] Returns an array of DataUserspace objects
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
    public function findOneBySomeField($value): ?DataUserspace
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
