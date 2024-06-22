<?php

namespace App\Repository;

use App\Entity\RefZipcode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefZipcode|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefZipcode|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefZipcode[]    findAll()
 * @method RefZipcode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefZipcodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefZipcode::class);
    }

    // /**
    //  * @return RefZipcode[] Returns an array of RefZipcode objects
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
    public function findOneBySomeField($value): ?RefZipcode
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
