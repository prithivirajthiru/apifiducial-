<?php

namespace App\Repository;

use App\Entity\RefLabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefLabel[]    findAll()
 * @method RefLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefLabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefLabel::class);
    }

    // /**
    //  * @return RefLabel[] Returns an array of RefLabel objects
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
    public function findOneBySomeField($value): ?RefLabel
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
