<?php

namespace App\Repository;

use App\Entity\DataTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataTemplate[]    findAll()
 * @method DataTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataTemplate::class);
    }

    // /**
    //  * @return DataTemplate[] Returns an array of DataTemplate objects
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
    public function findOneBySomeField($value): ?DataTemplate
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
