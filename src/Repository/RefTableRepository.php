<?php

namespace App\Repository;

use App\Entity\RefTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RefTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method RefTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method RefTable[]    findAll()
 * @method RefTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 
 */
class RefTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefTable::class);
    }

    // /**
    //  * @return RefTable[] Returns an array of RefTable objects
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
    public function findOneBySomeField($value): ?RefTable
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
public function next(string $code_table):string{
      $qb = $this->getEntityManager()->createQueryBuilder();
      $q = $qb->update('App\Entity\RefTable', 'u')
        ->set('u.current_value_table', 'u.current_value_table + 1')
        ->where('u.code_table = ?1')
        ->setParameter(1, $code_table)
        ->getQuery()
        ->execute();
        $refTable=$this->findOneBy(['code_table'=>$code_table]);
        return sprintf("%s%0".$refTable->getLengthTable()."d%s",$refTable->getPrefixTable(),$refTable->getCurrentValueTable(),$refTable->getSufixTable());        
}

public function nextv2(string $code_table):string{
    $EM = $this->getEntityManager();
    // $qb=$EM->createQueryBuilder();
    // $q = $qb->update('App\Entity\RefTable', 'u')
    //   ->set('u.current_value_table', 'u.current_value_table + 1')
    //   ->where('u.code_table = ?1')
    //   ->setParameter(1, $code_table)
    //   ->getQuery()
    //   ->execute();
      $refTable=$this->findOneBy(['code_table'=>$code_table]);
      $refTable->setCurrentValueTable( $refTable->getCurrentValueTable()+1);
      $EM->persist($refTable);
      $EM->flush();
      return sprintf("%s%0".$refTable->getLengthTable()."d%s",$refTable->getPrefixTable(),$refTable->getCurrentValueTable(),$refTable->getSufixTable());        
}
}
