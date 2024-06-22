<?php

namespace App\Repository;

use App\Entity\DataField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataField|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataField|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataField[]    findAll()
 * @method DataField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataField::class);
    }

    // /**
    //  * @return DataField[] Returns an array of DataField objects
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
    public function findOneBySomeField($value): ?DataField
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
