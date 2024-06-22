<?php

namespace App\Repository;

use App\Entity\DataFieldIssue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataFieldIssue|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataFieldIssue|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataFieldIssue[]    findAll()
 * @method DataFieldIssue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataFieldIssueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataFieldIssue::class);
    }

    // /**
    //  * @return DataFieldIssue[] Returns an array of DataFieldIssue objects
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
    public function findOneBySomeField($value): ?DataFieldIssue
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
