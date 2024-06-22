<?php

namespace App\Repository;

use App\Entity\DataComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataComment[]    findAll()
 * @method DataComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataComment::class);
    }

    // /**
    //  * @return DataComment[] Returns an array of DataComment objects
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
    public function findOneBySomeField($value): ?DataComment
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
