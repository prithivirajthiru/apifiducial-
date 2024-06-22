<?php

namespace App\Repository;

use App\Entity\RefTown;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefTown|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefTown|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefTown[]    findAll()
 * @method RefTown[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefTownRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefTown::class);
    }

    // /**
    //  * @return RefTown[] Returns an array of RefTown objects
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
    public function findOneBySomeField($value): ?RefTown
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
