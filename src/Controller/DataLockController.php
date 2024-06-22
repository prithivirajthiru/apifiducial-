<?php

namespace App\Controller;

use App\Utils\ApiResponse;
use App\Service\ClientService;
use App\Service\DataLockService;
use App\Service\LegalformService;
use App\Service\FieldCheckService;
use App\Service\CompanyTypeService;
use App\Service\DataRequestService;
use App\Repository\DataLockRepository;
use App\Repository\DataClientRepository;
use App\Repository\DataRequestRepository;
use App\Service\DataReq_ReqstatusService;
use App\Repository\RefTypeclientRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
class DataLockController extends AbstractController
{

   protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

   /**
    * @Route("/data/lock", name="data_lock")
   */
   public function index()
   {
      return $this->render('data_lock/index.html.twig', [
         'controller_name' => 'DataLockController',
      ]);
   }

   /**
    * @Route("/api/datalock/insert", name="dataLockInsert",methods={"POST"})
   */
   public function dataLockInsert(DataLockService $datalockservice,Request $request,DataLockRepository $datalockrepo)
   {   
      $data = $datalockservice->dataLockInsert($request);
      if ($data == 'request') {
         return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Invalid Request id');              
      }

      if ($data == 'login') {
         return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','already login');              
      }
      return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','Insert successfully');   
   }

   /**
    * @Route("/api/datalock/update/{username}", name="dataLockUpdate",methods={"PUT"})
   */
   public function dataLockUpdate(DataLockService $datalockservice,$username)
   {
      $data = $datalockservice->dataLockUpdate($username);
      if ($data == 'ko') {
         return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Invalid username');              
      }
      return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','Update successfully');   
   }

   /**
   * @Route("/api/datalock/get/{request_id}", name="dataLockGet",methods={"GET"})
   */
   public function dataLockGet(DataLockService $datalockservice,$request_id,DataRequestRepository $datareqrepo,
                              DataClientRepository $clientrepo, DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice,
                              LegalformService $legalformservice,DataLockRepository $datalockrepo, RefTypeclientRepository $typeclientrep) {
      $datalock = $datalockrepo->findOneBy(['request'=> $request_id,'dlock'=>true]);
      if (!$datalock) {
         return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','empty');   
      }
      $datareq = $datareqrepo->findOneBy(['id'=>$request_id]);
      $serializer = new Serializer(array(new DateTimeNormalizer()));
      $date = $serializer->normalize($datalock->getDate());
      $request = $datareqserv->getRequestStatusV1($datareq,$datareqrepo,$clientrepo,$datareqserv,$clientservice,$companytypeservice,$legalformservice,$typeclientrep, "", "", false);
      $datalock->setDateTime($date);
      $datalock->setDataRequest($request);
      return new ApiResponse($datalock,200,["Content-Type"=>"application/json"],'json','success',['request','date','requestId']);   
   }
}
