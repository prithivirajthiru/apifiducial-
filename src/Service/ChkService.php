<?php

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\RefTable;
class ChkService 
{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
    public function next(string $code_table):string{
        $qb = $this->EM->createQueryBuilder();
        $q = $qb->update('App\Entity\RefTable', 'u')
          ->set('u.current_value_table', 'u.current_value_table + 1')
          ->where('u.code_table = ?1')
          ->setParameter(1, $code_table)
          ->getQuery()
          ->execute();
          $refTable=$this->findOneBy(['code_table'=>$code_table]);
          return sprintf("%s%0".$refTable->getLengthTable()."d%s",$refTable->getPrefixTable(),$refTable->getCurrentValueTable(),$refTable->getSufixTable());        
  }
}