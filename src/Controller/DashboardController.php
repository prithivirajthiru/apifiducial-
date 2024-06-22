<?php

namespace App\Controller;

use App\Entity\RefEpa;
use App\Entity\RefLabel;
use App\Entity\RefTable;
use App\Utils\ApiHelper;
use App\Entity\DataClient;
use App\Entity\RefCountry;
use App\Utils\ApiResponse;
use App\Utils\FieldChecks;
use App\Entity\DataRequest;
use App\Entity\RefTown;
use App\Entity\RefLanguage;
use App\Entity\DataUserRole;
use App\Entity\DataTransaction;
use App\UtilsSer\Pagination;
use App\Service\AlertService;
use App\Service\ClientService;
use App\UtilsV3\Master\Status;
use App\UtilsV3\RequestStatus;
use App\Service\EmailServiceV1;
use App\Service\DocumentService;
use App\Service\LegalformService;
use App\Utils\RequestStatusOrder;
use App\Service\FieldCheckService;
use App\Service\CompanyTypeService;
use App\Service\DataRequestService;
use App\Service\IntegrationService;
use App\Repository\RefEpaRepository;
use App\Repository\DataCTORepository;
use App\Repository\RefFileRepository;
use App\Entity\DataCountryWhereclient;
use App\Repository\DataRoleRepository;
use App\Entity\DataCountryWheresupplier;
use App\Repository\DataClientRepository;
use App\Repository\FieldCheckRepository;
use App\Repository\RefCountryRepository;
use App\Repository\DataContactRepository;
use App\Repository\RefVariableRepository;
use App\Repository\DataRequestRepository;
use App\Repository\DataTemplateRepository;
use App\Service\DataReq_ReqstatusService;
use App\Repository\DataAttorneyRepository;
use App\Repository\DataUserRoleRepository;
use App\Repository\OptionsTabelRepository;
use App\Repository\DataClientSabRepository;
use App\Repository\DataUserspaceRepository;
use App\Repository\RefTypeclientRepository;
use App\Repository\DataFieldIssueRepository;
use Symfony\Component\Serializer\Serializer;
use App\Repository\DataRequestFileRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RefRequeststatusRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RefRequestStatusOrderRepository;
use App\Repository\DataCountryWhereclientRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\Repository\DataCountryWheresupplierRepository;
use App\Repository\DataRequestRequeststatusRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Swift_SmtpTransport,\Swift_Mailer,\Swift_Message,\Swift_Attachment;
use App\Repository\DataTransactionRepository;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
class DashboardController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }


    /**
      * @Route("/dashboard/getall", name="dashboardgetall")
     */
    public function dashboardGetall(DataRequestRepository $datareqrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $datareq = $datareqrepo->findBy(['requeststatus'=>1]);   
        return new ApiResponse($datareq,200,["Content-Type"=>"application/json"],'json','success');    
    }

    /**
     * @Route("/api/update/datarequestwithfirstopening", name="datarequestWithFirstOpening",methods={"PUT"})
     */
    public function datarequestWithFirstOpening(DataRequestRepository $datareqrepo,Request $request)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content, DataRequest::class, 'json');
        $request_data = $datareqrepo->findOneBy(['id'=>$data->getId()]);
        if(!$request_data){
            return new ApiResponse($data->getId(),400,["Content-Type"=>"application/json"],'json','invalid_id',["timezone"]);    
        }
        $request_data->setFirstOpening($data->getFirstOpening());
        $entityManager->persist($request_data);
        $entityManager->flush();
        return new ApiResponse($request_data,400,["Content-Type"=>"application/json"],'json','invalid_id',["timezone","client"]);    
    }

    /**
     * @Route("/api/multiplerequestwithcode", name="multiplerequestwithcode",methods={"POST"})
     */
    public function getMultipleRequestWithCode(DataRequestRepository $datareqrepo,
            DataRequestRepository $datarepo,DataClientRepository $clientrepo,
            DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice,
            LegalformService $legalformservice,Request $request,RefRequeststatusRepository $refreqreqstatusrepo, RefTypeclientRepository $typeclientrep, DataRequestRequeststatusRepository $datareqreqstatus, DataTransactionRepository $datatransrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrstatus = array();
        $arrstatus = $status->getStatus();
        $statuss = $refreqreqstatusrepo->findBy(array('status_requeststatus' => $arrstatus));
        $ids = array();
        $lim = false;
        foreach($statuss as $status){
            if ($status->getStatusRequestStatus() == "170") {

                $lim = true;

            }
            array_push($ids,$status->getId());
        }
        
        if ($lim == false)
            $datareqs = $datareqrepo->findBy(array('requeststatus' => $ids));    
        else 
            $datareqs = $datareqrepo->findBy(array('requeststatus' => $ids), ["id"=> "DESC"], 100);  
              
        $data = array();
        foreach ($datareqs as $datareq) {            
            $demandedate = $datareqreqstatus->findBy(['id_request' => $datareq->getId(), 'id_requeststatus' => '4'], ['id' => 'ASC']);   
            if (isset($demandedate[0])) {
                $datareq->setDateRequest($demandedate[0]->getDateRequestRequeststatus());
            }
            $demandedate = $datareqreqstatus->findBy(['id_request' => $datareq->getId()], ['id' => 'DESC']);        
            $datareq->setDateupdRequest($demandedate[0]->getDateRequestRequeststatus());
            $demandedate = $refreqreqstatusrepo->findBy(['id' => $demandedate[0]->getIdRequeststatus()]);        
			
			$arrstatus = ['150' => 1, '151' => 1, '152' => 1, '158' => 1];
            $isvir = "0";
            if (isset($arrstatus[$demandedate[0]->getStatusRequeststatus()])) {
                $datatrans = $datatransrepo->findOneBy(['client' => $datareq->getId(), "received" => 0], ["id" => "DESC"]);
                if ($datatrans) {
                    $isvir = "1";
                }
            }
			
            array_push($data, $datareqserv->getRequestStatusV1($datareq,$datarepo,$clientrepo,$datareqserv,$clientservice,$companytypeservice,$legalformservice,$typeclientrep, $demandedate[0]->getOrigine(), $demandedate[0]->getSource(), $isvir));
        }
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
    * @Route("/api/multiplerequestwithcodev1", name="multiplerequestwithcodev1",methods={"POST"})
    */
   public function getMultipleRequestWithCodev1(DataRequestRepository $datareqrepo,
           DataRequestRepository $datarepo,DataClientRepository $clientrepo,
           DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice,
           LegalformService $legalformservice,Request $request,RefRequeststatusRepository $refreqreqstatusrepo, RefTypeclientRepository $typeclientrep, DataRequestRequeststatusRepository $datareqreqstatus, DataTransactionRepository $datatransrepo)
   {
       $encoders = [new JsonEncoder()];
       $normalizer = new ObjectNormalizer();
       
       $entityManager = $this->EM;
       $normalizers = array($normalizer,new DateTimeNormalizer());
       $serializer = new Serializer($normalizers ,$encoders);
       $content = $request->getContent();
       $pagination = $serializer->deserialize($content, Pagination::class, 'json');
       $arrstatus = array();
       $arrstatus = $pagination->getStatus();
       
       
       $statuss = $refreqreqstatusrepo->findBy(array('status_requeststatus' => $arrstatus));
       $ids = array();
       foreach($statuss as $status){
           array_push($ids,$status->getId());
       }	
    //    return new ApiResponse($arrstatus,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    $ids = implode(",",$ids);
      
       $entityManager = $this->EM;
       $Requestrepo = $entityManager->getRepository(DataRequest::class);
       $count=$Requestrepo->createQueryBuilder('p')
       ->select('count(p.id)')
       ->andWhere("p.requeststatus IN (:ids)") // ... search if there's a match 
       ->setParameter("ids", $ids)       
        ->getQuery()
        ->getSingleScalarResult();
        // return new ApiResponse($count,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);

        $pagination->setCount($count);

    //    return $count;

        $pagination->calculate();


        $datarequest=$Requestrepo->createQueryBuilder('p')
        // ->setParameter('requeststatus   ', $requeststatus_id)
        ->andWhere("p.requeststatus IN (:ids)") // ... search if there's a match 
        ->setParameter("ids", $ids)  
        ->setMaxResults($pagination->getLimit())
        ->setFirstResult($pagination->getSkip())       
        ->getQuery()
        ->getResult();
    //    return new ApiResponse($datarequest,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);

    //    return $datarequest;
       
	
       $datareqs = $datareqrepo->findBy(array('id' => $datarequest));
       
       /*$lim = false;
       foreach($statuss as $status){
           if ($status->getStatusRequestStatus() == "170" || $status->getStatusRequestStatus() == "190") {
               $lim = true;
           }
           array_push($ids,$status->getId());
       }
       if ($lim == false)
           $datareqs = $datareqrepo->findBy(array('requeststatus' => $ids));
       else
           $datareqs = $datareqrepo->findBy(array('requeststatus' => $ids), ["id"=> "DESC"], 100);
       */
       $data = array();
       foreach ($datareqs as $datareq) {
           $demandedate = $datareqreqstatus->findBy(['id_request' => $datareq->getId(), 'id_requeststatus' => '4'], ['id' => 'ASC']);
           if (isset($demandedate[0])) {
               $datareq->setDateRequest($demandedate[0]->getDateRequestRequeststatus());
           }
           $demandedate = $datareqreqstatus->findBy(['id_request' => $datareq->getId()], ['id' => 'DESC']);
           $datareq->setDateupdRequest($demandedate[0]->getDateRequestRequeststatus());
           $demandedate = $refreqreqstatusrepo->findBy(['id' => $demandedate[0]->getIdRequeststatus()]);
           
           $arrstatus = ['150' => 1, '151' => 1, '152' => 1, '158' => 1];
           $isvir = "0";
           if (isset($arrstatus[$demandedate[0]->getStatusRequeststatus()])) {
               $datatrans = $datatransrepo->findOneBy(['client' => $datareq->getId(), "received" => 0], ["id" => "DESC"]);
               if ($datatrans) {
                   $isvir = "1";
               }
           }
           
           array_push($data, $datareqserv->getRequestStatusV1($datareq,$datarepo,$clientrepo,$datareqserv,$clientservice,$companytypeservice,$legalformservice,$typeclientrep, $demandedate[0]->getOrigine(), $demandedate[0]->getSource(), $isvir));
       }
       return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
   }

    /**
    * @Route("/api/multiplerequestwithcodev2", name="multiplerequestwithcodev2",methods={"POST"})
    */
    public function getMultipleRequestWithCodev2(DataRequestRepository $datareqrepo,
    DataRequestRepository $datarepo,DataClientRepository $clientrepo,
    DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice,
    LegalformService $legalformservice,Request $request,RefRequeststatusRepository $refreqreqstatusrepo, RefTypeclientRepository $typeclientrep, DataRequestRequeststatusRepository $datareqreqstatus, DataTransactionRepository $datatransrepo)
{
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $pagination = $serializer->deserialize($content, Pagination::class, 'json');
        $arrstatus = array();
        $arrstatus = $pagination->getStatus();
        $statusids = implode(",",$arrstatus);
        $limit = $pagination->getLimit();
        $offset = ($pagination->getPageNo() - 1) * $limit; 
        if($pagination->getKeyWord()){
             $keyword = $pagination->getKeyWord();
             $query = "SELECT `id_request_id` as id, max(date_request_requeststatus) as datei  FROM `data_request_requeststatus` where `id_requeststatus_id` in (select id from ref_requeststatus where status_requeststatus in ($statusids))  AND `id_request_id` in (SELECT id FROM DATA_REQUEST WHERE client_id in(SELECT `client_id` FROM `data_attorney` WHERE `name_attorney` like '%$keyword%' or `surname_attorney` like '%$keyword%' or `birth_name` like '%$keyword%') OR (`client_id` in(SELECT id FROM `data_client` where `companyname_client` like '%$keyword%' OR `siren` LIKE '%$keyword%')) OR (`id` LIKE '%$keyword%'))group by `id_request_id` order by `datei` desc limit $limit offset $offset";
        }
        else{
             $query = "SELECT `id_request_id` as id, max(date_request_requeststatus) as datei  FROM `data_request_requeststatus` where `id_requeststatus_id` in (select id from ref_requeststatus where status_requeststatus in ($statusids)) group by `id_request_id` order by `datei` desc limit $limit offset $offset";
        }

        $connection = $this->EM->getconnection();
        $Requestids = $connection->executeQuery($query)->fetchAll();
        
        $ids = array();
        foreach($Requestids as $status){
            array_push($ids,$status["id"]);
        }	
        $data = [];
        if($ids){
			$data = array();
			foreach ($ids as $oneid) {
				$datareqs = $datareqrepo->findBy(array('id' => $oneid));
				
				foreach ($datareqs as $datareq) {
					$demandedate = $datareqreqstatus->findBy(['id_request' => $datareq->getId(), 'id_requeststatus' => '4'], ['id' => 'ASC']);
					if (isset($demandedate[0])) {
						$datareq->setDateRequest($demandedate[0]->getDateRequestRequeststatus());
					}
					$demandedate = $datareqreqstatus->findBy(['id_request' => $datareq->getId()], ['id' => 'DESC']);
					$datareq->setDateupdRequest($demandedate[0]->getDateRequestRequeststatus());
					$demandedate = $refreqreqstatusrepo->findBy(['id' => $demandedate[0]->getIdRequeststatus()]);
		
					$arrstatus = ['150' => 1, '151' => 1, '152' => 1, '158' => 1];
					$isvir = "0";
					if (isset($arrstatus[$demandedate[0]->getStatusRequeststatus()])) {
						$datatrans = $datatransrepo->findOneBy(['client' => $datareq->getId(), "received" => 0], ["id" => "DESC"]);
						if ($datatrans) {
							$isvir = "1";
						}
					}
		
					array_push($data, $datareqserv->getRequestStatusV1($datareq,$datarepo,$clientrepo,$datareqserv,$clientservice,$companytypeservice,$legalformservice,$typeclientrep, $demandedate[0]->getOrigine(), $demandedate[0]->getSource(), $isvir));
				}
			}
		}
        
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    }


    /**
      * @Route("/api/getcount/{status}", name="countofStatus")
     */
    public function countofStatus($status,DataRequestRepository $datareqrepo)
    {
        $entityManager = $this->EM;
        $query = "select count(id) from data_request where requeststatus_id =(select id from ref_requeststatus where `status_requeststatus` = $status)";
        $entityManager = $this->EM;
        $connection = $this->EM->getconnection();
        $count = $connection->executeQuery($query)->fetch();
        return new ApiResponse($count["count(id)"],200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    

    }
    /**
     * @Route("/api/multiplerequest/usingdate", name="multiplerequestusingdate",methods={"POST"})
     */
    public function getMultipleRequestUsingDate(DataRequestRepository $datareqrepo,DataUserspaceRepository $userspaceRepo,
            DataRequestRepository $datarepo,DataClientRepository $clientrepo,EmailServiceV1 $emailserviceV1,
            DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,
            LegalformService $legalformservice,Request $request,RefRequeststatusRepository $refreqreqstatusrepo, RefTypeclientRepository $typeclientrep, DataRequestRequeststatusRepository $datareqreqstatus, DataTransactionRepository $datatransrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrDate = array();
        $arrDate = $status->getDate();
        $datecount = count($arrDate);
        if($datecount == 1){
            $requestIds = array();
            $query = "SELECT id FROM data_request WHERE CAST(date_request AS DATE) =:fromdate";
            $conn = $entityManager->getconnection();
            $datas = $conn->executeQuery($query, ['fromdate' => (string)$arrDate[0]])->fetchAll();
            foreach($datas as $data){
                array_push($requestIds,$data['id']);
            }
        }
        if($datecount == 2){
            $requestIds = array();
            $query = "SELECT id FROM data_request WHERE date_request BETWEEN :fromdate AND :todate";
            $conn = $entityManager->getconnection();
            $datas = $conn->executeQuery($query, ['fromdate' => (string)$arrDate[0],'todate'=>(string)$arrDate[1]])->fetchAll();
            foreach($datas as $data){
                array_push($requestIds,$data['id']);
            }
        }
        $datareqs = $datareqrepo->findBy(array('id' => $requestIds));    
        $data = array();
        foreach ($datareqs as $datareq) { 
            $clientid = $datareq->getClient()->getId();
            $objdatareq = $userspaceRepo->findOneBy(['id_request'=>$datareq->getId()]);
            $email_id = null;
            if($objdatareq){
               $email_id = $objdatareq->getEmailUs();
            }
            $emailserviceV1->sendEmail($varrepo,$datatemprepo,$clientid,34,$email_id, true);
            array_push($data, $datareqserv->getRequestStatusV1($datareq,$datarepo,$clientrepo,$datareqserv,$clientservice,$companytypeservice,$legalformservice,$typeclientrep,null,null,null));
        }
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/multiplerequest/usingdatev1", name="multiplerequestusingdatev1",methods={"POST"})
     */
    public function getMultipleRequestUsingDatev1(DataRequestRepository $datareqrepo,DataUserspaceRepository $userspaceRepo,
            DataRequestRepository $datarepo,DataClientRepository $clientrepo,EmailServiceV1 $emailserviceV1,
            DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,
            LegalformService $legalformservice,Request $request,RefRequeststatusRepository $refreqreqstatusrepo, RefTypeclientRepository $typeclientrep, DataRequestRequeststatusRepository $datareqreqstatus, DataTransactionRepository $datatransrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrDate = array();
        $arrDate = $status->getDate();
        $datecount = count($arrDate);
        if($datecount == 1){
            $requestIds = array();
            $query = "SELECT id_request_id FROM data_request_requeststatus WHERE id_requeststatus_id in(19,37) AND CAST(date_request_requeststatus AS DATE) =:fromdate";
            $conn = $entityManager->getconnection();
            $datas = $conn->executeQuery($query, ['fromdate' => (string)$arrDate[0]])->fetchAll();
            foreach($datas as $data){
                array_push($requestIds,$data['id_request_id']);
            }
        }
        if($datecount == 2){
            $requestIds = array();
            $query = "SELECT id_request_id FROM data_request_requeststatus WHERE id_requeststatus_id in(19,37) AND date_request_requeststatus BETWEEN :fromdate AND :todate";
            $conn = $entityManager->getconnection();
            $datas = $conn->executeQuery($query, ['fromdate' => (string)$arrDate[0],'todate'=>(string)$arrDate[1]])->fetchAll();
            foreach($datas as $data){
                array_push($requestIds,$data['id_request_id']);
            }
        }
        $datareqs = $datareqrepo->findBy(array('id' => $requestIds));    
        $data = array();
        $data = $emailserviceV1->getRequestContent($datareqs);
        // foreach ($datareqs as $datareq) { 
        //     array_push($data, $datareqserv->getRequestStatusV1($datareq,$datarepo,$clientrepo,$datareqserv,$clientservice,$companytypeservice,$legalformservice,$typeclientrep,null,null,null));
        // }
  
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }
    
    /**
     * @Route("/api/refrequeststatusV1/filter", name="requeststatusFilterV1",methods={"POST"})
     */
    public function requeststatusFilterV1_(RefRequeststatusRepository $refreqrepo,Request $request)
    {

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrstatus = array();
        $arrstatus = $status->getStatus();
        $data = $refreqrepo->findBy(array('active_requeststatus' => $arrstatus,"visibility" => "YES"));
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',['dataRequestRequeststatuses']);    

    }
 
    /**
     * @Route("/api/refrequeststatus/update", name="requeststatusUpdate",methods={"POST"})
     */
    public function requeststatusUpdate_(RefRequeststatusRepository $refreqrepo,Request $request)
    {

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content, RequestStatus::class, 'json');
        $chkstatus = $refreqrepo->findOneBy(['id'=>$data->getId()]);
        if(!$chkstatus){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid id');    

        }
        $chkstatus->setDescRequeststatus($data->getDescRequeststatus());
        $chkstatus->setVisibility($data->getVisibility());
        $chkstatus->setFoDesc($data->getFoDesc());
        $entityManager->persist($chkstatus);
        $entityManager->flush();
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success updated',["timezone"]);    

    }

    /**
     * @Route("/api/refrequeststatus/get/{id}", name="requeststatusGetSingle",methods={"GET"})
     */
    public function requeststatusGetSingle_(RefRequeststatusRepository $refreqrepo,Request $request,$id)
    {
        $chkstatus = $refreqrepo->findOneBy(['id'=>$id]);
        if(!$chkstatus){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid id');    
        }
        return new ApiResponse($chkstatus,200,["Content-Type"=>"application/json"],'json','success updated',["timezone"]);    
    }

    /**
      * @Route("/api/datarequest/getall/{request_id}", name="reqrequestGetAll",methods={"GET"})
    */
    public function reqrequestGetAll(DataRequestRequeststatusRepository $refreqreqrepo,Request $request,$request_id,
        DataReq_ReqstatusService $datareqreqserv,DataClientRepository $clientrepo,ClientService $clientservice)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
      
        if(!$clientservice->checkClientId($request_id, $request)) {
            return new ApiResponse([$clientservice->checkClientId($request_id, $request)],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $data = $datareqreqserv->getStatusDetail($request_id,$clientrepo,$clientservice,$refreqreqrepo);
        if(!$data){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid id');    
        }   
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success updated',["timezone","dataRequestRequeststatuses","__initializer__","__cloner__","__isInitialized__"]);    
    }

    /**
     * @Route("/api/role/filter", name="roleFilter",methods={"POST"})
     */
    public function roleFilter_(DataRoleRepository $rolerepo,Request $request)
    {

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrstatus = array();
        $arrstatus = $status->getStatus();
        $data = $rolerepo->findBy(array('status' => $arrstatus));
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/getcto/{request_id}/{all}", name="getcto",methods={"GET"})
     */
    public function getCto($request_id, $all = 0, DataCTORepository $ctorep, Request $request)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);

        if ($all == 1) {
            $data = $ctorep->findBy(array('request' => $request_id), ['id' => 'DESC']);
        } else {
            $data = $ctorep->findBy(array('request' => $request_id, "code" => "KO"), ['id' => 'DESC']);
        }
        
        $datas = array();
        foreach ($data as $key => $value) {
            $datas[$key]['type'] = $data[$key]->getTypeAlert();
            $datas[$key]['value'] = $data[$key]->getValue();
            $datas[$key]['code'] = $data[$key]->getCode();
            $datas[$key]['desc'] = $data[$key]->getAlertDesc();
            //$datas[$key]['date'] = date(DATE_W3C,$data[$key]->getDate()->getTimestamp()+ 3600*(-1+date("I")));
			$datas[$key]['date'] = date(DATE_W3C,$data[$key]->getDate()->getTimestamp());
            $datas[$key]['passage'] = $data[$key]->getPassage();
        }        
        return new ApiResponse($datas,200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/countcto/{request_id}", name="countcto",methods={"GET"})
     */
    public function countCto($request_id, DataCTORepository $ctorep, Request $request)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $ctorep->findBy(array('request' => $request_id));    
        $datas = array();
        $i = 0;
        foreach ($data as $key => $value) {
            $i++;
        }        
        return new ApiResponse([$i],200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
      * @Route("/api/docto/{client_id}", name="docto", methods={"GET"})
    */
	public function doCto(Request $request, $client_id, DataClientRepository $clientrepo, DataAttorneyRepository $attorneyrepo, DataCountryWhereclientRepository $whereclientrepo, DataCountryWheresupplierRepository $wheresupplierrepo, AlertService $alertservice, ApiHelper $api)
    {
        $clientdata = $clientrepo->findOneBy(['id'=>$client_id]);        

        $lastid = $alertservice->getLastPassage($client_id);
        $lastid = $lastid + 1;
        if ($lastid == null) {
            $lastid = 1;
        }

        $epa        = null;
        $country    = null;
        if($clientdata->getEpa()){
            $epa = $clientdata->getEpa()->getEpaCode();
            $alertservice->apeAlert($client_id, $epa, $request, $api, 1, $lastid);            
        }
        if($clientdata->getCountry()){
            $country=strtoupper($clientdata->getCountry()->getCountryCodeLabel());
            $alertservice->countryAlert($client_id, "Résidence ENT", $country, $request, $api, 1, $clientdata->getCountry()->getDescCountry(), $lastid);
        }

        $attorneys  = $attorneyrepo->findBy(['client'=>$client_id, 'active_attorney'=>'Active'], ['id' => 'ASC']);
        $i = 0;        
        foreach($attorneys as $attorney){
            $attorny_id  = $attorney->getId();
            $nom         = $attorney->getNameAttorney();
            $prenom      = $attorney->getSurnameAttorney();
            $birthname   = $attorney->getBirthName();
            
            $i = ($attorney->getIsmandatoryAttorney() && $attorney->getIsshareholderAttorney() == false) ? $i : $i + 1;
            $actourl = ($attorney->getIsmandatoryAttorney()) ? "RL" : "ACT $i";
            
            if ($attorney->getIscompany() == false) {
                $alertservice->personaldataAlert($client_id, $nom, $prenom, $birthname, $attorny_id, $request, $api, 1, $lastid, $actourl);

                $residencecountry = null;
                if($attorney->getResidentcountryAttorney()){
                    $residencecountry=strtoupper($attorney->getResidentcountryAttorney()->getCountryCodeLabel()); 
                    $alertservice->countryAlert($client_id, "Résidence $actourl", $residencecountry, $request, $api, 1, $attorney->getResidentcountryAttorney()->getDescCountry(), $lastid);
                }

                $fiscalcountry = null;
                if($attorney->getFiscalcountryAttorney()){
                    $fiscalcountry=strtoupper($attorney->getFiscalcountryAttorney()->getCountryCodeLabel()); 
                    $alertservice->countryAlert($client_id, "Résidence fiscale $actourl", $fiscalcountry, $request, $api, 1, $attorney->getFiscalcountryAttorney()->getDescCountry(), $lastid);
                }

                $nationality = null;
                if($attorney->getNationalityAttorney()){
                    $nationality=strtoupper($attorney->getNationalityAttorney()->getCountryCodeLabel());
                    $alertservice->countryAlert($client_id, "Nationalité $actourl", $nationality, $request, $api, 1, $attorney->getNationalityAttorney()->getDescCountry(), $lastid);
                }
            } else {
                $nationality = null;
                if($attorney->getNationalityAttorney()){
                    $nationality=strtoupper($attorney->getNationalityAttorney()->getCountryCodeLabel());
                    $alertservice->countryAlert($client_id, "Résidence $actourl", $nationality, $request, $api, 1, $attorney->getNationalityAttorney()->getDescCountry(), $lastid);
                }
            }
            
        }
        
        $whereclients  = $whereclientrepo->findBy(['client' => $client_id]);
        foreach($whereclients as $where){	
            $country = null;
            if($where->getCountry()) {
                $country = strtoupper($where->getCountry()->getCountryCodeLabel());
				$alertservice->countryAlert($client_id, "Pays clientèles", $country, $request, $api, 1, $where->getCountry()->getDescCountry(), $lastid);
            }
        }

        $wheresuppliers  = $wheresupplierrepo->findBy(['client' => $client_id]);
        foreach($wheresuppliers as $where){	
            $country = null;
            if($where->getCountry()) {
                $country = strtoupper($where->getCountry()->getCountryCodeLabel());
				$alertservice->countryAlert($client_id, "Pays fournisseurs", $country, $request, $api, 1, $where->getCountry()->getDescCountry(), $lastid);
            }
        }
		return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success',["timezone"]);		
    }

    /**
     * @Route("/api/getvirement/{request_id}", name="getvirement",methods={"GET"})
     */
    public function getVirement($request_id, Request $request, AlertService $alert, ApiHelper $api, DataClientSabRepository $datasabrep)
    {
        $datasab = $datasabrep->findOneBy(['request' => $request_id]);
        if ($datasab != null) {            
            $account = $datasab->getIban();
        } else {
            return new ApiResponse(['unknow iban'],200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
        }
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $alert->virementAlert($request_id, $account, $request, $api, 1);          
        return new ApiResponse($data, 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/simulator/{type}", name="simulator",methods={"POST"})
     */
    public function getSimulation($type, Request $request, AlertService $alert, ApiHelper $api)
    {
        $content = json_decode($request->getContent());                
        $data = array();
        switch ($type) {
            case 1:
                $data = $alert->apeAlert(0, $content->APE, $request, $api, 0, 1);
                break;
            case 2:
                $data = $alert->personaldataAlert(0, $content->nom, $content->prenom,$content->birthname, 0, $request, $api, 0, 1, '');
                break;
            case 3:
                $data = $alert->countryAlert(0, 0, $content->pays, $request, $api, 0, "", 1);
                break;
        }
        return new ApiResponse($data, 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/user/getone/{login}", name="userrole",methods={"GET"})
     */
    public function getUserRole($login, Request $request, DataUserRoleRepository $usrRoleRepo)
    {
        $myuser = $usrRoleRepo->findOneBy(['user_login' => $login, 'active_user_role' => true]);
        if (!$myuser) {
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid user');    
        }
		
		$ldapconn = ldap_connect("ldap://dom01.fr:389");
		if (!ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3)) {

		}
		
		$ldapbind = @ldap_bind($ldapconn, "dom01\\dore", "Mle2020*");		
		$count 	 = 0;
		if ($ldapconn) {
            if ($ldapbind) {
				$trouve = false;
				$base = 'OU=Applications,OU=Utilisateurs,DC=dom01,DC=fr';
                $filtre = '(cn=*_EER_*)';
                $attributes = array();
                $attributes[] = 'member';
                $attributes[] = 'samaccountname';
                $attributes[] = 'sn';
                $attributes[] = 'givenname';
                $result = ldap_search($ldapconn, $base, $filtre, $attributes);
                if (FALSE !== $result){
                    $entree = ldap_get_entries($ldapconn, $result);										
                    for ($x=0; $x < count($entree[0]['member']); $x++){
                        if (isset($entree[0]['member'][$x])){
                            $result2 = ldap_search($ldapconn, $entree[0]['member'][$x], "(cn=*)", $attributes);							
                            if (FALSE !== $result2) {
                                $entreeN2 = ldap_get_entries($ldapconn, $result2);								
                                for ($y=0; $y < count($entreeN2[0]['member']); $y++){
                                    if (isset($entreeN2[0]['member'][$y])){	
                                        $result3 = ldap_search($ldapconn, $entreeN2[0]['member'][$y], "(cn=*)", $attributes);										
                                        $count 	 = 0;
                                        if (FALSE !== $result3) {
                                            $entreeN3 = ldap_get_entries($ldapconn, $result3);											
                                            if (isset($entreeN3[0]['samaccountname'][0])) { 
                                                $logins 	= $entreeN3[0]['samaccountname'][0];
												if ($login == $logins) {
													$trouve = true;
													break;
												}
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                ldap_unbind($ldapconn);
				if ($trouve == false) {
					return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid user');    
				}
			}
		}
        return new ApiResponse($myuser, 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/user/getall", name="userall",methods={"GET"})
     */
    public function getAllUser(Request $request, DataUserRoleRepository $usrRoleRepo) {
        $myuser = $usrRoleRepo->findAll();
        if (!$myuser) {
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid user');    
        }
        return new ApiResponse($myuser, 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }

    /**
     * @Route("/api/user/updone", name="userupdone",methods={"POST"})
     */
    public function updUser(Request $request, DataUserRoleRepository $usrRoleRepo, DataRoleRepository $roleRepo) {
        $content = json_decode($request->getContent());                

        if (!$content->id || !$content->role) {
            return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','Data error',["timezone"]);    
        }

        $myuser = $usrRoleRepo->findOneBy(['id' => $content->id]);
        if (!$myuser) {
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid user');  
        }
        
        $myrole = $roleRepo->findOneBy(['id' => $content->role]);
        if (!$myrole) {
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid role');  
        }
        $myuser->setRole($myrole);

        $entityManager = $this->EM;
        $entityManager->persist($myuser);
        $entityManager->flush();

        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/user/status", name="userupdstatus",methods={"POST"})
     */
    public function updUserStatus(Request $request, DataUserRoleRepository $usrRoleRepo, DataRoleRepository $roleRepo) {
        $content = json_decode($request->getContent());                
        if (!$content->id || !(isset($content->status))) {
            return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','Data error',["timezone"]);    
        }
        $myuser = $usrRoleRepo->findOneBy(['id' => $content->id]);
        if (!$myuser) {
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid user');  
        }
        $myuser->setActiveUserRole($content->status);
        $entityManager = $this->EM;
        $entityManager->persist($myuser);
        $entityManager->flush();
        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);    
    }

    /**
     * @Route("/api/user/checkad", name="userad",methods={"GET"})
     */
    public function getAllUserAd(Request $request, DataUserRoleRepository $usrRoleRepo, DataRoleRepository $roleRepo) {
        
        $ldapconn = ldap_connect("ldap://dom01.fr:389");
		if (!ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3)) {

		}
		$ldapbind = @ldap_bind($ldapconn, "dom01\\dore", "Mle2020*");		
		$count 	 = 0;
		if ($ldapconn) {
            if ($ldapbind) {
                $base = 'OU=Applications,OU=Utilisateurs,DC=dom01,DC=fr';
                $filtre = '(cn=*_EER_*)';
                $attributes = array();
                $attributes[] = 'member';
                $attributes[] = 'samaccountname';
                $attributes[] = 'sn';
                $attributes[] = 'givenname';
                $result = ldap_search($ldapconn, $base, $filtre, $attributes);
                if (FALSE !== $result){
                    $entree = ldap_get_entries($ldapconn, $result);										
                    for ($x=0; $x < count($entree[0]['member']); $x++){
                        if (isset($entree[0]['member'][$x])){
                            $result2 = ldap_search($ldapconn, $entree[0]['member'][$x], "(cn=*)", $attributes);							
                            if (FALSE !== $result2) {
                                $entreeN2 = ldap_get_entries($ldapconn, $result2);								
	
                                for ($y=0; $y < count($entreeN2[0]['member']); $y++){
                                    if (isset($entreeN2[0]['member'][$y])){	
                                        $result3 = ldap_search($ldapconn, $entreeN2[0]['member'][$y], "(cn=*)", $attributes);										
                                        $count 	 = 0;
                                        if (FALSE !== $result3) {
                                            $entreeN3 = ldap_get_entries($ldapconn, $result3);																		
						
                                            if (isset($entreeN3[0]['samaccountname'][0])) { 
                                                $login 	= $entreeN3[0]['samaccountname'][0];	
                                                $nom 	= $entreeN3[0]['sn'][0];		
                                                $prenom = $entreeN3[0]['givenname'][0];														
                                                $myuser = $usrRoleRepo->findOneBy(['user_login' => $login]);
                                                if (!$myuser) {
                                                    $myuser = new DataUserRole();
                                                    $myuser->setUserLogin($login);
                                                    $myuser->setNomUserRole($nom);
                                                    $myuser->setPrenomUserRole($prenom);													
                                                    $myrole = $roleRepo->findOneBy(['id' => 19]);
                                                    $myuser->setRole($myrole);
                                                    
                                                    $myuser->setActiveUserRole(false);
                                                    $entityManager = $this->EM;
                                                    $entityManager->persist($myuser);
                                                    $entityManager->flush();
                                                    $count++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
				
				$myuser = $usrRoleRepo->findAll();

				foreach ($myuser as $muser) {	
					$login	= $muser->getUserLogin();			
					$trouve = false;
					$base = 'OU=Applications,OU=Utilisateurs,DC=dom01,DC=fr';
					$filtre = '(cn=*_EER_*)';
					$attributes = array();
					$attributes[] = 'member';
					$attributes[] = 'samaccountname';
					$attributes[] = 'sn';
					$attributes[] = 'givenname';
					$result = ldap_search($ldapconn, $base, $filtre, $attributes);
					if (FALSE !== $result){
						$entree = ldap_get_entries($ldapconn, $result);										
						for ($x=0; $x < count($entree[0]['member']); $x++){
							if (isset($entree[0]['member'][$x])){
								$result2 = ldap_search($ldapconn, $entree[0]['member'][$x], "(cn=*)", $attributes);							
								if (FALSE !== $result2) {
									$entreeN2 = ldap_get_entries($ldapconn, $result2);								
									for ($y=0; $y < count($entreeN2[0]['member']); $y++){
										if (isset($entreeN2[0]['member'][$y])){	
											$result3 = ldap_search($ldapconn, $entreeN2[0]['member'][$y], "(cn=*)", $attributes);										
											$count 	 = 0;
											if (FALSE !== $result3) {
												$entreeN3 = ldap_get_entries($ldapconn, $result3);											
												if (isset($entreeN3[0]['samaccountname'][0])) { 
													$logins 	= $entreeN3[0]['samaccountname'][0];
													if ($login == $logins) {
														$trouve = true;
														break;
													}
												}
											}
										}
									}
								}
							}
						}
					}
					
					if ($trouve == false) {
						$muser->setActiveUserRole(false);
						$entityManager = $this->EM;
						$entityManager->persist($muser);
						$entityManager->flush();
					}
				}
                ldap_unbind($ldapconn);
			}
		}
				
        return new ApiResponse([$count], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }

    /**
     * @Route("/api/integrer/{requestid}", name="integrer",methods={"GET"})
     */
    public function integrer($requestid, IntegrationService $integration, ApiHelper $api, DataClientRepository $clientrepo, DataAttorneyRepository $attorneyrepo, DataUserspaceRepository $userspacerepo,  DataContactRepository $datacontrepo, OptionsTabelRepository $prodrepo) {
        $integration->sendIntegrationData($requestid, $api, $clientrepo, $attorneyrepo, $userspacerepo, $datacontrepo, $prodrepo);
		return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }
	
	/**
     * @Route("/api/integrerstep2/{requestid}", name="integrerstep2",methods={"GET"})
     */
    public function integrerstep2($requestid, IntegrationService $integration, ApiHelper $api, DataClientSabRepository $sabrepo, DataClientRepository $clientrepo, DataRequestFileRepository $datareqfilerepo, RefFileRepository $reffilerepo) {
        $integration->sendIntegrationFinal($requestid, $api, $sabrepo, $clientrepo, $datareqfilerepo, $reffilerepo);
		return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }

    /**
     * @Route("/api/status/next/{type}", name="nextStatus",methods={"POST"})
     */
    public function nextStatus(Request $request, $type, RefRequestStatusOrderRepository $refRequestStatusOrderRepo) {        
        
        $content = $request->getContent();        
        $content = json_decode($content, true);

        $find = ['levelCode' => $content['level'], 'actionCode' => $content['action'], 
                    'isCto' => $content['iscto'], 'isRetourN0' => $content['retour'], 
                    'isAvisN2N0' => $content['avis'], 'isRetourN1' => $content['retourn'],
                    'isAvisN2N1' => $content['avisn'], 'initialStatusCode' => $content['status']];

        $statuses = $refRequestStatusOrderRepo->findOneBy($find);
        if ($statuses !== null) {
            if ($type == 1)
                return new ApiResponse([$statuses->getNextStatusCodeExist()], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
            else
                return new ApiResponse([$statuses->getNextStatusCodeNew()], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
        }
        return new ApiResponse([$statuses], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }

    /**
     * @Route("/api/status/detail/{code}/{type}", name="getStatusDetail",methods={"GET"})
     */
    public function getStatusDetail($code, $type, RefRequestStatusOrderRepository $refRequestStatusOrderRepo) {                
        
        if ($type == 1) {
            $statuses = $refRequestStatusOrderRepo->findOneBy(['nextStatusCodeExist' => $code]);
            if ($statuses == null) {
                $statuses = $refRequestStatusOrderRepo->findOneBy(['nextStatusCodeNew' => $code]);
            }
        } else {
            $statuses = $refRequestStatusOrderRepo->findOneBy(['initialStatusCode' => $code]);
        }

        if ($statuses !== null) {
            return new ApiResponse([$statuses], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
        }
        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }

    /**
     * @Route("/api/recap/{client_id}", name="getRecap",methods={"GET"})
     */
    public function getRecap($client_id, DocumentService $ds, EmailServiceV1 $es) {
        $ds->makeRecap($client_id, $es);
        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    }

    /**
     * @Route("/api/accountinfo/{client_id}", name="getaccountinfo",methods={"GET"})
     */
    public function getAccountInformation($client_id, DataClientSabRepository $datasabrep) {
        $sab = array();
        $datasab = $datasabrep->findOneBy(['request' => $client_id]);
        if ($datasab != null) {
            $sab['numero'] = $datasab->getNumero();
            $sab['iban'] = $datasab->getIban();
        }
        return new ApiResponse([$sab], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }


    /**
     * @Route("/api/pays/update", name="updatePays",methods={"GET"})
     */
    public function updatePays(ApiHelper $api, RefCountryRepository $refcountryrepo) {

        $entityManager = $this->EM;
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);

        if ($this->getParameter('app.where') == 'internal') {
            $paysapi = $api->decodeData($api->apiUpd("Fiducial/Listepays", []));
        } else {
            $paysapi = array();
        }
		
        $listpayssab = "A";
        $listpayseer = "A";
        foreach ($paysapi->pays as $key => $value) {
            $listpayssab .= ";".strtoupper($value->code).";";
        }

        $refcountry = $refcountryrepo->findAll();        
        foreach ($refcountry as $key => $value) {
            $listpayseer .= ";".strtoupper($value->getCountryCodeLabel()).";";
            
            $mycountry = $value;
            if (strpos($listpayssab, ";".strtoupper($value->getCountryCodeLabel()).";") == 0) {
                $mycountry->setActiveCountry("Disabled");
                $entityManager->persist($mycountry);
                $entityManager->flush();
            } else {
                $mycountry->setActiveCountry("Active");
                $entityManager->persist($mycountry);
                $entityManager->flush();
            }

        }

        foreach ($paysapi->pays as $key => $value) {
            
            $mycountry = $value;
            if (strpos($listpayseer, ";".strtoupper($value->code).";") == 0) {
                $refTabelRepo = $this->EM->getRepository(RefTable::class);
                
                $mycountry = new RefCountry();

                $mycountry->setCodeCountry($refTabelRepo->next('ref_country'));            

                $mycountry->setCountryCodeLabel(strtoupper($value->code));
                $mycountry->setIsEuropean("NO");
                $mycountry->setCountryCode("000");
                $mycountry->setDescCountry($value->Nom);
                $mycountry->setActiveCountry("Disabled");
                $entityManager->persist($mycountry);                

                $refLabelTemp = new RefLabel();
                $lang = $refLangLabelRepo->findOneBy(['code_language'=>'fr']);
                $refLabelTemp->setLangLabel($lang);
                $refLabelTemp->setLabelLabel($value->Nom);
                $refLabelTemp->setCodeLabel($mycountry->getCodeCountry());
                $refLabelTemp->setActiveLabel("Active");
                $entityManager->persist($refLabelTemp);

                $entityManager->flush();
            } 
            
        }

        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);   
    }
	
	/**
     * @Route("/api/ape/update", name="updateApe",methods={"GET"})
     */
    public function updateApe(ApiHelper $api, RefEpaRepository $refeparepo) {

        $entityManager = $this->EM;
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        if ($this->getParameter('app.where') == 'internal') {
            $apeapi = $api->decodeData($api->apiUpd("Fiducial/Listeape", []));
        } else {
            $apeapi = array();
        }
        $listapesab = "A";
        $listapeeer = "A";
        foreach ($apeapi->ape as $key => $value) {
            $listapesab .= ";".strtoupper($value->code).";";
        }		
        $refape = $refeparepo->findAll();        
        foreach ($refape as $key => $value) {
            $listapeeer .= ";".strtoupper($value->getEpaCode()).";";            
            $myepa = $value;
            if (strpos($listapesab, ";".strtoupper($value->getEpaCode()).";") == 0) {				
                $myepa->setActiveEpa("Disabled");
                $entityManager->persist($myepa);
                $entityManager->flush();
            } else {
                $myepa->setActiveEpa("Active");
                $entityManager->persist($myepa);
                $entityManager->flush();
            }
        }
        foreach ($apeapi->ape as $key => $value) {
            $myepa = $value;
            if (strpos($listapeeer, ";".strtoupper($value->code).";") == 0) {
                $refTabelRepo = $this->EM->getRepository(RefTable::class);
                $myepa = new RefEpa();
                $myepa->setCodeEpa($refTabelRepo->next('ref_epa'));            
                $myepa->setEpaCode(strtoupper($value->code));
                $myepa->setDescEpa($value->Nom);
                $myepa->setActiveEpa("Active");
                $entityManager->persist($myepa);
	
				$refLabelTemp = new RefLabel();
                $lang = $refLangLabelRepo->findOneBy(['code_language'=>'fr']);
                $refLabelTemp->setLangLabel($lang);
                $refLabelTemp->setLabelLabel($value->Nom);
                $refLabelTemp->setCodeLabel($myepa->getCodeEpa());
                $refLabelTemp->setActiveLabel("Active");
                $entityManager->persist($refLabelTemp);			
                $entityManager->flush();
            } 
        }
        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);   
    }

    /**
     * @Route("/api/getdocinfo/{doc}/{reqid}", name="getdocinfo",methods={"GET"})
     */
    public function getDocInfo($doc, $reqid, RefFileRepository $reffilerepo, DataRequestFileRepository $datarfrepo) {

        switch ($doc) {
            case 'RIB':
                $reffile = $reffilerepo->findOneBy(['jsonkey' => 'RIB_Fiducial']);
                break;  
            case 'attestationdepot':
                $reffile = $reffilerepo->findOneBy(['jsonkey' => 'attestation']);
                break;
			case 'synthese':
                $reffile = $reffilerepo->findOneBy(['jsonkey' => 'synthese']);
                break;
			case 'contrat':
                $reffile = $reffilerepo->findOneBy(['jsonkey' => 'contrat']);
                break; 				
            default:
                return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','Code not found',["timezone"]);   
                break;  
        }
        if (!$reffile) {
            return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','File not found',["timezone"]);   
        }		
        $datarf = $datarfrepo->findOneBy(['file' => $reffile->getId(), 'request' => $reqid]);		
        if (!$datarf) {
            return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','File not found',["timezone"]);   
        }
        return new ApiResponse([$datarf->getFileUuid()], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    }

    /**
     * @Route("/ajax/siren/{siren}", name="siren")
     */
    public function getSirenDatas($siren, Request $request, ApiHelper $api): Response
    {
        // Getting the token
        $options = [
            'auth' => ['uPGeHNp0YcseQRjHTP1bOqoV_K0a', 'p2vWSjpQUApvdoxb1uQjNraHBVoa'],
            'form_data' => ['grant_type' => 'client_credentials'],
        ];
        $response = $api->inseeApiPost('token?grant_type=client_credentials', $options);
        $token = $response->access_token;

        // Calling the service
        $options = [
            'headers' => ['Authorization' => 'Bearer ' . $token, 'Accept' => '*/*'],
        ];
        
        if (strlen($siren) == 9) {
            $response = $api->inseeApiGet('entreprises/sirene/V3/siret?q=siren:' . $siren, $options);
            $jsonobj = array();
            if (isset($response->header->statut) && $response->header->statut == "200") {
                $nbent = $response->header->nombre;
                for ($i = 0; $i < $nbent; $i++) {
                    if ($response->etablissements[$i]->periodesEtablissement[0]->dateFin == null) {
                        $jsonobj[$i]['siret'] = $response->etablissements[$i]->siret;
                        $jsonobj[$i]['denomination'] = $response->etablissements[$i]->uniteLegale->denominationUniteLegale;

                        $jsonobj[$i]['adresse'] = $response->etablissements[$i]->adresseEtablissement->numeroVoieEtablissement . ' ' .
                        $response->etablissements[$i]->adresseEtablissement->typeVoieEtablissement . ' ' .
                        $response->etablissements[$i]->adresseEtablissement->libelleVoieEtablissement;

                        $jsonobj[$i]['cp'] = $response->etablissements[$i]->adresseEtablissement->codePostalEtablissement;
                        $jsonobj[$i]['ville'] = $response->etablissements[$i]->adresseEtablissement->libelleCommuneEtablissement;

                        $jsonobj[$i]['categorie'] = $response->etablissements[$i]->uniteLegale->categorieJuridiqueUniteLegale;

                        $jsonobj[$i]['APE'] = $response->etablissements[$i]->uniteLegale->activitePrincipaleUniteLegale;
                    }
                }
            }
        } elseif (strlen($siren) == 14) {
            $response = $api->inseeApiGet('entreprises/sirene/V3/siret/' . trim($siren), $options);
            $jsonobj = array();
            if (isset($response->header->statut) && $response->header->statut == "200") {
                $nbent = 1;
                for ($i = 0; $i < $nbent; $i++) {
                    $jsonobj[$i]['siret'] = $response->etablissement->siret;
                    $jsonobj[$i]['denomination'] = $response->etablissement->uniteLegale->denominationUniteLegale;
    
                    $jsonobj[$i]['adresse'] = $response->etablissement->adresseEtablissement->numeroVoieEtablissement . ' ' .
                    $response->etablissement->adresseEtablissement->typeVoieEtablissement . ' ' .
                    $response->etablissement->adresseEtablissement->libelleVoieEtablissement;
    
                    $jsonobj[$i]['cp'] = $response->etablissement->adresseEtablissement->codePostalEtablissement;
                    $jsonobj[$i]['ville'] = $response->etablissement->adresseEtablissement->libelleCommuneEtablissement;
    
                    $jsonobj[$i]['categorie'] = $response->etablissement->uniteLegale->categorieJuridiqueUniteLegale;
    
                    $jsonobj[$i]['APE'] = $response->etablissement->uniteLegale->activitePrincipaleUniteLegale;
                }
            }        
        }       
        return new ApiResponse([$jsonobj], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    }
	
	/**
     * @Route("/api/virement", name="virement")
     */
    public function getVirementsDatas(Request $request, ApiHelper $api, RefRequeststatusRepository $refreqreqstatusrepo, DataRequestRepository $datareqrepo, 
    AlertService $alert, DataClientSabRepository $datasabrep, DataClientRepository $dataclirep, DataTransactionRepository $datatransrep): Response 
    {    
        $arrstatus = ['150', '151', '152', '158'];
        // Listing all "virements initials"
        $statuss = $refreqreqstatusrepo->findBy(array('status_requeststatus' => $arrstatus));
        $ids = array();
        foreach ($statuss as $status) {
            array_push($ids,$status->getId());
        }

        $datareqs = $datareqrepo->findBy(array('requeststatus' => $ids));
        $data = array();	
        foreach ($datareqs as $datareq) {
            
            $datasab = $datasabrep->findOneBy(['request' => $datareq->getId()]);
            $datacli = $dataclirep->findOneBy(['id' => $datareq->getId()]);			
            if ($datasab != null) {
                $account = $datasab->getIban();
                
                $data    = $alert->virementAlert($datareq->getId(), $account, $request, $api, 1);
				
                $nbTransaction = count($data);
				
                $datatrans = $datatransrep->findOneBy(['client' => $datareq->getId()], ["id" => "DESC"]);
                if ($datatrans != null && $nbTransaction > $datatrans->getNbtransaction() && $nbTransaction > 0) {        
					
                    $entityManager = $this->EM;
                    
                    $datatransaction = new DataTransaction();
                    $datatransaction->setNbtransaction($nbTransaction);
                    $datatransaction->setDateCreation(new \DateTime());
                    $datatransaction->setDateReceived(new \DateTime());
                    $datatransaction->setReceived(false);
                    $datatransaction->setClient($datacli);

                    $entityManager->persist($datatransaction);
                    $entityManager->flush();
                } else {
					if ($datatrans == null && $nbTransaction > 0) {
						$entityManager = $this->EM;
                    
						$datatransaction = new DataTransaction();
						$datatransaction->setNbtransaction($nbTransaction);
						$datatransaction->setDateCreation(new \DateTime());
						$datatransaction->setDateReceived(new \DateTime());
						$datatransaction->setReceived(false);
						$datatransaction->setClient($datacli);

						$entityManager->persist($datatransaction);
						$entityManager->flush();
					}
				}
            }

        }		
        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    }

    /**
     * @Route("/api/update/receveid/{id}", name="received")
     */
    public function updateReceived($id, DataTransactionRepository $datatransrep) 
    {
        $datatransaction = $datatransrep->findOneBy(['client' => $id], ["id" => "DESC"]);
        if ($datatransaction) {
            $entityManager = $this->EM;
            $datatransaction->setDateReceived(new \DateTime());
            $datatransaction->setReceived(true);
            $entityManager->persist($datatransaction);
            $entityManager->flush();
            return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
        }
        return new ApiResponse(["Data not found"], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    }

    /**
     * @Route("/api/csv/requestdetail", name="requestdetail",methods={"POST"})
     */
    public function requestdetail(DataRequestRepository $datareqrepo,DataUserspaceRepository $userspaceRepo,
    DataRequestRepository $datarepo,DataClientRepository $clientrepo,EmailServiceV1 $emailserviceV1,
    DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,
    LegalformService $legalformservice,Request $request,RefRequeststatusRepository $refreqreqstatusrepo, RefTypeclientRepository $typeclientrep, DataRequestRequeststatusRepository $datareqreqstatus, DataTransactionRepository $datatransrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
       
        $normalizers = array($normalizer,new DateTimeNormalizer());
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrDate = array();
        $arrDate = $status->getDate();
        $datecount = count($arrDate);
        if($datecount == 1){
            $requestIds = array();
            $query = "SELECT id FROM data_request WHERE CAST(date_request AS DATE) =:fromdate";
            $conn = $entityManager->getconnection();
            $datas = $conn->executeQuery($query, ['fromdate' => (string)$arrDate[0]])->fetchAll();
            foreach($datas as $data){
                array_push($requestIds,$data['id']);
            }
        }
        if($datecount == 2){
            $requestIds = array();
            $query = "SELECT id FROM data_request WHERE date_request BETWEEN :fromdate AND :todate";
            $conn = $entityManager->getconnection();
            $datas = $conn->executeQuery($query, ['fromdate' => (string)$arrDate[0],'todate' => (string)$arrDate[1]])->fetchAll();    
            foreach($datas as $data){
                array_push($requestIds,$data['id']);
            }
        }
        $datareqs = $datareqrepo->findBy(array('id' => $requestIds));    
        $data = array();
        $requestdetails = $emailserviceV1->getForRequestDetail($datareqs);
        // return new ApiResponse($requestdetails, 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
        $list = array (
            array('SAB_ID', 'COMPANY_NAME', 'CREATE_DATE', 'TYPECLIENT','PRODUCT'),
        );
        if($requestdetails){
            foreach($requestdetails as $requestdetail){
                array_push($list,$requestdetail);
            }
        }
        
        $fp = fopen("sample.csv", "w");
        foreach ($list as $line)
        {
            fputcsv(
                $fp, // The file pointer
                $line, // The fields
                ',' // The delimiter
            );      
        }
     
        fclose($fp);
        header("Content-type: text/csv");
        header("Content-disposition: attachment; filename = sample.csv");
        $readed = readfile("sample.csv");
        $deletfilepath = "sample.csv";
        $filesystem = new Filesystem();
        $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
        $transport =(new Swift_SmtpTransport('smtp.gmail.com','465','ssl'))
        ->setUsername("vijayarethinam.info@gmail.com")
        ->setPassword("sample@123");
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();
        $message->setFrom('vijayarethinam.info@gmail.com');
        $message->setTo('vijayarethinam.info@gmail.com');
        $message->attach(new Swift_Attachment($readed, 'check.csv','application/csv'));
        $mailer->send($message);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');   
    } 
    

    /**
     * @Route("/api/make/att/{id}", name="att")
     */
    public function makeAttDep($id, DocumentService $ds, EmailServiceV1 $es) 
    {
        $entityManager = $this->EM;
        $ds->makeAttestationDepot($id, $es, $entityManager);           
        return new ApiResponse([], 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);
    }

    /**
     * @Route("/api/csv/zipcodeinsert", name="csvzip")
     */
    public function csvZipcode() 
    {
        $fp = fopen("departement2020.csv", 'r');
        $header = fgetcsv($fp);
        $datas = array();
        while ($row = fgetcsv($fp)) {
        $arr = array();
        foreach ($header as $i => $col)
        $arr[$col] = $row[$i];
        $datas[] = $arr;
        }
        $entityManager = $this->EM;
        foreach($datas as $data){
        $reftown = new RefTown;
        $reftown->setDep($data['dep']);
        $reftown->setName($data['ncc']);
        $entityManager->persist($reftown);
        $entityManager->flush();
        }
        return new ApiResponse($data, 200,["Content-Type"=>"application/json"],'json','success',["timezone"]);  
    }

}
 