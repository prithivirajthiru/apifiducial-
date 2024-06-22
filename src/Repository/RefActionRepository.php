<?php

namespace App\Repository;

use App\Entity\RefAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefAction[]    findAll()
 * @method RefAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefAction::class);
    }

    // /**
    //  * @return RefAction[] Returns an array of RefAction objects
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
    public function findOneBySomeField($value): ?RefAction
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
