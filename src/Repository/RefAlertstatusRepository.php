<?php

namespace App\Repository;

use App\Entity\RefAlertstatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefAlertstatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefAlertstatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefAlertstatus[]    findAll()
 * @method RefAlertstatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefAlertstatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefAlertstatus::class);
    }

    // /**
    //  * @return RefAlertstatus[] Returns an array of RefAlertstatus objects
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
    public function findOneBySomeField($value): ?RefAlertstatus
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
