<?php

namespace App\Repository;

use App\Entity\OptionsTabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OptionsTabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionsTabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionsTabel[]    findAll()
 * @method OptionsTabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionsTabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionsTabel::class);
    }

    // /**
    //  * @return OptionsTabel[] Returns an array of OptionsTabel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionsTabel
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
