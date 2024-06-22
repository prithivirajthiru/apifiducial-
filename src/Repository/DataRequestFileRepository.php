<?php

namespace App\Repository;

use App\Entity\DataRequestFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataRequestFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataRequestFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataRequestFile[]    findAll()
 * @method DataRequestFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataRequestFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataRequestFile::class);
    }

    // /**
    //  * @return DataRequestFile[] Returns an array of DataRequestFile objects
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
    public function findOneBySomeField($value): ?DataRequestFile
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
