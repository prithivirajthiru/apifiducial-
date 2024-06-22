<?php

namespace App\Repository;

use App\Entity\RefOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefOffer[]    findAll()
 * @method RefOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefOffer::class);
    }

    // /**
    //  * @return RefOffer[] Returns an array of RefOffer objects
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
    public function findOneBySomeField($value): ?RefOffer
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
