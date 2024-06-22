<?php

namespace App\Repository;

use App\Entity\DataEloquaContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataEloquaContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataEloquaContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataEloquaContact[]    findAll()
 * @method DataEloquaContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataEloquaContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataEloquaContact::class);
    }

    // /**
    //  * @return DataEloquaContact[] Returns an array of DataEloquaContact objects
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
    public function findOneBySomeField($value): ?DataEloquaContact
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
