<?php

namespace App\Service;

use App\Entity\RefLabel;
use App\Entity\RefFile;
use App\Entity\DataRequest;

use App\Entity\EmailAction;

use App\Entity\RefLanguage;
use App\Entity\RefVariable;
use App\Entity\DataTemplate;
use App\Entity\DataUserspace;
use App\Entity\RefEmailStatus;
use App\Entity\DataRequestFile;
use App\Service\EmailServiceV1;
use App\Entity\RefRequeststatus;
use App\Entity\DataTreatment;

use App\UtilsSer\DataReqReqStatus;
use App\Entity\RefRequestStatusOrder;
use App\Entity\DataRequestRequeststatus;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DataTemplateRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DataReq_ReqstatusService{

    private $EM;
    private $emailservice;
    private $params;
    public function __construct(EntityManagerInterface $EM,EmailServiceV1 $emailservice, ParameterBagInterface $params)
    {
        $this->EM = $EM;
        $this->emailservice = $emailservice;
        $this->params = $params;
    }
    public function create($datareqreqstatus,$entityManager){

        $requeststatusrepo=$this->EM->getRepository(RefRequeststatus::class);
        $datarequestrepo=$this->EM->getRepository(DataRequest::class);
        
        $requestatuschk=$requeststatusrepo->findOneBy(['id'=>$datareqreqstatus->getIdRequeststatus()]);
        if(!$requestatuschk){
            return 'requeststatus id is invalid';  
        }
        $datarequestchk=$datarequestrepo->findOneBy(['id'=>$datareqreqstatus->getIdRequest()]);
        if(!$datarequestchk){
            return 'datarequest id is invalid';  
        }

        $datarequestchk->setRequeststatus($requestatuschk);
        $entityManager->persist($datarequestchk);
         $entityManager->flush();
        $datarequestrequeststatus=new DataRequestRequeststatus();
        $datarequestrequeststatus->setIdRequest($datarequestchk);
        $datarequestrequeststatus->setIdRequeststatus($requestatuschk);
        $datarequestrequeststatus->setDateRequestRequeststatus(new \DateTime());
        $entityManager->persist($datarequestrequeststatus);
        $entityManager->flush();

        return $datareqreqstatus;
    }

    public function createWithCode($eloquaservice,$datareqreqstatus,$entityManager, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $es, $ds){

        $requeststatusrepo  = $this->EM->getRepository(RefRequeststatus::class);
        $datarequestrepo    = $this->EM->getRepository(DataRequest::class);
        
        $requestatuschk = $requeststatusrepo->findOneBy(['status_requeststatus'=>$datareqreqstatus->getCode()]);
        if(!$requestatuschk){
            return 'requeststatus code is invalid';  
        }
        $datarequestchk = $datarequestrepo->findOneBy(['id'=>$datareqreqstatus->getIdRequest()]);
        if(!$datarequestchk){
            return 'datarequest id is invalid';  
        }

        if ($this->params->get('app.where') == 'internal') {
            switch ($requestatuschk->getIntegration()) {
                case 1:
                    $integration->sendIntegrationData($datareqreqstatus->getIdRequest(), $api, $clientrepo, $attorneyrepo, $userspacerepo, $datacontrepo, $prodrepo);
                    break;
                case 2:
				
					$datareqfilerepo  = $this->EM->getRepository(DataRequestFile::class);
                    $reffilerepo      = $this->EM->getRepository(RefFile::class);
					
                    $integration->sendIntegrationFinal($datareqreqstatus->getIdRequest(), $api, $sabrepo, $clientrepo, $datareqfilerepo, $reffilerepo);
					
					// Getting recap at same time
					$response = $api->apiGetBack("recap/".$datareqreqstatus->getIdRequest()."/doc");
					if ($response) {
						$folder         = '../file/'.$datareqreqstatus->getIdRequest().'/';   
						$filesystem     = new Filesystem();
						$path           = $filesystem->exists($folder);
						if ($path == false) {
							$filesystem->mkdir($folder);
						}
						file_put_contents($folder."synthese_".$datareqreqstatus->getIdRequest().".pdf", $response->getBody());	

						$unnid = $ds->UuidGenerate($datareqreqstatus->getIdRequest());
						
						$reffilerepo = $this->EM->getRepository(RefFile::class);
						$reffile = $reffilerepo->findOneBy(['jsonkey'=>'synthese']);

						$datareqfile   =  new DataRequestFile();
						$datareqfile   -> setFile($reffile);
						$datareqfile   -> setRequest($datarequestchk);
						$datareqfile   -> setPath($folder."synthese_".$datareqreqstatus->getIdRequest().".pdf");
						$datareqfile   -> setFilename("EER Fiducial - Synthese.pdf");
						$datareqfile   -> setFileUuid($unnid);
						$entityManager -> persist($datareqfile);
						$entityManager -> flush();
											
					}
                    $response = $api->apiGetBack("recap/".$datareqreqstatus->getIdRequest()."/doc");
					
                    break;
                default: 
                    break;
            }

            if ($requestatuschk->getAttDepot() == 1) {
                $ds->makeAttestationDepot($datareqreqstatus->getIdRequest(), $es, $entityManager);                               
            }
        }

        $datarequestchk->setRequeststatus($requestatuschk);
        $entityManager->persist($datarequestchk);
        $entityManager->flush();
		
		$res = $this->getLastDataRequestRequestStatus($datarequestchk->getId());
		
        $datarequestrequeststatus = new DataRequestRequeststatus();
        $datarequestrequeststatus->setIdRequest($datarequestchk);
        $datarequestrequeststatus->setIdRequeststatus($requestatuschk);
        $datarequestrequeststatus->setLoginRequestRequeststatus((string) $datareqreqstatus->getLogin());
        $datarequestrequeststatus->setDateRequestRequeststatus(new \DateTime());
        $entityManager->persist($datarequestrequeststatus);
        $entityManager->flush();
		
		$res1 = $this->getLastDataRequestRequestStatus($datarequestchk->getId());
		$datatreatment = $this->insertDataTreatment($res,$res1);
		
        $step = $requestatuschk->getStep();        
		if($requestatuschk->getEloqua() == 1) {
            $data = $eloquaservice->eloquaCall($datarequestchk,$userspacerepo,$step);
        }

        $msg = $this->checkEmailStatus($datarequestrequeststatus, $requestatuschk, true);
        $checkstatus = [190,191];
        if (in_array($datareqreqstatus->getCode(), $checkstatus)){
            $datauserspacerepo = $this->EM->getRepository(DataUserspace::class);
            $datauserspace = $datauserspacerepo->findOneBy(['id_request'=>$datarequestchk]);
            $datauserspace->setActiveUs("Deactive");
            $entityManager->persist($datarequestrequeststatus);
            $entityManager->flush();
        }
        
        return "success";
    }

    public function checkEmailStatus($datarequestrequeststatus,$requestatuschk, $norib){

        $emailstatusrepo    = $this->EM->getRepository(RefEmailStatus::class);
        $userspacerepo      = $this->EM->getRepository(DataUserspace::class);
        $datatemplaterepo   = $this->EM->getRepository(DataTemplate::class);

        $requeststatus      = $requestatuschk->getStatusRequeststatus();

        $action = $emailstatusrepo->findBy(['statusCode' => $requeststatus,'statusActive' => "Active"]);

        if(!$action){
            return "invalid status";
        }
        foreach ($action as $act) {
            $actionId = $act->getActionId();

            $emailstatus = $datatemplaterepo->findOneBy(['action_id' => $actionId, 'status' => "Active"]);
            if(!$emailstatus) {
                return "invalid action";
            }

            $client_id  = $datarequestrequeststatus->getIdRequest()->getClient()->getId();
            $userspace  = $userspacerepo->findOneBy(['id_request' => $datarequestrequeststatus->getIdRequest()]);
			$to_email   = $userspace->getEmailUs();
			if ($actionId == 40 || $actionId == 41) {
				$to_email = $this->params->get('app.mailum');
			} 

            $varrepo        = $this->EM->getRepository(RefVariable::class);
            $datatemprepo   = $this->EM->getRepository(DataTemplate::class); 
			
            $data           = $this->emailservice->sendEmail($varrepo, $datatemprepo, $client_id, $actionId, $to_email, $norib);
			
        }
        return "success";
    }


    public function getStatusDetail($request_id,$clientrepo,$clientservice,$refreqreqrepo){
        $datas     =  $refreqreqrepo->findBy(['id_request'=>$request_id], ['id' => 'DESC']);
        $arraydata=array();
        $requeststatusrepo=$this->EM->getRepository(RefRequeststatus::class);
        $refRequestStatusOrderRepo = $this->EM->getRepository(RefRequestStatusOrder::class);
        foreach($datas as $data){
            $chkrequestStatusOrder  = $refRequestStatusOrderRepo->findOneBy(['initialStatusCode' => $data->getIdRequeststatus()->getStatusRequeststatus()]);
            $id=$data->getIdRequeststatus()->getId();
            $langvalue='all';
            $status=$this->getSingleStatus($langvalue,$id);
            $serializer = new Serializer(array(new DateTimeNormalizer()));
            $dateAsString = $serializer->normalize($data->getDateRequestRequeststatus());
            $datarequestreqstatus=new DataReqReqStatus();
            $datarequestreqstatus->setId($data->getId());
            $datarequestreqstatus->setDateRequestRequeststatus($dateAsString);
            $datarequestreqstatus->setLogin((string) $data->getLoginRequestRequeststatus());
            $datarequestreqstatus->setIdRequeststatus($status);
            $client=$clientservice->getClientDetail($clientrepo,$request_id);
            $datarequestreqstatus->setClient($client);
            $datarequestreqstatus->setRefRequestStatusOrder($chkrequestStatusOrder);
            array_push($arraydata,$datarequestreqstatus);
        }
        return $arraydata;
    }

    public function getSingleStatus(string $langvalue,int $id): ?RefRequeststatus{
        $langRepo=$this->EM->getRepository(RefLanguage::class);
        $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
        if(!$lang){
            if($langvalue != "all"){
              return ["msg"=>"lang not found"];
            }
          }
       $labelRepo=$this->EM->getRepository(RefLabel::class);        
       $civilityRepo=$this->EM->getRepository(RefRequeststatus::class);
       $particularcivility=$civilityRepo->findOneBy(['id'=>$id]);
       $languages=$langRepo->findAll();
       if($langvalue=="all"){
                   $refLabels=array();
                   foreach($languages as $language){
                       $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularcivility->getCodeRequeststatus()]);
                       if (!$label){
                           $refLabels[$language->getCodeLanguage()]="";
                           continue;
                           }
                        $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                      }
                      $particularcivility-> setRefLabels($refLabels);
               return $particularcivility;
           }
            $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularcivility->getCodeRequeststatus()]);
            if(!$label){
                 return ["msg"=>"label not found"];
            }
            $particularcivility->setRefLabel($label->getLabelLabel());
        return $particularcivility;
       
   }
   
   public function getLastDataRequestRequestStatus($request_id){
        $query = "SELECT * from data_request_requeststatus where id = (SELECT max(id) as maxid from data_request_requeststatus where `id_request_id`=$request_id)";
        $conn = $this->EM->getconnection();
        $result = $conn->executeQuery($query)->fetch();
        $res = ['request_id'=>$result['id_request_id'],'date'=>$result['date_request_requeststatus'],'login'=>$result['login_request_requeststatus'],'requeststatus'=>$result['id_requeststatus_id']];
        return $res;
       
    }
   
   public function insertDataTreatment($from,$to){
        $requeststatusrepo  = $this->EM->getRepository(RefRequeststatus::class);
        $datarequestrepo    = $this->EM->getRepository(DataRequest::class);
        $fromdate = date_create($from['date']);
        $todate = date_create($to['date']);
        $diff = date_diff($fromdate,$todate);
        // $diffcalc=$from->diff($to);
        $delay = (int)$diff->format("%a");
        $from_requeststatus = $requeststatusrepo->findOneBy(['id'=>$from['requeststatus']]);
        $to_requeststatus = $requeststatusrepo->findOneBy(['id'=>$to['requeststatus']]);
        $datarequestchk = $datarequestrepo->findOneBy(['id'=>$from['request_id']]);
        $datatreatment = new DataTreatment();
        $datatreatment->setRequest($datarequestchk);
        $datatreatment->setFromStatus($from_requeststatus);
        $datatreatment->setToStatus($to_requeststatus);
        $datatreatment->setDate(new \DateTime());
        $datatreatment->setDelay($delay);
        $datatreatment->setLogin($to['login']);
        $this->EM->persist($datatreatment);
        $this->EM->flush();
        return $datatreatment;
    }

}