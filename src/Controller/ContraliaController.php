<?php

namespace App\Controller;
use App\Service\ContraliaService;
use App\Service\DocumentService;
use App\Service\EmailServiceV1;
use App\Service\ClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\ApiHelper;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\ApiResponse;
use App\UtilsV3\Document;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
class ContraliaController extends AbstractController
{

    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/contralia/step1/{requestid}", name="contraliaStep1",methods={"POST"})
     */
    public function contraliaStep1_($requestid,ContraliaService $contraliaservice,Request $request,ApiHelper $apihelper, EmailServiceV1 $emailService, DocumentService $documentMaker, ClientService $clientser)
    {
        
        if(!$clientser->checkClientId($requestid, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $transac = $contraliaservice->initiate($requestid,$apihelper);
        $contraliaservice->upload($request,$apihelper,$requestid, $emailService, $documentMaker);
        $contraliaservice->legalRepresentant($apihelper,$requestid);
        $value = [];
        $value['url'] = $contraliaservice->edocUrl($apihelper,$requestid);
        $value['transaction'] = $transac;       
        return new ApiResponse($value,200,["Content-Type"=>"application/json"],'json','success',['timezone']); 
    }
	
	/**
     * @Route("/contralia/document/signed/{requestid}", name="documentSigned",methods={"PUT"})
     */
    public function documentSigned_($requestid,ContraliaService $contraliaservice,Request $request,ClientService $clientser)
    {
        if(!$clientser->checkClientId($requestid, $request)) {
            //return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $contraliaservice->documentSign($requestid);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',' success',['timezone']); 
    }
	
	/**
     * @Route("/contralia/get/signedstatus/{requestid}", name="documentSignedStatus",methods={"GET"})
     */
    public function documentSignedStatus_($requestid,ContraliaService $contraliaservice,Request $request,ClientService $clientser)
    {
        if(!$clientser->checkClientId($requestid, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $data = $contraliaservice->getSignedStatus($requestid);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',' success',['timezone','client','dataRequestRequeststatuses','dateupdRequest','dataUserspace','requeststatus','__initializer__','__cloner__','__isInitialized__']); 
    }
	
	/**
     * @Route("/contralia/transaction/close/{requestid}", name="closeTrasaction",methods={"GET"})
     */
    public function closeTransaction($requestid, ContraliaService $contraliaservice, ApiHelper $apihelper)
    {
        $data = $contraliaservice->terminate($apihelper,$requestid);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',' success',['timezone','client','dataRequestRequeststatuses','dateupdRequest','dataUserspace','requeststatus','__initializer__','__cloner__','__isInitialized__']); 
    }

    /**
     * @Route("/contralia/step2/{requestid}", name="contraliaStep2",methods={"POST"})
     */
    public function contraliaStep2($requestid,ContraliaService $contraliaservice,Request $request,ApiHelper $apihelper)
    {
        $contraliaservice->setBankSignature($apihelper,$requestid);
        $contraliaservice->terminate($apihelper,$requestid);
        $entityManager = $this->EM;
        $contraliaservice->documentDownload($apihelper,$requestid, $entityManager);    
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',' success',['timezone']);
    }

}
