<?php

namespace App\Repository;

use App\Entity\DataTemplateVariablesV1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataTemplateVariablesV1|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataTemplateVariablesV1|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataTemplateVariablesV1[]    findAll()
 * @method DataTemplateVariablesV1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataTemplateVariablesV1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataTemplateVariablesV1::class);
    }

    // /**
    //  * @return DataTemplateVariablesV1[] Returns an array of DataTemplateVariablesV1 objects
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
    public function findOneBySomeField($value): ?DataTemplateVariablesV1
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
