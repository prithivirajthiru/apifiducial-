<?php

namespace App\Repository;

use App\Entity\RefOfferContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefOfferContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefOfferContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefOfferContent[]    findAll()
 * @method RefOfferContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefOfferContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefOfferContent::class);
    }

    // /**
    //  * @return RefOfferContent[] Returns an array of RefOfferContent objects
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
    public function findOneBySomeField($value): ?RefOfferContent
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
