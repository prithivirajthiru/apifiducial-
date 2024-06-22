<?php

namespace App\Repository;

use App\Entity\DataOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataOffer[]    findAll()
 * @method DataOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataOffer::class);
    }

    // /**
    //  * @return DataOffer[] Returns an array of DataOffer objects
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
    public function findOneBySomeField($value): ?DataOffer
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
