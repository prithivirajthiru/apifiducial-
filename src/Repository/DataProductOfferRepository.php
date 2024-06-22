<?php

namespace App\Repository;

use App\Entity\DataProductOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataProductOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataProductOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataProductOffer[]    findAll()
 * @method DataProductOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataProductOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataProductOffer::class);
    }

    // /**
    //  * @return DataProductOffer[] Returns an array of DataProductOffer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataProductOffer
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
