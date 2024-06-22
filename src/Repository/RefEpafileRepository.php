<?php

namespace App\Repository;

use App\Entity\RefEpafile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefEpafile|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefEpafile|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefEpafile[]    findAll()
 * @method RefEpafile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefEpafileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefEpafile::class);
    }

    // /**
    //  * @return RefEpafile[] Returns an array of RefEpafile objects
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
    public function findOneBySomeField($value): ?RefEpafile
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
