<?php

namespace App\Repository;

use App\Entity\DataActionVariable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataActionVariable|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataActionVariable|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataActionVariable[]    findAll()
 * @method DataActionVariable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataActionVariableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataActionVariable::class);
    }

    // /**
    //  * @return DataActionVariable[] Returns an array of DataActionVariable objects
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
    public function findOneBySomeField($value): ?DataActionVariable
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
