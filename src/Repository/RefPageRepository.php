<?php

namespace App\Repository;

use App\Entity\RefPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefPage[]    findAll()
 * @method RefPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefPage::class);
    }

    // /**
    //  * @return RefPage[] Returns an array of RefPage objects
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
    public function findOneBySomeField($value): ?RefPage
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
