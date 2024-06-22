<?php

namespace App\Repository;

use App\Entity\DataTreatment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataTreatment|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataTreatment|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataTreatment[]    findAll()
 * @method DataTreatment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataTreatmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataTreatment::class);
    }

    // /**
    //  * @return DataTreatment[] Returns an array of DataTreatment objects
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
    public function findOneBySomeField($value): ?DataTreatment
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
