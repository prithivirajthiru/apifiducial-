<?php

namespace App\Repository;

use App\Entity\RefDocumentAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefDocumentAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefDocumentAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefDocumentAction[]    findAll()
 * @method RefDocumentAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefDocumentActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefDocumentAction::class);
    }

    // /**
    //  * @return RefDocumentAction[] Returns an array of RefDocumentAction objects
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
    public function findOneBySomeField($value): ?RefDocumentAction
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
