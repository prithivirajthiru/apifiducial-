<?php

namespace App\Repository;

use App\Entity\RefRequeststatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefRequeststatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefRequeststatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefRequeststatus[]    findAll()
 * @method RefRequeststatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefRequeststatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefRequeststatus::class);
    }

    // /**
    //  * @return RefRequeststatus[] Returns an array of RefRequeststatus objects
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
    public function findOneBySomeField($value): ?RefRequeststatus
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
