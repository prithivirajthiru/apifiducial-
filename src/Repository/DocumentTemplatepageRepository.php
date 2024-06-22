<?php

namespace App\Repository;

use App\Entity\DocumentTemplatepage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentTemplatepage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentTemplatepage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentTemplatepage[]    findAll()
 * @method DocumentTemplatepage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentTemplatepageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentTemplatepage::class);
    }

    // /**
    //  * @return DocumentTemplatepage[] Returns an array of DocumentTemplatepage objects
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
    public function findOneBySomeField($value): ?DocumentTemplatepage
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
