<?php

namespace App\Repository;

use App\Entity\RefLegalformfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefLegalformfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefLegalformfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefLegalformfile[]    findAll()
 * @method RefLegalformfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefLegalformfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefLegalformfile::class);
    }

    // /**
    //  * @return RefLegalformfile[] Returns an array of RefLegalformfile objects
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
    public function findOneBySomeField($value): ?RefLegalformfile
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
