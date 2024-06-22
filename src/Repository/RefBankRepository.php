<?php

namespace App\Repository;

use App\Entity\RefBank;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefBank|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefBank|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefBank[]    findAll()
 * @method RefBank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefBankRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefBank::class);
    }

    // /**
    //  * @return RefBank[] Returns an array of RefBank objects
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
    public function findOneBySomeField($value): ?RefBank
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
