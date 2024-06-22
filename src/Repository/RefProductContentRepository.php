<?php

namespace App\Repository;

use App\Entity\RefProductContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefProductContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefProductContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefProductContent[]    findAll()
 * @method RefProductContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefProductContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefProductContent::class);
    }

    // /**
    //  * @return RefProductContent[] Returns an array of RefProductContent objects
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
    public function findOneBySomeField($value): ?RefProductContent
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
