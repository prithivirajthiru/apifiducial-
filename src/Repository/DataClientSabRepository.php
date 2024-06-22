<?php

namespace App\Repository;

use App\Entity\DataClientSab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataClientSab|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataClientSab|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataClientSab[]    findAll()
 * @method DataClientSab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataClientSabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataClientSab::class);
    }

    // /**
    //  * @return DataClientSab[] Returns an array of DataClientSab objects
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
    public function findOneBySomeField($value): ?DataClientSab
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
