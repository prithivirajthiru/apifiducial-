<?php

namespace App\Repository;

use App\Entity\DataDocumentaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataDocumentaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataDocumentaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataDocumentaction[]    findAll()
 * @method DataDocumentaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataDocumentactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataDocumentaction::class);
    }

    // /**
    //  * @return DataDocumentaction[] Returns an array of DataDocumentaction objects
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
    public function findOneBySomeField($value): ?DataDocumentaction
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
