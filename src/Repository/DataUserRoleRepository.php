<?php

namespace App\Repository;

use App\Entity\DataUserRole;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method DataUserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataUserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataUserRole[]    findAll()
 * @method DataUserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataUserRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataUserRole::class);
    }

    // /**
    //  * @return DataUserRole[] Returns an array of DataUserRole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataUserRole
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
