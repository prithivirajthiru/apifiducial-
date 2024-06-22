<?php

namespace App\Repository;

use App\Entity\DataDocumenttype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataDocumenttype|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataDocumenttype|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataDocumenttype[]    findAll()
 * @method DataDocumenttype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataDocumenttypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataDocumenttype::class);
    }

    // /**
    //  * @return DataDocumenttype[] Returns an array of DataDocumenttype objects
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
    public function findOneBySomeField($value): ?DataDocumenttype
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
