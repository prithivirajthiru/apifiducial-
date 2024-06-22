<?php

namespace App\Service;

use App\Entity\RefDirect;
use App\UtilsV3\Direct;
use App\Repository\RefDirectRepository;
use Doctrine\ORM\EntityManagerInterface;

class EmailService{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
    // public function getData(RefDirectRepository $directrepo,$id){
    //     $fields=$directrepo->findAll();
    //     //  $entityManager = em->getDoctrine()->getManager();
    //     $conn = $this->EM->getConnection();

    //     foreach($fields as $field){
    //       if($field->getDirecttype()=='direct'){
    //         $tablename=$field->getTablename();
    //         // $directid=$fields->getId();
    //          $sql='select '.$field->getFieldname().' from '.$tablename.' where id ='.$id;
    //         $stmt = $conn->prepare($sql);
    //         $stmt->execute();
    //         $result=$stmt->fetch();
    //         // $result= $conn->getEntityManager()
    //         // ->createQuery($sql)
    //         // ->setMaxResults(1)
    //         // ->getResult()
    //         // return $result;
    //         $field->setValue($result[$field->getFieldname()]);
           
    //       }
    //     //   if($field->getDirecttype()=='i18n'){
    //     //     $tablename=$field->getTablename();
    //     //     // $directid=$fields->getId();
    //     //      $sql='select '.$field->getFieldname().' from '.$tablename.' where id ='(select .$id);
    //     //     $stmt = $conn->prepare($sql);
    //     //     $stmt->execute();
    //     //     $result=$stmt->fetch();
    //     //     // $result= $conn->getEntityManager()
    //     //     // ->createQuery($sql)
    //     //     // ->setMaxResults(1)
    //     //     // ->getResult()
    //     //     // return $result;
    //     //     $field->setValue($result[$field->getFieldname()]);
           
    //     //   }
    // }
    // return $fields;
        
    // }
    // public function addVariable($text,$data){
        
    //     // foreach($data as $datum){
           
    //     //     $pattern="%".$datum->getVariable()."%";
    //     //     $correctiontext = preg_replace("/$pattern/"," ".$datum->getValue()." ",$text);
    //     //     return $pattern;
    //     // }
    //     // $correctiontext ="";
    //     $text1=$text;
    //     foreach($data as $datum){
    //         if($datum->getVariable()!=null){
    //             $pattern="%".$datum->getVariable()."%";
    //             $correctiontext = preg_replace("/$pattern/"," ".$datum->getValue()." ",$text1);
    //             $text1= $correctiontext;
    //         }
           
           
    //     }
       
    //     return $correctiontext;
       
       
    // }


}