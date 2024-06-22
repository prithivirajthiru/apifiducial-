<?php

namespace App\Repository;

use App\Entity\RefTypecontact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefTypecontact|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefTypecontact|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefTypecontact[]    findAll()
 * @method RefTypecontact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefTypecontactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefTypecontact::class);
    }

    // /**
    //  * @return RefTypecontact[] Returns an array of RefTypecontact objects
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
    public function findOneBySomeField($value): ?RefTypecontact
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
