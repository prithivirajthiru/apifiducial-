<?php

namespace App\Repository;

use App\Entity\RefFilestatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefFilestatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefFilestatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefFilestatus[]    findAll()
 * @method RefFilestatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefFilestatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefFilestatus::class);
    }

    // /**
    //  * @return RefFilestatus[] Returns an array of RefFilestatus objects
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
    public function findOneBySomeField($value): ?RefFilestatus
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
