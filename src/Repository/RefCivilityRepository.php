<?php

namespace App\Repository;

use App\Entity\RefCivility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefCivility|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefCivility|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefCivility[]    findAll()
 * @method RefCivility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefCivilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefCivility::class);
    }

    // /**
    //  * @return RefCivility[] Returns an array of RefCivility objects
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
    public function findOneBySomeField($value): ?RefCivility
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
