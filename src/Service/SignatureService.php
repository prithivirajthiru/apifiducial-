<?php
namespace App\Service;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\RefSignature;



use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManagerInterface;


class SignatureService 
{

    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }

    public function getAllSignatures(){
        $signature       =   $this ->EM -> getRepository(RefSignature::class);
        $offers          =   $signature->findBy(['active_signature'=>'Active']);
        return $offers;
    }

    public function getSingleOffer($id){
        $signature      =   $this ->EM -> getRepository(RefSignature::class);
        $offer          =   $signature->findOneBy(['id'=>$id]);
        return $offer;
    }

    public function filterSignature($status){
      
        $arrstatus     =  array();
        $signature     =  array();
        $arrstatus     =  $status->getStatus();
        $signaturerepo =  $this->EM->getRepository(RefSignature::class);
        $signature     =  $signaturerepo->findBy(array('active_signature' => $arrstatus));
        return $signature;
      }
      public function insertSignature($signature,$entityManager){
        $signaturerepo =  $this->EM->getRepository(RefSignature::class);
        $chksignature = $signaturerepo->findOneBy(['name'=>$signature->getName()]);
        if($chksignature){
          return "ko";
        }
        $uuData = $this->signatureUuid();
        $signature->setType("img");
        $signature->setActiveSignature("Disabled");
        $signature->setData($signature->getData());
        $signature->setUuid($uuData);
        $entityManager->persist($signature);
        $entityManager->flush();
        return $signature;
      }

      
      public function updateSignature($signature,$entityManager,$sigrepo){
        $chksig=$sigrepo->findOneby(['id'=>$signature->getId()]);
        if(!$chksig){
            return "id invalid";
        }
        $chksig  ->  setName($signature->getName());
        $chksig  ->  setDescSignature($signature->getDescSignature());
        $chksig  ->  setType("img");
        if($signature->getData()){
          $chksig  ->  setData($signature->getData());
        }
        if($chksig->getUuid()==null){
          $uuData = $this->signatureUuid();
          $chksig  ->  setUuid($uuData);
        }
        $entityManager  -> persist($chksig);
        $entityManager  -> flush();
        return $chksig;
      }

      public function signatureUuid() {
        $date = date('d-m-Y_H-i-s');
        $q = "SELECT MAX(id) as value FROM ref_signature";
        $conn = $this->EM->getConnection();
        $result = $conn->executeQuery($q)->fetch();
        $currecnt_value = (string)$result["value"]+1;
        $unnid = $date."_".$currecnt_value;
        return sha1($unnid);
      }
}