<?php

namespace App\Repository;

use App\Entity\RefDocumentIssueType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefDocumentIssueType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefDocumentIssueType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefDocumentIssueType[]    findAll()
 * @method RefDocumentIssueType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefDocumentIssueTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefDocumentIssueType::class);
    }

    // /**
    //  * @return RefDocumentIssueType[] Returns an array of RefDocumentIssueType objects
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
    public function findOneBySomeField($value): ?RefDocumentIssueType
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
