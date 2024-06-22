<?php

namespace App\Repository;

use App\Entity\RefOfferType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefOfferType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefOfferType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefOfferType[]    findAll()
 * @method RefOfferType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefOfferTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefOfferType::class);
    }

    // /**
    //  * @return RefOfferType[] Returns an array of RefOfferType objects
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
    public function findOneBySomeField($value): ?RefOfferType
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
