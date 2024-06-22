<?php

namespace App\Repository;

use App\Entity\DataCTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataCTO|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCTO|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCTO[]    findAll()
 * @method DataCTO[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCTORepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataCTO::class);
    }

    // /**
    //  * @return DataCTO[] Returns an array of DataCTO objects
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
    public function findOneBySomeField($value): ?DataCTO
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
