<?php

namespace App\Repository;

use App\Entity\DataContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataContact[]    findAll()
 * @method DataContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataContact::class);
    }

    // /**
    //  * @return DataContact[] Returns an array of DataContact objects
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
    public function findOneBySomeField($value): ?DataContact
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
