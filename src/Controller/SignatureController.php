<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\RefSignatureRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\UtilsV3\Master\Status;
use App\UtilsV3\Signature;
use App\Entity\RefSignature;
use App\Utils\ApiResponse;
use App\Service\SignatureService;

class SignatureController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/api/signature/getAll", name="getAllSignatures",methods={"GET"})
     */
    public function getAllSignatures_(SignatureService $signatureservice,Request $request)
    {
        $encoders      =  [ new JsonEncoder()];
        $normalizers   =  [new ObjectNormalizer()];
        $serializer    =  new Serializer($normalizers ,$encoders);
        $data          =  $signatureservice->getAllSignatures();
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   
     /**
     * @Route("/api/signature/getsingle/{id}", name="getSingleSignature",methods={"GET"})
     */
    public function getSingleSignature_(SignatureService $signatureservice,Request $request,$id)
    {
        $encoders      =  [ new JsonEncoder()];
        $normalizers   =  [new ObjectNormalizer()];
        $serializer    =  new Serializer($normalizers ,$encoders);
        $data          =  $signatureservice->getSingleOffer($id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

    /**
     * @Route("/api/signature/filter", name="signatureFilter",methods={"POST"})
     */
    public function signatureFilter_(SignatureService $signatureservice,Request $request)
    {
        $encoders      =  [ new JsonEncoder()];
        $normalizers   =  [new ObjectNormalizer()];
        $serializer    =  new Serializer($normalizers ,$encoders);
        $content       =  $request->getContent();
        $entityManager =  $this->EM;
        $status        =  $serializer->deserialize($content, Status::class, 'json');
        $data          =  $signatureservice->filterSignature($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

    /**
     * @Route("/api/signature/insert", name="signatureInsert",methods={"POST"})
     */
    public function signatureInsert(SignatureService $signatureservice,Request $request)
    {
        $encoders       =  [ new JsonEncoder()];
        $normalizers    =  [new ObjectNormalizer()];
        $serializer     =  new Serializer($normalizers ,$encoders);
        $content        =  $request->getContent();
        $signature      =  $serializer->deserialize($content, RefSignature::class, 'json');
        $entityManager  =  $this->EM;
        $data           =  $signatureservice->insertSignature($signature,$entityManager);
        if($data == "ko"){
            return new ApiResponse($data,400,["Content-Type"=>"application/json"],'json','dublicate signature');    
        }
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');    
      
    }

    /**
     * @Route("/api/signature/update", name="signatureUpdate",methods={"PUT"})
     */
    public function signatureUpdate(SignatureService $signatureservice,Request $request,RefSignatureRepository $sigrepo)
    {
        $encoders       =  [ new JsonEncoder()];
        $normalizers    =  [new ObjectNormalizer()];
        $serializer     =  new Serializer($normalizers ,$encoders);
        $content        =  $request->getContent();
        $signature      =  $serializer->deserialize($content, Signature::class, 'json');
        $entityManager  =  $this->EM;
        $data           =  $signatureservice->updateSignature($signature,$entityManager,$sigrepo);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }
    /**
     * @Route("/api/signature/enabled/{id}", name="signatureEnabled",methods={"PUT"})
     */
    public function signatureEnabled(RefSignatureRepository $sigrepo,$id)
    {
        $encoders       =  [ new JsonEncoder()];
        $normalizers    =  [new ObjectNormalizer()];
        $serializer     =  new Serializer($normalizers ,$encoders);
        $entityManager  =  $this->EM;
        $signature      =  $sigrepo->findOneBy(['id'=>$id]);
        if(!$signature){
        return new ApiResponse($signature,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $signature      -> setActiveSignature('Active');
        $entityManager  -> persist($signature);
        $entityManager  -> flush();
       
        return new ApiResponse($signature,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }
    /**
     * @Route("/api/signature/disabled/{id}", name="signatureDisabled",methods={"PUT"})
     */
    public function signatureDisabled(RefSignatureRepository $sigrepo,$id)
    {
        $encoders       =  [ new JsonEncoder()];
        $normalizers    =  [new ObjectNormalizer()];
        $serializer     =  new Serializer($normalizers ,$encoders);
        $entityManager  =  $this->EM;
        $signature      =  $sigrepo->findOneBy(['id'=>$id]);
        if(!$signature){
            return new ApiResponse($signature,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $signature      -> setActiveSignature('Disabled');
        $entityManager  -> persist($signature);
        $entityManager  -> flush();
       
        return new ApiResponse($signature,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }
    /**
     * @Route("/api/signature/deleted/{id}", name="signatureDeleted",methods={"PUT"})
     */
    public function signatureDeleted(RefSignatureRepository $sigrepo,$id)
    {
        $encoders       =  [ new JsonEncoder()];
        $normalizers    =  [new ObjectNormalizer()];
        $serializer     =  new Serializer($normalizers ,$encoders);
        $entityManager  =  $this->EM;
        $signature      =  $sigrepo->findOneBy(['id'=>$id]);
        if(!$signature){
            return new ApiResponse($signature,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $signature      -> setActiveSignature('Deleted');
        $entityManager  -> persist($signature);
        $entityManager  -> flush();
       
        return new ApiResponse($signature,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }

}