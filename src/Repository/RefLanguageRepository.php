<?php

namespace App\Repository;

use App\Entity\RefLanguage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefLanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefLanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefLanguage[]    findAll()
 * @method RefLanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefLanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefLanguage::class);
    }

    // /**
    //  * @return RefLanguage[] Returns an array of RefLanguage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RefLanguage
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
