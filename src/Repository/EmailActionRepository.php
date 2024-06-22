<?php

namespace App\Repository;

use App\Entity\EmailAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailAction[]    findAll()
 * @method EmailAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailAction::class);
    }

    // /**
    //  * @return EmailAction[] Returns an array of EmailAction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailAction
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
