<?php

namespace App\Repository;

use App\Entity\RefFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefFile[]    findAll()
 * @method RefFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefFile::class);
    }

    // /**
    //  * @return RefFile[] Returns an array of RefFile objects
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
    public function findOneBySomeField($value): ?RefFile
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
