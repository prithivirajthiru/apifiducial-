<?php

namespace App\Repository;

use App\Entity\DataDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataDocument[]    findAll()
 * @method DataDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataDocument::class);
    }

    // /**
    //  * @return DataDocument[] Returns an array of DataDocument objects
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
    public function findOneBySomeField($value): ?DataDocument
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
