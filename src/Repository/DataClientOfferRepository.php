<?php

namespace App\Repository;

use App\Entity\DataClientOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataClientOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataClientOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataClientOffer[]    findAll()
 * @method DataClientOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataClientOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataClientOffer::class);
    }

    // /**
    //  * @return DataClientOffer[] Returns an array of DataClientOffer objects
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
    public function findOneBySomeField($value): ?DataClientOffer
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
