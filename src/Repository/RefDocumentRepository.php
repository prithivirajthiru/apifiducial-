<?php

namespace App\Repository;

use App\Entity\RefDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefDocument[]    findAll()
 * @method RefDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefDocument::class);
    }

    // /**
    //  * @return RefDocument[] Returns an array of RefDocument objects
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
    public function findOneBySomeField($value): ?RefDocument
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
