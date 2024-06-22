<?php

namespace App\Repository;

use App\Entity\DataSignature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataSignature|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataSignature|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataSignature[]    findAll()
 * @method DataSignature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataSignatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataSignature::class);
    }

    // /**
    //  * @return DataSignature[] Returns an array of DataSignature objects
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
    public function findOneBySomeField($value): ?DataSignature
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
