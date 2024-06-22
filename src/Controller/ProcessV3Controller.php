<?php

namespace App\Controller;

use DateTime;
use Pikirasa\RSA;
use App\Entity\Cards;
use App\Utils\Eloqua;
use App\UtilsV3\Card;
use App\UtilsV3\Login;
use App\UtilsV3\Cardss;
use App\UtilsV3\Contac;
use App\UtilsV3\Option;
use App\UtilsV3\StepV1;
use App\Utils\ApiHelper;
use App\UtilsV3\Contact;
use App\UtilsV3\Product;
use App\UtilsV3\Activity;
use App\UtilsV3\LegStep1;
use App\UtilsV3\LegStep2;
use App\UtilsV3\LegStep3;
use App\Entity\DataClient;
use App\Entity\DataClientOffer;
use App\Entity\DataOffer;
use App\Utils\ApiResponse;
use App\Entity\DataContact;
use App\Entity\DataRequest;
use App\Entity\DataAttorney;
use App\Entity\DataTemplate;
use App\Entity\OptionsTabel;
use App\Entity\RefRequeststatus;
use App\UtilsV3\Description;
use App\Entity\DataClientSab;
use App\Entity\DataEloquaCdo;
use App\Entity\DataUserSpace;
use App\Service\EmailService;
use App\UtilsV3\PersonalData;
use App\Service\ClientService;
use App\Service\EloquaService;
use App\Service\MasterService;
use App\UtilsV3\ActivityStep1;
use App\UtilsV3\ActivityStep2;
use App\UtilsV3\ActivityStep3;
use App\Service\EmailServiceV1;
use App\Service\DocumentService;
use App\UtilsSer\ClientPersonal;
use Doctrine\DBAL\ParameterType;
use App\Entity\DataEloquaContact;
use App\Repository\BoxRepository;
use App\Repository\CardsRepository;
use App\Service\DataRequestService;
use App\Repository\RefEpaRepository;
use App\Service\TokenGeneratService;
use App\Entity\DataClientWhereclient;
use karpy47\PhpMqttClient\MQTTClient;
use App\Entity\DataCountryWhereclient;
use App\Entity\RefFid;
use App\Entity\DataFid;
use App\Repository\RefFieldRepository;
use App\Repository\RefLabelRepository;
use App\Entity\DataClientWheresupplier;
use App\Entity\DataTemplateVariablesV1;
use App\Entity\DataCountryWheresupplier;
use App\Entity\DataRequestRequeststatus;
use App\Entity\RefCountry;
use App\Repository\DataClientRepository;
use App\Repository\RefCountryRepository;
use App\Repository\RefProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DataContactRepository;
use App\Repository\DataRequestRepository;
use App\Repository\RefCardtypeRepository;
use App\Repository\RefCivilityRepository;
use App\Repository\RefLanguageRepository;
use App\Repository\RefPriorityRepository;
use App\Repository\RefVariableRepository;
use App\Service\DataReq_ReqstatusService;
use App\Repository\DataAttorneyRepository;
use App\Repository\DataTemplateRepository;
use App\Repository\OptionsTabelRepository;
use App\Repository\RefDebittypeRepository;
use App\Repository\RefLegalformRepository;
use App\Repository\RefWhoclientRepository;
use App\Repository\DataEloquaCdoRepository;
use App\Repository\DataUserspaceRepository;
// use phpseclib\Crypt\RSA;
use App\Entity\RefTypeclient;
use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use App\Repository\RefTypeclientRepository;
use App\Repository\RefCdpricecalcRepository;
use App\Repository\RefCompanytypeRepository;
use App\Repository\RefTypecontactRepository;
use App\Repository\RefWhereclientRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RefRequeststatusRepository;
use App\Repository\RefWheresupplierRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DataEloquaContactRepository;
use App\Repository\RefProductContentRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DataClientWhereclientRepository;
use App\Repository\DataCountryWhereclientRepository;
use App\Repository\DataClientWheresupplierRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use \Swift_SmtpTransport,\Swift_Mailer,\Swift_Message;
use App\Repository\DataRequestRequeststatusRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\Repository\DataCountryWheresupplierRepository; 
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ProcessV3Controller extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

//     /**
//      * @Route("/process/v3", name="process_v3")
//      */
//     public function index()
//     {
//         return $this->render('process_v3/index.html.twig', [
//             'controller_name' => 'ProcessV3Controller',
//         ]);
//     }
//      /**
//      * @Route("/api/v3/process/step1", name="step1",methods={"POST"})
//      */
//     public function step1(Request $request,DataUserspaceRepository $userspaceRepo,RefProductRepository $productrepo,RefCompanytypeRepository $companytyperepo,
//     RefCardtypeRepository $cardtyperepo,RefPriorityRepository $priorityrepo,RefDebittypeRepository $debittyperepo,RefCountryRepository $contryrepo,RefRequeststatusRepository $reqstatusrepo,
//     DataRequestRequeststatusRepository $reqreqstatusrepo)
//     {
//         $encoders = [ new JsonEncoder()];
//         $normalizers = [new ObjectNormalizer()];
//         $serializer = new Serializer($normalizers ,$encoders);
//         $content = $request->getContent();
//         $entityManager = $this->EM;
//         $step1 = $serializer->deserialize($content, StepV1::class, 'json');
       
       
       
//         $emailchk=null;
//         $productchk=null;
//         $chkcompanytype=null;
//         if($step1->getEmail()){
//             $emailchk= $userspaceRepo->findOneBy(['email_us'=>$step1->getEmail()]);
//             // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','email already exist');       
//         }
//         if($step1->getProduct()){
//             $productchk= $productrepo->findOneBy(['id'=>$step1->getProduct()]);
//             // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','product is invalid');  
//         }
//         if($step1->getCompanyType()){
//             $chkcompanytype=$companytyperepo->findOneBy(['id'=>$step1->getCompanyType()]);
//             // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','companytype is invalid');  
//         }
//         $reqstatus=$reqstatusrepo->findOneBy(['id'=>1]);
//         if(!$reqstatus){
//             return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','  invalid request status');  
//         }
//         $datarequest = new DataRequest();
//         $client=new DataClient();
//         $userspace=new DataUserSpace();
//         $reqreqstatus=new DataRequestRequeststatus();
//         $client->setCompanytype($chkcompanytype);
//         $datarequest->setDateupdRequest(new \DateTime());
//         $datarequest->setDateRequest(new \DateTime());
//         $datarequest->setClient($client);
//         $datarequest->setRequeststatus($reqstatus);
//         $reqreqstatus->setIdRequest($datarequest);
//         $reqreqstatus->setIdRequeststatus($reqstatus);
//         $reqreqstatus->setDateRequestRequeststatus(new \DateTime());
//         $userspace->setIdRequest($datarequest);
//         $userspace->setEmailUs($step1->getEmail());
//         $userspace->setActiveUs("Active");
//         $entityManager->persist($client);
//         $entityManager->persist($datarequest);
//         $entityManager->persist($userspace);
//         $entityManager->persist($reqreqstatus);
//         $entityManager->flush();


//         $optiontable=new OptionsTabel;
//         $optiontable->setProduct($productchk);
//         $optiontable->setCheque($step1->getCheque());
//         $optiontable->setTpc($step1->getTpc());
//         $optiontable->setCheque($step1->getCheque());
//         $optiontable->setClient($client);
//         if($step1->getCards() ){
//         foreach($step1->getCards() as $noncards )
//         {
//             $cards =$serializer->deserialize( json_encode($noncards,JSON_NUMERIC_CHECK ), Cardss::class, 'json');
//             // $utilContacts = new Contac();
//             $cardtypechk=null;
//             $debittypechk=null;
//             $prioritychk=null;
//             $contrychk=null;
          
            
           
           
//             if($cards->getCardtype()){
//                 $cardtypechk=$cardtyperepo->findOneBy(['id'=>$cards->getCardtype()]);
//                 // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
//             }
//             if($cards->getDebittype()){
//                 $debittypechk=$debittyperepo->findOneBy(['id'=>$cards->getDebittype()]);
//                 // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','debittype is invalid');  
//             }
//             if($cards->getPriority()){
//                 $prioritychk=$priorityrepo->findOneBy(['id'=>$cards->getPriority()]);
//                 // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','priority is invalid');  
//             }
//             if($cards->getCountry()){
//                 $contrychk=$contryrepo->findOneBy(['id'=>$cards->getCountry()]);
//                 // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','contry is invalid');  
//             }
            
//             $card=new Cards;
//             $card->setCardtype($cardtypechk);
//             $card->setDebittype($debittypechk);
//             $card->setPriority($prioritychk);
//             $card->setName($cards->getName());
//             $card->setSurname($cards->getSurname());
//             $card->setCountry($contrychk);
//             $card->setClient($client);
//             $entityManager->persist($optiontable);
//             $entityManager->persist($card);
//             $entityManager->flush();
//         }
//     }
//             return new ApiResponse(["client_id"=>$client->getId()],200,["Content-Type"=>"application/json"],'json','success ');    

    

// }

    /**
     * @Route("/api/resume_completion/{clientid}", name="resumeCompletion",methods={"PUT"})
     */
    public function resumeCompletion_(ClientService $clientser,Request $request,RefRequeststatusRepository $reqstatusrepo,DataRequestRequeststatusRepository $reqreqstatusrepo,$clientid,DataRequestRepository $clientrepo, DataReq_ReqstatusService $datareqreqserv, ClientService $clientservice, DataClientRepository $clientrepod)
    {
        if(!$clientser->checkClientId($clientid, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $entityManager = $this->EM;

        $chkclient=$clientrepo->findOneBy(['client'=>$clientid]);
        if(!$chkclient){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','  invalid client');  
        }

        $data =  $datareqreqserv->getStatusDetail($clientid,$clientrepod,$clientservice,$reqreqstatusrepo);
		if ($data[0]->getIdRequeststatus()->getId() >= 3) {
			return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');  
		}
        
        $reqstatus=$reqstatusrepo->findOneBy(['id'=>2]);
        if(!$reqstatus){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','  invalid request status');  
        }
        $reqreqstatus=new DataRequestRequeststatus();
        $reqreqstatus->setIdRequest($chkclient);
        $reqreqstatus->setIdRequeststatus($reqstatus);
        $reqreqstatus->setDateRequestRequeststatus(new \DateTime());
        $entityManager->persist($reqreqstatus);
        $entityManager->flush();

        $reqstatus=$reqstatusrepo->findOneBy(['id'=>3]);
        if(!$reqstatus){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','  invalid request status');  
        }
        $reqreqstatus=new DataRequestRequeststatus();
        $reqreqstatus->setIdRequest($chkclient);
        $reqreqstatus->setIdRequeststatus($reqstatus);
        $reqreqstatus->setDateRequestRequeststatus(new \DateTime());
        $entityManager->persist($reqreqstatus);
        $entityManager->flush();

        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');  

        
    }

    /**
     * @Route("/api/client/reqstatus/{clientid}", name="ClientReqStatus",methods={"GET"})
     */
    public function ClientReqStatus_(Request $request,DataRequestRequeststatusRepository $reqreqstatusrepo,$clientid,DataRequestRepository $reqrepo,DataClientRepository $clientrepo,DataAttorneyRepository $attoneyrepo,BoxRepository $boxrepo,RefFieldRepository $reffieldrepo,ClientService $clientser)
    {
        if(!$clientser->checkClientId($clientid, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $chkclient=$clientrepo->findOneBy(['id'=>$clientid]);
        if(!$chkclient){
           return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid clientid');  
        }
        $attorney=$attoneyrepo->findOneBy(['client'=>$clientid,'ismandatory_attorney'=>true]);
        $name=null;
        $surname=null; 
        $birthname = null;
        $civility=null;
        if($attorney){
            if($attorney->getCivilityAttorney()){
                $civility=$attorney->getCivilityAttorney()->getId();
             }
             if($attorney->getNameAttorney()){
                $name=$attorney->getNameAttorney();
             }
             if($attorney->getSurnameAttorney()){
                $surname=$attorney->getSurnameAttorney();
             }
             if($attorney->getBirthName()){
                $birthname=$attorney->getBirthName();
             }
          
         }
        $reqchk=$reqrepo->findOneBy(['client'=>$clientid]);
        if(!$reqchk){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid client nt in DataRequest');  
         }
         $datas=$reqreqstatusrepo->findBy(['id_request'=>$reqchk->getId()], ['id' => 'DESC']);
         if(!$datas){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','no data in reqstatus');  
         }
        
         
         $req=array();
         $clientdata=new ClientPersonal;
         $clientdata->setName($name);
         $clientdata->setSurName($surname);
         $clientdata->setBirthName($birthname);
         $clientdata->setCivility($civility);
         foreach($datas as $data){
            $serializer = new Serializer(array(new DateTimeNormalizer()));
            $dateAsString = $serializer->normalize($data->getDateRequestRequeststatus());
            $data->setDateRequest($dateAsString);
            array_push($req,$data);
         }
         $clientdata->setRequestStatus($req);
         $chkbox = $boxrepo->findOneBy(['client'=>$clientid]);
         if(!$chkbox){
            $boxid  = null;
         }else{
            $boxid  = $chkbox->getBoxId();
         }
         $reffielddata  = $reffieldrepo->findOneBy(['box_id'=>$boxid]);
         if($reffielddata){
            //return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid box_id',['timezone','DataRequest','idRequest','dataRequestRequeststatuses']);           
            $orderid =$reffielddata->getOrderId();
            $orderdata  = $reffieldrepo->findOneBy(['orderId'=>$orderid+1]);
            if ($orderdata != null) {
                $nextBox  = $orderdata->getBoxId();
                $clientdata->setBoxId($boxid);
                $clientdata->setNextBoxId($nextBox);
            }
        }
        return new ApiResponse($clientdata,200,["Content-Type"=>"application/json"],'json','success',['timezone','DataRequest','idRequest','dataRequestRequeststatuses']);  
         

    }
    //  /**
    //  * @Route("/api/token", name="token",methods={"POST"})
    //  */
    // public function token(TokenGeneratService $tokenservice){
    //     $data = $tokenservice->sessionInsert(10);
    //     return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',['timezone','DataRequest','idRequest','dataRequestRequeststatuses']);  

    // }
    /**
     * @Route("/api/v3/process/step1V1", name="step1v1",methods={"POST"})
     */
    public function step1v1(Request $request,DataUserspaceRepository $userspaceRepo,RefProductRepository $productrepo,RefCompanytypeRepository $companytyperepo,RefTypeclientRepository $typeclientrepo,
    RefCardtypeRepository $cardtyperepo,RefPriorityRepository $priorityrepo,RefDebittypeRepository $debittyperepo,RefCountryRepository $contryrepo,RefRequeststatusRepository $reqstatusrepo,
    EmailServiceV1 $emailserviceV1,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,
    DataRequestRequeststatusRepository $reqreqstatusrepo,ApiHelper $apihelper, DataReq_ReqstatusService $drrss,TokenGeneratService $tokenservice)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $step1 = $serializer->deserialize($content, StepV1::class, 'json');    
        $emailchk = null;
        $productchk = null;
        $chkcompanytype = null;
        $chktypeclient = null;
        $pub_key = file_get_contents('../pub.crt');
        $pri_key = file_get_contents('../pri.crt');
        $rsa     = new  RSA($pub_key, $pri_key);
        $data    = $step1->getEmail();
        $encrypted = $rsa->base64Encrypt($data);
        $baseurl = base64_encode($encrypted); 
        $q = "SELECT MAX(id) as id FROM data_client";
        $connection = $this->EM->getconnection();
        $client_id = $connection->executeQuery($q)->fetch();

        $q = "SELECT MAX(id) as id FROM data_request";
        $connection = $this->EM->getconnection();
        $request_id = $connection->executeQuery($q)->fetch();
        $client_id = $client_id['id']+1;
        $request_id = $request_id['id']+1;
        // return $result;
        // return new ApiResponse($request_id['id'],404,["Content-Type"=>"application/json"],'json','email already exist');       
        if($client_id != $request_id){
            if($client_id>$request_id){
                $request_id = $client_id;
            }
            else{
                $client_id = $request_id;
            }
        }
        // return new ApiResponse([$client_id,$request_id],404,["Content-Type"=>"application/json"],'json','email already exist');       
        
        if ($step1->getEmail()){
            $emailchk = $userspaceRepo->findOneBy(['email_us'=>$step1->getEmail(),'active_us'=>'Active'],['id'=>'DESC']);
            if ($emailchk){
                if($emailchk->getIdRequest()->getRequeststatus()==null){
                    return new ApiResponse($baseurl,404,["Content-Type"=>"application/json"],'json','email already exist');       
                }
                $clientstatusid = $emailchk->getIdRequest()->getRequeststatus()->getId();
                $count = 0;
                $chkstatuses = ["190", "191", "170", "180", "171", "172", "999"];
                foreach($chkstatuses as $chkstatus){
                    $requeststatus = $reqstatusrepo->findOneBy(['status_requeststatus'=>$chkstatus]);
                    if($requeststatus){
                        if($requeststatus->getId()==$clientstatusid){  
                            $count = $count+1;
                            break;
                         }
                    }
                }
                if($count==0){
                    return new ApiResponse($baseurl,404,["Content-Type"=>"application/json"],'json','email already exist');       
                }
            }
        }
        if ($step1->getProduct()){
            $productchk = $productrepo->findOneBy(['id'=>$step1->getProduct()]);
        }
        if ($step1->getCompanyType()){
            $chkcompanytype = $companytyperepo->findOneBy(['id'=>$step1->getCompanyType()]);
        }
        if ($step1->getTypeClient()){
            $chktypeclient = $typeclientrepo->findOneBy(['id'=>$step1->getTypeClient()]);
        }
        $reqstatus = $reqstatusrepo->findOneBy(['id'=>1]);
        if (!$reqstatus){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','  invalid request status');  
        }
        $datarequest    = new DataRequest();
        $client         = new DataClient();
        $userspace      = new DataUserSpace();
        $reqreqstatus   = new DataRequestRequeststatus();
      
        $client->setId($client_id);
        $client->setCompanytype($chkcompanytype);
        $client->setTypeClient($chktypeclient);
        $datarequest->setId($request_id);
        $datarequest->setDateupdRequest(new \DateTime());
        $datarequest->setDateRequest(new \DateTime());
        $datarequest->setClient($client);

        $reqreqstatus->setIdRequest($datarequest);
        $reqreqstatus->setIdRequeststatus($reqstatus);
        $reqreqstatus->setDateRequestRequeststatus(new \DateTime());

        $userspace->setIdRequest($datarequest);
        $userspace->setEmailUs($step1->getEmail());
        $userspace->setActiveUs("Active");

        $entityManager->persist($client);
        $entityManager->persist($datarequest);

        $datarequest->setRequestRef($datarequest->getId()+50000);

        $reqreqstatus2  = $reqreqstatus;
		$reqstatus2		= $reqstatus;
		
        $entityManager->persist($userspace);
        $entityManager->persist($reqreqstatus);        
        $entityManager->flush();

        $msg = $drrss->checkEmailStatus($reqreqstatus2, $reqstatus2, true);

        $entityManager->flush();

        $optiontable = new OptionsTabel;
        $optiontable->setProduct($productchk);
        $optiontable->setCheque($step1->getCheque());
        $optiontable->setTpc($step1->getTpc());
        $optiontable->setCash($step1->getCash());
        $optiontable->setClient($client);
        $entityManager->persist($optiontable);
        $entityManager->flush();
        if ($step1->getCards() )
        {
            foreach ($step1->getCards() as $noncards )
            {
                $cards = $serializer->deserialize( json_encode($noncards), Cardss::class, 'json');
                $cardtypechk = null;
                $debittypechk = null;
                $prioritychk = null;
                $contrychk = null;
                $telephone = null;
                $telecode = null;
                if ($cards->getCardtype()){
                    $cardtypechk = $cardtyperepo->findOneBy(['id'=>$cards->getCardtype()]);
                }
                if ($cards->getDebittype()){
                    $debittypechk = $debittyperepo->findOneBy(['id'=>$cards->getDebittype()]);
                }
                if ($cards->getPriority()){
                    $prioritychk = $priorityrepo->findOneBy(['id'=>$cards->getPriority()]);
                }
                if ($cards->getCountry()){
                    $contrychk = $contryrepo->findOneBy(['id'=>$cards->getCountry()]);
                }
                if ($cards->getTelephone()){
                    $telephone = $cards->getTelephone();
                }
                if ($cards->getTelecode()){
                    $telecode = $cards->getTelecode();
                }
                $card = new Cards;
                $card->setCardtype($cardtypechk);
                $card->setDebittype($debittypechk);
                $card->setPriority($prioritychk);
                $card->setName($cards->getName());
                $card->setSurname($cards->getSurname());
                $card->setCountry($contrychk);
                $card->setClient($client);
                $card->setTelephone($telephone);
                $card->setTelecode($telecode);
                $card->setPrice($cards->getPrice());
                $entityManager->persist($card);
                $entityManager->flush();
            }
        }
        if($step1->getOfferId()){
            $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
            $dataoffer = $DataOfferRepo->findOneBy(["id"=>$step1->getOfferId()]);
            $dataclientoffer = new DataClientOffer;
            $dataclientoffer->setOffer($dataoffer);
            $dataclientoffer->setClient($client);
            $entityManager->persist($dataclientoffer);
            $entityManager->flush();

        }
       
        // $text = $datatemprepo->findOneBy(['action_id'=>2,'status'=>'Active']);
        $data = array();
        $lang = 'fr';
        //$base_url = $this->params->get('app.ser1')."/otp?euid=$baseurl";
        $data = $emailserviceV1->sendEmail($varrepo,$datatemprepo,$client->getId(),1,$step1->getEmail(), true);
        $eloquaContact = new DataEloquaContact();
        $eloquaContact->setEmailAddress($step1->getEmail());
        $eloquaContact->setDate(new \DateTime());
        $eloquaContact->setIsSend(false);
        $eloquaContact->setRequest($datarequest);
        $entityManager->persist($eloquaContact);
        $entityManager->flush();
        $eloquaCdo = new DataEloquaCdo();
        $eloquaCdo->setEmailAddress($step1->getEmail());
        $eloquaCdo->setDate((string)time());
        if($chkcompanytype){
            $eloquaCdo->setOffreSituation($chkcompanytype->getDescCompanytype());
        }
        if($productchk){
            $eloquaCdo->setOffreFormule($productchk->getName());
        }
        $eloquaCdo->setEtape("Offre");
        $eloquaCdo->setIsSend(false);
        $eloquaCdo->setRequest($datarequest);
        $eloquaCdo->setLoginUrl($baseurl);
        $eloquaCdo->setUrlsource($step1->getUrlsource());
        $entityManager->persist($eloquaCdo);
        $entityManager->flush();
        $token = $tokenservice->sessionInsert($datarequest->getId());

        $pub_key    = file_get_contents('../pub.crt');
        $pri_key    = file_get_contents('../pri.crt');
        $rsa        = new RSA($pub_key, $pri_key);
        $encrypted  = $rsa->base64Encrypt($step1->getEmail());
        
        return new ApiResponse(["client_id"=>$client->getId(),"token"=>$token, "otpid"=> base64_encode($encrypted)],200,["Content-Type"=>"application/json"],'json','success');    
        
    }

    public function eloquaContact($apihelper,$emailid){
        $route="api/REST/1.0/data/contact";
        $data = [  
           "emailAddress" => $emailid,
       ];
       /*$response=$apihelper->eloquaPost($route,$data);
       $bodycontent = $response-> getBody();
       $encoders = [ new JsonEncoder()];
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers ,$encoders);
       $entityManager = $this->EM;
       $data = $serializer->deserialize($bodycontent, Eloqua::class, 'json');
       // $arraybodycontent=json_decode(json_encode($bodycontent), true);*/
       return $data;    
    }

    public function eloquaCdo($apihelper,$cdo){
        $route="api/REST/2.0/data/customObject/14/instance";
        $date = $cdo->getDate();
        $emailaddress = $cdo->getEmailAddress();
        $offersituation = $cdo->getOffreSituation();
        $offerformula = $cdo->getOffreFormule();
        $etape = $cdo->getEtape();
        $civility = $cdo->getCivility();
        $nom = $cdo->getNom();
        $prnom = $cdo->getPrenom();
        $rationsociale = $cdo->getRaisonSociale();
        $data =[
          "type"=>"CustomObjectData",
          "fieldValues"=> [
            [
              "id"=> "219",
              "value"=> $date
            ],
            [
              "id"=> "228",
              "value"=> $emailaddress
            ],
            [
                "id"=>"226",
                "value"=>$offersituation
            ],
            [
                "id"=> "227",
                "value"=>$offerformula
            ],
            [
                "id"=>"221",
                "value"=>$etape
            ],
            [
                "id"=>"224",
                "value"=>$civility
            ],
            [
                "id"=>"222",
                "value"=>$nom
            ],
            [
                "id"=>"223",
                "value"=>$prnom
            ],
            [
                "id"=>"225",
                "value"=>$rationsociale
            ]
          ]
            ];
       /*$response=$apihelper->eloquaPost($route,$data);
       $bodycontent = $response-> getBody();
       $encoders = [ new JsonEncoder()];
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers ,$encoders);
       $entityManager = $this->EM;
       $data = $serializer->deserialize($bodycontent, Eloqua::class, 'json');
       // $arraybodycontent=json_decode(json_encode($bodycontent), true);*/
       return $data;    
    }

    /**
     * @Route("/api/Eloqua/contact/autocall", name="EloquaContactAutoCall",methods={"POST"})
     */
    public function EloquaContactCall(DataRequestRepository $datareqrepo,ApiHelper $apihelper,DataEloquaContactRepository $contactrepo,EloquaService $eloquaservice)
    {
        $data = $eloquaservice->EloquaContactAutoCall();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success ');    
    }

     /**
     * @Route("/api/Eloqua/cdo/autocall", name="EloquaCdoAutoCall",methods={"POST"})
     */
    public function EloquaCdoCall(DataRequestRepository $datareqrepo,ApiHelper $apihelper,DataEloquaCdoRepository $cdorepo,EloquaService $eloquaservice)
    {
        $data = $eloquaservice->EloquaCdoAutoCall();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
    }

    /**
     * @Route("/api/dataclientsab/insert", name="DataClientSab",methods={"POST"})
     */
    public function DataClientSab(Request $request,DataRequestRepository $requestrepo, DocumentService $ds, EmailServiceV1 $es, DataRequestRequestStatusRepository $datareqstatusrep, DataReq_ReqstatusService $drrss, RefRequeststatusRepository $refreq)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $serializer->deserialize($content, DataClientSab::class, 'json');
        $request_id = $data->getRequestId();
        $request = $requestrepo->findOneBy(['id'=>$request_id]);
        if(!$request){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid request id');    
        }
        $clientsab = new DataClientSab;
        $clientsab->setRequest($request);
        $clientsab->setNumero($data->getNumero());
        $clientsab->setIban($data->getIban());
        $clientsab->setBic($data->getBic());
        $clientsab->setIsClosed(false);
        $entityManager->persist($clientsab);
        $entityManager->flush();

        // Génération du RIB
        $ds->makeRib($request_id, $es, $entityManager);
        
        $listst = $datareqstatusrep->findBy(['id_request' => $request_id], ['id' => 'DESC']);
        $lastst = $listst[1];
        $newst  = $refreq->findOneBy(['id' => $listst[0]->getIdRequeststatus()]);
                
        $msg = $drrss->checkEmailStatus($lastst, $newst, false);
        
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
    }

    /**
     * @Route("/api/Eloqua/contact/insert", name="EloquaContactInsert",methods={"POST"})
     */
    public function EloquaContactInsert(Request $request,DataRequestRepository $datareqrepo,ApiHelper $apihelper, ClientService $clientser)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $serializer->deserialize($content, DataEloquaContact::class, 'json');
        $id = $data->getRequestId();

        if(!$clientser->checkClientId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $request = $datareqrepo->findOneBy(['id'=>$id]);
        if(!$request){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid request id');    
        }
        try{
            $eloquadata = $this->eloquaContact($apihelper,$data->getEmailAddress());
            if(!$eloquadata){
                throw new HttpException(400, "somthing wrong");    
              }
        }
        catch(\Exception $e){
            return new ApiResponse(["MSG"=>$e->getMessage()],400,["Content-Type"=>"application/json"],'json','Incorrect password or id');   
        }
        $eloquaContact = new DataEloquaContact();
        $eloquaContact->setEmailAddress($data->getEmailAddress());
        $eloquaContact->setDate(new \DateTime());
        $eloquaContact->setEloquaId($eloquadata->getId());
        $eloquaContact->setIsSend(true);
        $eloquaContact->setRequest($request);
        $entityManager->persist($eloquaContact);
        $entityManager->flush();
        return new ApiResponse(["eloqua_id"=>$eloquadata->getId()],200,["Content-Type"=>"application/json"],'json','success ');    

        
    }
    /**
     * @Route("/api/Eloqua/autocall", name="autoCallEloqua",methods={"GET"})
     */
    public function autoCallEloqua(EloquaService $eloquaservice)
    {
       $eloquaservice->EloquaContactAutoCall();
       $eloquaservice->EloquaCdoAutoCall();
       return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success ');    
    }
    /**
     * @Route("/api/Eloqua/cdo/insert", name="EloquaCdoInsert",methods={"POST"})
     */
    public function EloquaCdoInsert(Request $request,DataRequestRepository $datareqrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $serializer->deserialize($content, DataEloquaCdo::class, 'json');
        $id = $data->getRequestId();
        $request = $datareqrepo->findOneBy(['id'=>$id]);
        if(!$request){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid request id');    
        }
        $eloquaCdo = new DataEloquaCdo();
        $eloquaCdo->setEmailAddress($data->getEmailAddress());
        $eloquaCdo->setDate(time());
        $eloquaCdo->setEloquaId($data->getEloquaId());
        $eloquaCdo->setIsSend($data->getIsSend());
        $eloquaCdo->setRequest($request);
        $eloquaCdo->setOffreSituation($data->getOffreSituation());
        $eloquaCdo->setOffreFormule($data->getOffreFormule());
        $eloquaCdo->setEtape($data->getEtape());
        $eloquaCdo->setCivility($data->getCivility());
        $eloquaCdo->setNom($data->getNom());
        $eloquaCdo->setPrenom($data->getPrenom());
        $eloquaCdo->setRaisonSociale($data->getRaisonSociale());
        $eloquaCdo->setLoginUrl($data->getLoginUrl());
        $entityManager->persist($eloquaCdo);
        $entityManager->flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success ');    
    }


    /**
     * @Route("/api/v3/process/step1V1/trial", name="step1v1Trial",methods={"POST"})
     */
    public function step1v1Trial(Request $request,DataUserspaceRepository $userspaceRepo,RefProductRepository $productrepo,RefCompanytypeRepository $companytyperepo,RefTypeclientRepository $typeclientrepo,
    RefCardtypeRepository $cardtyperepo,RefPriorityRepository $priorityrepo,RefDebittypeRepository $debittyperepo,RefCountryRepository $contryrepo,RefRequeststatusRepository $reqstatusrepo,
    EmailServiceV1 $emailserviceV1,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,\Swift_Mailer $mailer, \Twig_Environment $templating,\Swift_Transport $transport,
    DataRequestRequeststatusRepository $reqreqstatusrepo)
    {
       
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $step1 = $serializer->deserialize($content, StepV1::class, 'json');
       
    // return new ApiResponse($step1->getCards(),404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
       
       
        $emailchk=null;
        $productchk=null;
        $chkcompanytype=null;
        $chktypeclient=null;
        if($step1->getEmail()){
            $emailchk= $userspaceRepo->findOneBy(['email_us'=>$step1->getEmail()]);
            if($emailchk){
             return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','email already exist');       
            }
        }
        if($step1->getProduct()){
            $productchk= $productrepo->findOneBy(['id'=>$step1->getProduct()]);
             //return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','product is invalid');  
        }
        if($step1->getCompanyType()){
            $chkcompanytype=$companytyperepo->findOneBy(['id'=>$step1->getCompanyType()]);
             //return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','companytype is invalid');  
        }
        if($step1->getTypeClient()){
            $chktypeclient=$typeclientrepo->findOneBy(['id'=>$step1->getTypeClient()]);
             //return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','companytype is invalid');  
        }
        $reqstatus=$reqstatusrepo->findOneBy(['id'=>1]);
        if(!$reqstatus){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','  invalid request status');  
        }
        $datarequest = new DataRequest();
        $client=new DataClient();
        $userspace=new DataUserSpace();
        $reqreqstatus=new DataRequestRequeststatus();
        $client->setCompanytype($chkcompanytype);
        $client->setTypeClient($chktypeclient);
        $datarequest->setDateupdRequest(new \DateTime());
        $datarequest->setDateRequest(new \DateTime());
        $datarequest->setClient($client);
        $reqreqstatus->setIdRequest($datarequest);
        $reqreqstatus->setIdRequeststatus($reqstatus);
        $reqreqstatus->setDateRequestRequeststatus(new \DateTime());
        $userspace->setIdRequest($datarequest);
        $userspace->setEmailUs($step1->getEmail());
        $userspace->setActiveUs("Active");
        $entityManager->persist($client);
        $entityManager->persist($datarequest);
        $entityManager->persist($userspace);
        $entityManager->persist($reqreqstatus);
        $entityManager->flush();


        $optiontable=new OptionsTabel;
        $optiontable->setProduct($productchk);
        $optiontable->setCheque($step1->getCheque());
        $optiontable->setTpc($step1->getTpc());
        $optiontable->setCash($step1->getCash());
        $optiontable->setClient($client);
        $entityManager->persist($optiontable);
        $entityManager->flush();
        if($step1->getCards() )
        {
        foreach($step1->getCards() as $noncards )
        {
            $cards =$serializer->deserialize( json_encode($noncards), Cardss::class, 'json');
            // $utilContacts = new Contac();
            $cardtypechk=null;
            $debittypechk=null;
            $prioritychk=null;
            $contrychk=null;
            $telephone=null;
            $telecode=null;
                           
            if($cards->getCardtype()){
                $cardtypechk=$cardtyperepo->findOneBy(['id'=>$cards->getCardtype()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
            }
            if($cards->getDebittype()){
                $debittypechk=$debittyperepo->findOneBy(['id'=>$cards->getDebittype()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','debittype is invalid');  
            }
            if($cards->getPriority()){
                $prioritychk=$priorityrepo->findOneBy(['id'=>$cards->getPriority()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','priority is invalid');  
            }
            if($cards->getCountry()){
                $contrychk=$contryrepo->findOneBy(['id'=>$cards->getCountry()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','contry is invalid');  
            }
            if($cards->getTelephone()){
                $telephone=$cards->getTelephone();
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
            }
            if($cards->getTelecode()){
                $telecode=$cards->getTelecode();
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
            }
            $card=new Cards;
            $card->setCardtype($cardtypechk);
            $card->setDebittype($debittypechk);
            $card->setPriority($prioritychk);
            $card->setName($cards->getName());
            $card->setSurname($cards->getSurname());
            $card->setCountry($contrychk);
            $card->setClient($client);
            $card->setTelephone($telephone);
            $card->setTelecode($telecode);
            $card->setPrice($cards->getPrice());
            // $entityManager->persist($optiontable);
            $entityManager->persist($card);
            $entityManager->flush();
        }
    }
        $text = $datatemprepo->findOneBy(['action_id'=>2,'status'=>'Active']);
        $data = array();
        $lang = 'fr';
        $emailserviceV1->sendEmail($varrepo,$datatemprepo,$client->getId(),1,$step1->getEmail(), true);
        return new ApiResponse(["client_id"=>$client->getId()],200,["Content-Type"=>"application/json"],'json','success ');    
 
    }





    /**
     * @Route("/api/v3/process/step1V1/update/{client_id}", name="step1v1Update",methods={"POST"})
     */
    public function step1v1Update(Request $request,DataUserspaceRepository $userspaceRepo,RefProductRepository $productrepo,RefCompanytypeRepository $companytyperepo,RefTypeclientRepository $typeclientrepo,
    RefCardtypeRepository $cardtyperepo,RefPriorityRepository $priorityrepo,RefDebittypeRepository $debittyperepo,RefCountryRepository $contryrepo,RefRequeststatusRepository $reqstatusrepo,
    EmailServiceV1 $emailserviceV1,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,
    DataRequestRequeststatusRepository $reqreqstatusrepo,$client_id,ClientService $clientser)
    {
      
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $step1 = $serializer->deserialize($content, StepV1::class, 'json');
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $productchk=null;
        $chkcompanytype=null;
        $chktypeclient=null;
       
        if($step1->getProduct()){
            $productchk= $productrepo->findOneBy(['id'=>$step1->getProduct()]);
             //return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','product is invalid');  
        }
        else{
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','product is empty');
        }
        if($step1->getCompanyType()){
            $chkcompanytype=$companytyperepo->findOneBy(['id'=>$step1->getCompanyType()]);
             //return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','companytype is invalid');  
        }
        else{
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','companytype is empty');
        }
        if($step1->getTypeClient()){
            $chktypeclient=$typeclientrepo->findOneBy(['id'=>$step1->getTypeClient()]);
             //return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','companytype is invalid');  
        }
        else{
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','typeclient is empty');
        }
        $clientrepo=$this->EM->getRepository(DataClient::class);

        $client=$clientrepo->findOneBy(['id'=>$client_id]);
       
        // $client->setCompanytype($chkcompanytype);
        // $client->setTypeClient($chktypeclient);
        $entityManager->persist($client);
        $entityManager->flush();

        $optiontablerepo=$this->EM->getRepository(OptionsTabel::class);
        $optiontable=$optiontablerepo->findOneBy(['client'=>$client_id]);
        $optiontable->setProduct($productchk);
        $optiontable->setCheque($step1->getCheque());
        $optiontable->setTpc($step1->getTpc());
        $optiontable->setCheque($step1->getCheque());
        $optiontable->setCash($step1->getCash());
        $optiontable->setClient($client);
        if(count($step1->getCards())==0){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','card is empty');
        }
        if($step1->getCards() )
        {
            $cardrepo=$this->EM->getRepository(Cards::class);
            $deletecards=$cardrepo->findBy(['client'=>$client_id]);

            foreach($deletecards as $data){
                $entityManager  ->  remove($data);
                $entityManager  ->  flush();
                }
               
        foreach($step1->getCards() as $noncards )
        {
            $cards =$serializer->deserialize( json_encode($noncards), Cardss::class, 'json');
            // $utilContacts = new Contac();
            $cardtypechk  = null;
            $debittypechk = null;
            $prioritychk  = null;
            $contrychk    = null;
            $telephone    = null;
            $telecode     = null;
        
            // return new ApiResponse([$cards->getTelephone()],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');     
           
            if($cards->getCardtype()){
                $cardtypechk=$cardtyperepo->findOneBy(['id'=>$cards->getCardtype()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
            }
            if($cards->getDebittype()){
                $debittypechk=$debittyperepo->findOneBy(['id'=>$cards->getDebittype()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','debittype is invalid');  
            }
            if($cards->getPriority()){
                $prioritychk=$priorityrepo->findOneBy(['id'=>$cards->getPriority()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','priority is invalid');  
            }
            if($cards->getCountry()){
                $contrychk=$contryrepo->findOneBy(['id'=>$cards->getCountry()]);
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','contry is invalid');  
            }
            if($cards->getTelephone()){
                $telephone=$cards->getTelephone();
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
            }
            if($cards->getTelecode()){
                $telecode=$cards->getTelecode();
                // return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','cardtype is invalid');       
            }
            $card=new Cards;
            $card->setCardtype($cardtypechk);
            $card->setDebittype($debittypechk);
            $card->setPriority($prioritychk);
            $card->setName($cards->getName());
            $card->setSurname($cards->getSurname());
            $card->setCountry($contrychk);
            $card->setClient($client);
            $card->setPrice($cards->getPrice());
            $card->setTelephone($cards->getTelephone());
            $card->setTelecode($telecode);
            // $entityManager->persist($optiontable);
            $entityManager->persist($card);
            $entityManager->flush();
        }
      
        $entityManager->persist($optiontable);
        $entityManager->flush();

    }
    
    return new ApiResponse(["client_id"=>$client->getId()],200,["Content-Type"=>"application/json"],'json','successfullyupdated');    

    }
    
  
//      /**
//      * @Route("/api/v3/process/{email}", name="email",methods={"GET"})
//      */
//     public function emailcheck(DataUserspaceRepository $userspaceRepo,$email){
//         $encoders = [new JsonEncoder()];
//         $normalizer = new ObjectNormalizer();
//         
//         $entityManager = $this->EM;
//         $normalizer->setCircularReferenceHandler(function ($object) {
//         return $object->getId();
//         });
//         $normalizers = array($normalizer);
//         $serializer = new Serializer($normalizers ,$encoders);
//         $emailchk=$userspaceRepo->findOneBy(['email_us'=>$email]);
//         if(!$emailchk){
//             return new ApiResponse([$emailchk],401,["Content-Type"=>"application/json"],'json','invalid email');    
//         }
//         return new ApiResponse([$emailchk],200,["Content-Type"=>"application/json"],'json','valid email ');    
// }


//     /**
//      * @Route("/api/v3/process/login", name="login",methods={"POST"})
//      */
//     public function login(DataUserspaceRepository $userRepo,\Swift_Mailer $mailer,Request $request)
//     {
//         $encoders = [ new JsonEncoder()];
//         $normalizers = [new ObjectNormalizer()];
//         $serializer = new Serializer($normalizers ,$encoders);
//         $content = $request->getContent();
//         $entityManager = $this->EM;
//         $login = $serializer->deserialize($content, Login::class, 'json');
//         $chkemail= $userRepo->findOneBy(['email_us'=>$login->getEmail()]);
//         $userid= $chkemail->getId();
//         if(!$chkemail){
//              return new ApiResponse($chkemail,400,["Content-Type"=>"application/json"],'json',"your email id is invalid!!!!");
//         }
//         // return new ApiResponse($userid,400,["Content-Type"=>"application/json"],'json',"your email id is invalid!!!!");
//         $digits = 6;
//         $otp= rand(pow(10, $digits-1), pow(10, $digits)-1);
//         // echo $otp;
//         $cache = new FilesystemAdapter();
//         // create a new item by trying to get it from the cache
//         $productsCount = $cache->getItem('LOGIN_'.$userid);
//         $productsCount->expiresAfter(100);
//         // assign a value to the item and save it
       
//         $productsCount->set($otp);
//         $cache->save($productsCount);
//         $message = (new \Swift_Message('Fidicual Login - OTP'))
//         ->setFrom('yourideas.info@gmail.com')
//         ->setTo($login->getEmail())
//         ->setBody(
//             $this->renderView(
//                 'otp/loginotp.html.twig',
//                 ['otp' => $otp,'email_us'=>$login->getEmail()]
//             ),
//             'text/html'
//         );
//         $mailer->send($message);
//         return new ApiResponse($login->getEmail(),200,["Content-Type"=>"application/json"],'json',"msg send!!!!");
//     }
//     /**
//      * @Route("/api/v3/process/loginverification", name="validateotp",methods={"POST"})
//      */
//     public function loginverification(DataUserspaceRepository $userRepo ,Request $request)
//     {
//         $encoders = [ new JsonEncoder()];
//         $normalizers = [new ObjectNormalizer()];
//         $serializer = new Serializer($normalizers ,$encoders);
//         $content = $request->getContent();
//         $entityManager = $this->EM;
//         $login = $serializer->deserialize($content, Login::class, 'json');
//         $chkemail= $userRepo->findOneBy(['email_us'=>$login->getEmail()]);
//         $userotp= $login->getOtp();
//         $userid= $chkemail->getId();
//         if(!$chkemail){
//              return new ApiResponse($chkemail,400,["Content-Type"=>"application/json"],'json',"your email id is invalid!!!!");
//         }
//         $userid= $chkemail->getId();
//         $cache = new FilesystemAdapter();
//         // create a new item by trying to get it from the cache
//         $productsCount = $cache->getItem('LOGIN_'.$userid);
//         $otp=$productsCount->get();
//          if($otp==""){
//             return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"OTP EXPIRED!!!!");
//         }
//         if($userotp!=$otp){
//             return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"your OTP is invalid!!!!");
//         }
       
//         if($userotp==$otp){
//             return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success!!!!");
//         }
       
//     }

//     /**
//      * @Route("/api/v3/alreadyregistered/individual/legalrepresentative/step1", name="sess1",methods={"POST"})
//      */
//     public function sess1(Request $request,RefCivilityRepository $civilityrepo,RefTypecontactRepository $typecontrepo,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,DataContactRepository $datacontactrepo)
//     {
//         $encoders       = [new JsonEncoder()];
//         $normalizers    = [new ObjectNormalizer()];
//         $serializer     = new Serializer($normalizers ,$encoders);
//         $content        = $request->getContent();
//         $entityManager  = $this->EM;
//         $step1          = $serializer->deserialize($content, LegStep1::class, 'json');
//         $clientchk      = $clientrepo->findOneBy(['id'=>$step1->getClient()]);
//         $chkcivility    = $civilityrepo->findOneBy(['id'=>$step1->getCivility()]);
//         // return new ApiResponse($step1,200,["Content-Type"=>"application/json"],'json',"invalid client!!!!");
//         if (!$clientchk) {
//             return new ApiResponse([],404,["Content-Type"=>"application/json"],'json',"invalid client!!!!");
//         }

//         if (!$chkcivility) {
//             return new ApiResponse([],404,["Content-Type"=>"application/json"],'json',"invalid civility!!!!");
//         }

//         $attony = new DataAttorney;
//         $attony->setCivilityAttorney($chkcivility);
//         $attony->setClient($clientchk);
//         $attony->setNameAttorney($step1->getName());
//         $attony->setSurnameAttorney($step1->getSurname());
//         $entityManager->persist($attony);

//         foreach($step1->getContacts() as $noncontact )
//         {
//             $contact = $step1 = $serializer->deserialize( json_encode($noncontact,JSON_NUMERIC_CHECK ), Contac::class, 'json');
//             $utilContacts = new Contac();
//             $tc = $typecontrepo->find($contact->getTypeContact());
//             if(!$tc){
//                 return new ApiResponse( $tc,400,["Content-Type"=>"application/json"],'json',"Invalid type contact");
//             }
//             $dataContact = $datacontactrepo->findOneBy(["client"=>$clientchk,"type_contact"=>$tc]);
//             $dataContact = new DataContact();
//             $dataContact->setClient($clientchk);
//             $dataContact->setTypeContact($tc);
//             $dataContact->setValueContact($contact->getValueContact());
//             $entityManager->persist($dataContact);
//         }
//         $entityManager->flush();

//         return new ApiResponse(["client_id:"=>$clientchk->getId(),"attorny_id:"=>$attony->getId()],200,["Content-Type"=>"application/json"],'json',"successfully added");
//     }
    
//     /**
//      * @Route("/api/v3/alreadyregistered/individual/legalrepresentative/step2", name="sess2",methods={"POST"})
//      */
//     public function sess2(Request $request,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefCountryRepository $contryrepo)
//     {
//         $encoders       = [new JsonEncoder()];
//         $normalizers    = [new ObjectNormalizer()];
//         $serializer     = new Serializer($normalizers ,$encoders);
//         $content        = $request->getContent();
//         $entityManager  = $this->EM;
//         $step2          = $serializer->deserialize($content, LegStep2::class, 'json');
//         $chkclient      = $clientrepo->findOneBy(['id'=>$step2->getClient()]);       
//         $attony         = $attonyrepo->findOneBy(['client'=>$step2->getClient()]);
//         $contry         = $contryrepo->findOneBy(['id'=>$step2->getResContry()]);

//         if (!$chkclient) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
//         }
//         if (!$attony) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid attony_id");
//         }
//         if (!$contry) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid contry");
//         }
        
//         $attony->setAddressAttorney($step2->getAddress());
//         $attony->setZipcodeAttorney($step2->getZipcode());
//         $attony->setCityAttorney($step2->getCity());
//         $attony->setResidentcountryAttorney($contry);
//         $entityManager->persist($attony);
//         $entityManager->flush();

//         return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");
//     }

//     /**
//      * @Route("/api/v3/alreadyregistered/individual/legalrepresentative/step3", name="sess3",methods={"POST"})
//      */
//     public function sess3(Request $request,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefCountryRepository $contryrepo)
//     {
//         $encoders       = [new JsonEncoder()];
//         $normalizers    = [new ObjectNormalizer()];
//         $serializer     = new Serializer($normalizers ,$encoders);
//         $content        = $request->getContent();
//         $entityManager  = $this->EM;
//         $step3          = $serializer->deserialize($content, LegStep3::class, 'json');
//         $chkclient      = $clientrepo->findOneBy(['id'=>$step3->getClient()]);
//         $attony         = $attonyrepo->findOneBy(['client'=>$step3->getClient()]);
//         $ficacontry     = $contryrepo->findOneBy(['id'=>$step3->getFiscalcontry()]);
//         $nationality    = $contryrepo->findOneBy(['id'=>$step3->getNationality()]);
//         $contrybirth    = $contryrepo->findOneBy(['id'=>$step3->getContrybirth()]);

//         if (!$chkclient) {
//             return new ApiResponse([$step3->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
//         }
//         if (!$attony) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid attony_id");
//         }
//         if (!$ficacontry) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid fiscalcontry");
//         }
//         if( !$nationality) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid nationality");
//         }
//         if (!$contrybirth) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid contrybirth");
//         }

//         $attony->setDatebirthAttorney(new \DateTime(($step3->getDob())));
//         $attony->setPlacebirthAttorney($step3->getPlacebirth());
//         $attony->setCountrybirthAttorney($contrybirth);
//         $attony->setNationalityAttorney($nationality);
//         $attony->setFiscalcountryAttorney($ficacontry);
//         $attony->setAmericanAttorney($step3->getAmerican());
//         $entityManager->persist($attony);
//         $entityManager->flush();

//         return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");
//     }

//     /**
//      * @Route("/api/v3/alreadyregistered/individual/activity/step1", name="session1",methods={"POST"})
//      */
//     public function session1(Request $request,DataClientRepository $clientrepo,RefCountryRepository $contryrepo,RefLegalformRepository $legalrepo,RefEpaRepository $eparepo)
//     {
//         $encoders       = [new JsonEncoder()];
//         $normalizers    = [new ObjectNormalizer()];
//         $serializer     = new Serializer($normalizers ,$encoders);
//         $content        = $request->getContent();
//         $entityManager  = $this->EM;
//         $step1          = $serializer->deserialize($content, ActivityStep1::class, 'json');

//         $chkclient      = $clientrepo->findOneBy(['id'=>$step1->getClient()]);
//         $chklegalform   = $legalrepo->findOneBy(['id'=>$step1->getLegalForm()]);
//         $chkepa         = $eparepo->findOneBy(['id'=>$step1->getEpa()]);

//         if (!$chkclient) {
//             return new ApiResponse([$step1->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
//         }
//         if (!$chklegalform) {
//             return new ApiResponse([$step1->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid legalform");
//         }
//         if (!$chkepa) {
//             return new ApiResponse([$step1->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid Epa");
//         }

//         $chkclient->setSiren($step1->getSiren());
//         $chkclient->setCompanynameClient($step1->getCompanyName());
//         $chkclient->setCaptionClient($step1->getCaption());
//         $chkclient->setLegalform($chklegalform);
//         $chkclient->setEpa($chkepa);
//         $entityManager->persist($chkclient);
//         $entityManager->flush();

//         return new ApiResponse([$step1],200,["Content-Type"=>"application/json"],'json',"success");        
//     }

//     /**
//      * @Route("/api/v3/alreadyregistered/individual/activity/step2", name="session2",methods={"POST"})
//      */
//     public function session2(Request $request,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefCountryRepository $contryrepo)
//     {
//         $encoders       = [new JsonEncoder()];
//         $normalizers    = [new ObjectNormalizer()];
//         $serializer     = new Serializer($normalizers ,$encoders);
//         $content        = $request->getContent();
//         $entityManager  = $this->EM;
//         $step2          = $serializer->deserialize($content, ActivityStep2::class, 'json');

//         $chkclient      = $clientrepo->findOneBy(['id'=>$step2->getClient()]);
//         $contry         = $contryrepo->findOneBy(['id'=>$step2->getContry()]);

//         if (!$chkclient) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
//         }
//         if (!$contry) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid contry");
//         }

//         $chkclient->setAddressClient($step2->getAddress());
//         $chkclient->setZipcodeClient($step2->getZipcode());
//         $chkclient->setCityClient($step2->getCity());
//         $chkclient->setCountry($contry);
//         $entityManager->persist($chkclient);
//         $entityManager->flush();

//         return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");
//     }

//      /**
//      * @Route("/api/v3/alreadyregistered/individual/activity/step3", name="session3",methods={"POST"})
//      */
//     public function session3(Request $request,DataClientRepository $clientRepo,DataClientWhereclientRepository $dataclientwcRepo,
//                              DataClientWheresupplierRepository $dataclientwsRepo,DataCountryWhereclientRepository $datacountrywcRepo,
//                              DataCountryWheresupplierRepository $datacountrywsRepo,RefWhereclientRepository $refwcrepo,
//                              RefWheresupplierRepository $refwsrepo,RefCountryRepository $refcountryrepo,RefWhoclientRepository $whoclientrepo)
//     {
//         $encoders       = [new JsonEncoder()];
//         $normalizers    = [new ObjectNormalizer()];
//         $serializer     = new Serializer($normalizers ,$encoders);
//         $content        = $request->getContent();
//         $entityManager  = $this->EM;
//         $step3          = $serializer->deserialize($content, ActivityStep3::class, 'json');
//         $chkclient      = $clientRepo->findOneBy(['id'=>$step3->getClient()]);
//         $chkwhoclient   = $whoclientrepo->findOneBy(['id'=>$step3->getWhoclient()]);
      
//         if (!$chkclient) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
//         }
//         if (!$chkclient) {
//             return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
//         }

//         $chkclient->setActdescClient($step3->getActdesc());
//         $chkclient->setTurnoverClient($step3->getTurnover());
//         $chkclient->setOtherWheresupplier($step3->getOtherWheresupplier());
//         $chkclient->setOtherWhereclient($step3->getOtherWhereclient());
//         $chkclient->setWhoclient($chkwhoclient);
//         $entityManager->persist($chkclient);

//         // GETTING DATAS FOR WHERE IS CLIENT ####################
//         if($step3->getWhereClientList()){
//         foreach($step3->getWhereClientList() as $clientlist)
//         {           
//             $wc = $refwcrepo->findOneBy(['id'=>$clientlist]);
//             if (!$wc) {
//             return new ApiResponse($wc,400,["Content-Type"=>"application/json"],'json',"Invalid input ");
//             }
//             $datawhereclient     = $dataclientwcRepo->findBy(["client"=>$chkclient]);
//             $datacounwhereclient = $datacountrywcRepo->findBy(["client"=>$chkclient]);
            
//             if ($datacounwhereclient) {
//                 foreach($datacounwhereclient as $data){
//                     $entityManager->remove($data);
//                     $entityManager->flush();
//                 }
//             }

//             if (!$datawhereclient) {
//                 $dwc   = new DataClientWhereclient();
//                 $dwc->setClient($chkclient);
//                 $dwc->setWhereclient($wc);
//                 $entityManager->persist($dwc);             
//             } else {
//                 foreach($datawhereclient as $data){
//                     $entityManager->remove($data);
//                     $entityManager->flush();
//                 }
//                 $dwc   = new DataClientWhereclient();
//                 $dwc->setClient($chkclient);
//                 $dwc->setWhereclient($wc);
//                 $entityManager->persist($dwc);         
//             }
//         }
//     }

//         // GETTING DATAS FOR WHERE IS SUPPLIER ####################
//         if($step3->getWhereSupplierList()){
//         foreach($step3->getWhereSupplierList() as $supplierlist)
//         {
//             $ws = $refwsrepo->findOneBy(['id'=>$supplierlist]);
//             if (!$ws) {
//                 return new ApiResponse($supplierlist,400,["Content-Type"=>"application/json"],'json',"Invalid input");
//             }
//             $datawheresupplier      = $dataclientwsRepo->findBy(["client"=>$chkclient]);
//             $datacounwheresupplier  = $datacountrywsRepo->findBy(["client"=>$chkclient]);

//             if ($datacounwheresupplier) {
//                 foreach($datacounwheresupplier as $data) {
//                     $entityManager->remove($data);
//                     $entityManager->flush();
//                 }
//             }

//             if (!$datawheresupplier) {
//                 $dws = new DataClientWheresupplier();
//                 $dws->setClient($chkclient);
//                 $dws->setWheresupplier($ws);
//                 $entityManager->persist($dws);            
//             } else {
//                 foreach($datawheresupplier as $data){
//                     $entityManager->remove($data);
//                     $entityManager->flush();
//                 }
//                 $dws = new DataClientWheresupplier();
//                 $dws->setClient($chkclient);
//                 $dws->setWheresupplier($ws);
//                 $entityManager->persist($dws);                
//             }
//         }
//     }
 
//         // GETTING DATAS FOR WHERE IS CLIENT IF OTHERS COUNTRIES ####################
//         if ($step3->getOtherWhereclient() == true) {

//             foreach($step3->getOtherWCCountryList() as $clientcountrylist)
//             {
//                 // $list = $step1 = $serializer->deserialize( json_encode($clientcountrylist,JSON_NUMERIC_CHECK ), Step2::class, 'json');
            
//                 $wcc = $refcountryrepo->findOneBy(['id'=>$clientcountrylist]);
                
//                 if (!$wcc) {
//                     return new ApiResponse($wcc,400,["Content-Type"=>"application/json"],'json',"Invalid type contact");
//                 }
//                 $datacounwhereclient = $datacountrywcRepo->findBy(["client"=>$chkclient]);
//                 $datawhereclient     = $dataclientwcRepo->findBy(["client"=>$chkclient]);
//                 if ($datawhereclient) {            
//                     foreach($datawhereclient as $data) {
//                         $entityManager->remove($data);
//                         $entityManager->flush();
//                     }
//                 }

//                 if (!$datacounwhereclient) {
//                     $dwcc = new DataCountryWhereclient();
//                     $dwcc->setClient($chkclient);
//                     $dwcc->setCountry($wcc);
//                     $entityManager->persist($dwcc);                 
//                 } else {
//                     foreach($datacounwhereclient as $data){
//                         $entityManager->remove($data);
//                         $entityManager->flush();
//                     }
//                     $dwcc = new DataCountryWhereclient();
//                     $dwcc->setClient($chkclient);
//                     $dwcc->setCountry($wcc);
//                     $entityManager->persist($dwcc);
//                 }
//             }
//        }

//        // GETTING DATAS FOR WHERE IS SUPPLIER IF OTHERS COUNTRIES ####################
//         if($step3->getOtherWheresupplier()  ==  true) {
             
//             foreach($step3->getOtherWSCountryList() as $suppliercountrylist )
//             {
//                 // $list = $step1 = $serializer->deserialize( json_encode($suppliercountrylist,JSON_NUMERIC_CHECK ), Step2::class, 'json');
//                 $wcs = $refcountryrepo->findOneBy(['id'=>$suppliercountrylist]);
//                 if (!$wcs) {
//                     return new ApiResponse($wcs,400,["Content-Type"=>"application/json"],'json',"Invalid type contact");
//                 }

//                 // $datacounwheresupplier=$datacountrywsRepo->findOneBy(["client"=>$chkClient]);
//                 $datacounwheresupplier  = $datacountrywsRepo->findBy(["client"=>$chkclient]);
//                 $datawheresupplier      = $dataclientwsRepo->findBy(["client"=>$chkclient]);
//                 if ($datawheresupplier) {
//                     foreach($datawheresupplier as $data){
//                         $entityManager->remove($data);
//                         $entityManager->flush();
//                     }
//                 }

//                 if (!$datacounwheresupplier) {
//                     $dwsc = new DataCountryWheresupplier();
//                     $dwsc->setClient($chkclient);
//                     $dwsc->setCountry($wcs);
//                     $entityManager->persist($dwsc);                    
//                 } else {
//                     foreach($datacounwheresupplier as $data){
//                         $entityManager->remove($data);
//                         $entityManager->flush();
//                     }
//                     // $entityManager->remove($datacounwheresupplier);
//                     // $entityManager->flush();
//                     $dwsc = new DataCountryWheresupplier();
//                     $dwsc->setClient($chkclient);
//                     $dwsc->setCountry($wcs);
//                     $entityManager->persist($dwsc);
//                 }
//             }
//         }
//         $entityManager->flush();
//         return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");
//     }

    /**
     * @Route("/api/v3/options/{id}/{langvalue}", name="getoption",methods={"GET"})
     */
    public function getoption_($id,$langvalue,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,
    OptionsTabelRepository $optionrepo,Request $request,CardsRepository $cardrepo,RefCardtypeRepository $cardtyperepo,RefDebittypeRepository $debittyperepo,
    RefPriorityRepository $priorepo,RefCountryRepository $contryrepo,RefCdpricecalcRepository $pricerepo,DataRequestRepository $datarequestrepo, ClientService $clientser){
        $option="";
        if(!$clientser->checkClientId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $optiondata=$optionrepo->findOneBy(['client'=>$id]);
    //   return new ApiResponse([$optiondata],404,["Content-Type"=>"application/json"],'json','invalid option',['timezone']);

        if(!$optiondata)
      {
        return new ApiResponse([$optiondata],404,["Content-Type"=>"application/json"],'json','invalid option',['timezone']);

      }
      $requestRef=null;
      $datarequest=$datarequestrepo->findOneBy(['client'=>$id]);

        if($datarequest){
            $requestRef=$datarequest->getRequestRef();
        }
    //   return new ApiResponse([$requestRef],404,["Content-Type"=>"application/json"],'json','invalid option',['timezone']);

        if($optiondata->getProduct()){
        $productid=$optiondata->getProduct()->getId();
            
        $listproducts=[];
        $listcards=[];
            array_push($listproducts,$this->getsingleproduct($langvalue,$productid,$langRepo,$labelRepo,$productrepo,$productcontentrepo,$request,$id));
            $carddatas=$cardrepo->findBy(['client'=>$id], ['id' => 'ASC']);
            foreach($carddatas as $carddata){
            $data=$this->getcards($carddata,$cardrepo,$cardtyperepo,$debittyperepo,$priorepo,$contryrepo,$pricerepo,$request);
            array_push($listcards,$data);
            }
            $DataClientOfferRepo = $this->EM->getRepository(DataClientOffer::class);
       
            $dataprodoffer = $DataClientOfferRepo->findOneBy(["client"=>$id]);
            if($dataprodoffer){
                $dataprodoffer = $dataprodoffer->getOffer();
            }
            $option=new Option();
            $option->setCheque($optiondata->getCheque());
            $option->setTpc($optiondata->getTpc());
            $option->setCash($optiondata->getCash());
            $option->setCards($listcards);
            $option->setProduct($listproducts);
            $option->setRequestRef($requestRef);
            $option->setOffer($dataprodoffer);
        }
     
            
        return new ApiResponse([$option],200,["Content-Type"=>"application/json"],'json','success',['timezone','__initializer__','__cloner__','__isInitialized__']);
       
        

    }

    public function  getSingleTypeclient(int $id,string $langvalue): ?RefTypeclient{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo     =  $this  ->EM ->  getRepository(RefLabel::class);        
        $TypeclientRepo      =  $this  ->EM ->  getRepository(RefTypeclient::class);
        $paticulartypeclient =  $TypeclientRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticulartypeclient->getCodeTypeclient()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticulartypeclient -> setRefLabels($refLabels); 
                return $paticulartypeclient;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticulartypeclient -> getCodeTypeclient()]);
             $lablevalue="null";
             if(!$label){
              $paticulartypeclient -> setRefLabel($lablevalue);
             }
             else{
              $paticulartypeclient -> setRefLabel($label -> getLabelLabel());
             }
        return $paticulartypeclient;
      }
      
    public function getsingleproduct(string $langvalue,int $productid,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request,$id){
        $clientrepo=$this->EM->getRepository(DataClient::class);
        $client=$clientrepo->findOneBy(['id'=>$id]);
        $paticularproduct=$productrepo->findOneBy(['id'=>$productid]);
       $tempProduct=new Product();
       $tempProduct->setId($paticularproduct->getId());
       $tempProduct->setTypeclient($client->getTypeclient()->getId());
       $typeClientObj = $this->getSingleTypeclient($client->getTypeclient()->getId(),$langvalue);
       $tempProduct->setRefTypeclient($typeClientObj);
       $tempProduct->setCompanytype($client->getCompanytype());
       $tempProduct->setName($paticularproduct->getName());
       $tempProduct->setImageurl($paticularproduct->getImageurl());
       $tempProduct->setPrice($paticularproduct->getPrice());
       $tempProduct->setCodeProduct($paticularproduct->getCodeProduct());
       $tempProduct->setStatus($paticularproduct->getStatus());
       $tempProduct->setBusiness($paticularproduct->getBusiness());
       $tempProduct->setVisa($paticularproduct->getVisa());
       $tempProduct->setBuisDi($paticularproduct->getBuisDi());
       $tempProduct->setBuisDd($paticularproduct->getBuisDd());
       $tempProduct->setBuisSupdi($paticularproduct->getBuisSupdi());
       $tempProduct->setBuisSupdd($paticularproduct->getBuisSupdd());
       $tempProduct->setVidaDi($paticularproduct->getVidaDi());
       $tempProduct->setVisaDd($paticularproduct->getVisaDd());
       $tempProduct->setVisaSupdi($paticularproduct->getVisaSupdi());
       $tempProduct->setVisaSupdd($paticularproduct->getVisaSupdd());
       $tempProduct->setCheque($paticularproduct->getCheque());
       $tempProduct->setTpe($paticularproduct->getTpe());
       $tempProduct->setCash($paticularproduct->getCash());
       $tempProduct->setCardLimit($paticularproduct->getCardLimit());

       //set entity to util
        // return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');
       $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
       if(!$lang){
       if($langvalue!="all")
          {
             return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','label not found');
             
           }
          }
       $languages=$langRepo->findAll();
       if($langvalue=="all"){
                 $refLabels=array();
              foreach($languages as $language){
                  $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularproduct->getCodeProduct()]);
                  if (!$label){
                      $refLabels[$language->getCodeLanguage()]="";
                      continue;
                      }
                   $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                 }
                 $tempProduct-> setRefLabels($refLabels);
             
              
          
                //   return new ApiResponse([$tempProduct],200,["Content-Type"=>"application/json"],'json','success');
      }else{
      $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularproduct->getCodeProduct()]);
      if(!$label){
         
          return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','label not found');
      }
      $tempProduct->setRefLabel($label->getLabelLabel());
    //    return new ApiResponse([$tempProduct],200,["Content-Type"=>"application/json"],'json','success');
      }
       $productdesc=$productcontentrepo->findBy(['product'=>$productid]);
       //
      $descriptions=[];
       foreach($productdesc as $pdtdesc){
           $singleDescription=new Description();
           $singleDescription->setId($pdtdesc->getId());
           $singleDescription->setCodeProductcontent($pdtdesc->getCodeProductcontent());
           $singleDescription->setProduct($productid);
           $singleDescription->setDescProduct($pdtdesc->getDescProduct());
           if($langvalue=="all"){
            $refLabels=array();
            foreach($languages as $language){
                $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$pdtdesc->getCodeProductcontent()]);
                if (!$label){
                    $refLabels[$language->getCodeLanguage()]="";
                    continue;
                    }
                 $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
               }
               $singleDescription-> setRefLabels($refLabels);
           
            
        
            //    return new ApiResponse([$singleDescription],200,["Content-Type"=>"application/json"],'json','success');
    }else{
    $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$pdtdesc->getCodeProductcontent()]);
    if(!$label){
       
        return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','label not found');
    }

    $singleDescription->setRefLabel($label->getLabelLabel());
}
           array_push($descriptions,$singleDescription);
    
        // $code_arr[]=$pdtdesc->getCodeProductcontent();
    }
    //setDescription
    $tempProduct->setDescription($descriptions);
    return $tempProduct;

    }
    public function getcards($carddata,CardsRepository $cardrepo,RefCardtypeRepository $cardtyperepo,RefDebittypeRepository $debittyperepo,RefPriorityRepository $priorepo,RefCountryRepository $contryrepo,RefCdpricecalcRepository $pricerepo,Request $request){
        $debit=$debittyperepo->findOneBy(['id'=>$carddata->getDebittype()]);
        $cardtype=$cardtyperepo->findOneBy(['id'=>$carddata->getCardtype()]);
        $priority=$priorepo->findOneBy(['id'=>$carddata->getPriority()]);
        $contry=$contryrepo->findOneBy(['id'=>$carddata->getCountry()]);
        // $price=$pricerepo->findOneBy(array('priority' => $carddata->getPriority(),'cardtype' => $carddata->getCardtype(),'debittype' => $carddata->getDebittype()));
        $card=new Card();
        $card->setCardtype($cardtype);
        $card->setDebittype($debit);
        $card->setPriority($priority);
        $card->setName($carddata->getName());
        $card->setSurname($carddata->getSurname());
        $card->setCountry($contry);
        $card->setTelephone($carddata->getTelephone());
        $card->setTelecode($carddata->getTelecode());
        $card->setCountrycode("");
        if($carddata->getTelephone()){
            $country = $contryrepo->findOneBy(['id'=>$carddata->getTelecode()]);
            $telecode = null;
            if($country){
                $telecode = $country->getCountryCode();
                $card->setCountrycode($country->getCountryCode());
            }
        }

        // if($price){
        $card->setPrice($carddata->getPrice());
        // }
            return $card;
}
    /**
     * @Route("/api/v3/personaldata/{id}/{lang}", name="getpersonaldata",methods={"GET"})
     */
    public function getpersonaldata(Request $request,DataAttorneyRepository $attorny, $id, $lang, MasterService $masterService, DataContactRepository $datacontrepo, ClientService $clientservice){

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
      
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $res = array();


        $chkattorny = $attorny->findOneBy(['id'=>$id]);
        if (!$chkattorny){
            $personaldata=new PersonalData();
            return new ApiResponse([$personaldata],400,["Content-Type"=>"application/json"],'json','id not found');
        }
        
        if (!$clientservice->checkAttorneyId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $civilid=null;
        $cbirthid=null;
        $nationalid=null;
        $resid=null;
        $fisid=null;
        $functionid=null;
        $personaldata=new PersonalData();
        if($chkattorny->getCivilityAttorney()){
            $civilid=$chkattorny->getCivilityAttorney()->getId();
            $personaldata->setCivilityAttorney($masterService->getSingleCivility($lang,$civilid));
        }
        if($chkattorny->getCountrybirthAttorney()){   
            // $mc= new MastersController();
            $cbirthid=$chkattorny->getCountrybirthAttorney()->getId();
            $personaldata->setCountrybirthAttorney($masterService->getSingleCountry($lang,$cbirthid));
        }
        if($chkattorny->getNationalityAttorney()){
            $nationalid=$chkattorny->getNationalityAttorney()->getId();
            $personaldata->setNationalityAttorney($masterService->getSingleCountry($lang,$nationalid));
        }
        if($chkattorny->getResidentcountryAttorney()){
            $resid=$chkattorny->getResidentcountryAttorney()->getId();
            $personaldata->setResidentcountryAttorney($masterService->getSingleCountry($lang,$resid));
        }
        if($chkattorny->getFiscalcountryAttorney()){
            $fisid=$chkattorny->getFiscalcountryAttorney()->getId();
            $personaldata->setFiscalcountryAttorney($masterService->getSingleCountry($lang,$fisid));
        }

        // return new ApiResponse([$chkattorny->getFunction()],200,["Content-Type"=>"application/json"],'json','success!!');
        if($chkattorny->getFunction()){
            $functionid=$chkattorny->getFunction()->getId();
        //    return new ApiResponse([$chkattorny->getFunction()],200,["Content-Type"=>"application/json"],'json','success!!');
            $personaldata->setFunction($masterService->getSingleFunction($lang,$functionid));
        }
          
        $personaldata->setId($chkattorny->getId());
        $personaldata->setNameAttorney($chkattorny->getNameAttorney());
        $personaldata->setSurnameAttorney($chkattorny->getSurnameAttorney());
        $personaldata->setBirthName($chkattorny->getBirthName());
        $personaldata->setPlacebirthAttorney($chkattorny->getPlacebirthAttorney());
        $personaldata->setDatebirthAttorney($chkattorny->getDatebirthAttorney());
        $personaldata->setAddressAttorney($chkattorny->getAddressAttorney());
        $personaldata->setZipcodeAttorney($chkattorny->getZipcodeAttorney());
        $personaldata->setCityAttorney($chkattorny->getCityAttorney());
        $personaldata->setAmericanAttorney($chkattorny->getAmericanAttorney());
        $personaldata->setSirenAttorney($chkattorny->getSirenAttorney());
        $personaldata->setAmericanAttorney($chkattorny->getAmericanAttorney());
        $personaldata->setPartAttorney($chkattorny->getPartAttorney());
        $personaldata->setPercentageAttorney($chkattorny->getPercentageAttorney());
        $personaldata->setIscompany($chkattorny->getIscompany());
        $personaldata->setIscompany($chkattorny->getIscompany());
        $personaldata->setIsrepresentative($chkattorny->getIsrepresentative());
        $personaldata->setFiscalnumber($chkattorny->getFiscalnumberAttorney());
        $datacontact=new Contact();
        $email=$datacontrepo->findOneBy(['type_contact'=>2,'attorney'=>$id]);
        $phone=$datacontrepo->findOneBy(['type_contact'=>4,'attorney'=>$id]);
        $phonefix=$datacontrepo->findOneBy(['type_contact'=>13,'attorney'=>$id]);
        $countryrepo = $this->EM->getRepository(RefCountry::class);
        // return new ApiResponse([$personaldata],200,["Content-Type"=>"application/json"],'json','success!!AA');
        if($email){
        $datacontact->setEmail($email->getValueContact());
        }
        if($phone){
        $country = $countryrepo->findOneBy(['id'=>$phone->getTelecode()]);
        $telecode = null;
        $countrycode = null;
        if($country){
            $telecode = $phone->getTelecode();
            $countrycode = $country->getCountryCode();
        }
        $datacontact->setPhone($phone->getValueContact());
        $datacontact->setTelecode($telecode);
        $datacontact->setCountrycode($countrycode);
        }
        if($phonefix){
        $countryfix = $countryrepo->findOneBy(['id'=>$phonefix->getTelecode()]);
        $telecodefix = null;
        $countrycodefix = null;
        if($countryfix){
            $telecodefix = $phonefix->getTelecode();
            $countrycodefix = $countryfix->getCountryCode();
        }
        $datacontact->setPhonefix($phonefix->getValueContact());
        $datacontact->setTelecodeFix($telecodefix);    
        $datacontact->setCountrycodeFix($countrycodefix);    
        }
        
        $personaldata->setContact($datacontact);
        $addrassstring = $this->checkField($personaldata->getAddressAttorney());
        $zipcodestring = $this->checkField($personaldata->getZipcodeAttorney());
        $citystring = $this->checkField($personaldata->getCityAttorney());
        $countrystring = $this->checkField($personaldata->getResidentcountryAttorney());
        $addresses = explode ("|", $addrassstring); 
        $astring="";
        $adcount=0;
        foreach($addresses as $address){
            $adcount = $adcount+1;
          if($adcount==1){
            $astring = $address;
            continue;
          }
          $astring = $astring.":".$address; 
        }
        if($addresses[0]!=null&&$personaldata->getZipcodeAttorney()!=null&&$personaldata->getCityAttorney()!=null&&$personaldata->getResidentcountryAttorney()!=null){
            $personaldata->setlrValidationAddress(true);    
        }
        else{
            $personaldata->setlrValidationAddress(false);    
        }
        if($countrystring!=""){
            $countrystring = $countrystring->getDescCountry();
        }
        $stringarray = [$astring,$zipcodestring,$citystring,$countrystring];
        $string=null;
        $count=0;
        foreach($stringarray as $strarray){
            if($strarray==""){
               $count=$count+1;
               continue;
            }
            if($stringarray[0]==$strarray){
                $string = $strarray;
                continue;
             }
            $string = $string.":".$strarray;
        }
        if($count==4){
            $personaldata->setlrValidationAddress(null);
        }
        // $string =$addrassstring." ".$zipcodestring." ".$citystring." ".$countrystring;
        $personaldata->setlrStringAddress($string);
        
        return new ApiResponse([$personaldata],200,["Content-Type"=>"application/json"],'json','success!!',['timezone']);
    }

    public function checkField($variable){
        if($variable==null){
            $variable="";
            return $variable;
        }
        return $variable;

    }

    /**
     * @Route("/api/v3/activity/{id}/{lang}", name="getactivity",methods={"GET"})
     */
    public function getactivity_(Request $request,DataClientRepository $clientrepo,$id,MasterService $masterService,$lang,DataClientWhereclientRepository $dataclientwcRepo,DataClientWheresupplierRepository $dataclientwsRepo
                                ,DataCountryWhereclientRepository $datacountrywcRepo,DataCountryWheresupplierRepository $datacountrywsRepo, ClientService $clientser){
      
        if(!$clientser->checkClientId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $chkclient=$clientrepo->findOneBy(['id'=>$id]);
        if(!$chkclient){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','id not found');
        }
        $fidrepo  =  $this->EM->getRepository(DataFid::class);
        $checkfid = $fidrepo->findBy(["client"=>$chkclient]);
        $bankid=null;
        $legalid=null;
        $epaid=null;
        $companytypeid=null;
        $contryid=null;
        $civilid=null;
        $typeclientid=null;
        $activity=new Activity();
        $activity->setId($chkclient->getId());
        $activity->setCompanynameClient($chkclient->getCompanynameClient());
        $activity->setCaptionClient($chkclient->getCaptionClient());
        $activity->setAddressClient($chkclient->getAddressClient());
        $activity->setZipcodeClient($chkclient->getZipcodeClient());
        $activity->setCityClient($chkclient->getCityClient());
        $activity->setTurnoverClient($chkclient->getTurnoverClient());
        $activity->setTurnoveryearClient($chkclient->getTurnoveryearClient());
        $activity->setTurnovertypeClient($chkclient->getTurnovertypeClient());
        $activity->setIbanClient($chkclient->getIbanClient());
        $activity->setBicClient($chkclient->getBicClient());
        $datafidarr = [];
        $dataFidArrstring = "";
        if($checkfid){
            $singlefid = [];
            foreach($checkfid as $fid){
                if($fid->getFid()){
                    $singlefid["id"] = $fid->getFid()->getId();
                    $singlefid["name"]=$fid->getFid()->getName();
                    if($dataFidArrstring!=""){
                        if($fid->getYesOrNo()=="OUI"){
                            $dataFidArrstring = $dataFidArrstring.",".$fid->getFid()->getName();
                        }
                    }
                    else{
                        if($fid->getYesOrNo()=="OUI"){
                            $dataFidArrstring = $dataFidArrstring.$fid->getFid()->getName();
                        }
                    }
                    $singlefid["yesOrNo"] = $fid->getYesOrNo(); 
                    array_push($datafidarr,$singlefid);
                }
            }
        }
        $activity->setDataFidArrstring($dataFidArrstring);
        $activity->setDataFidArr($datafidarr);
        if($chkclient->getBank()){
            $bankid=$chkclient->getBank()->getId();
            $activity->setBank($masterService->getSingleBank($lang,$bankid));
        }
        if($chkclient->getLegalform()){
            $legalid=$chkclient->getLegalform()->getId();
            $activity->setLegalform($masterService->getSingleLegalForm($lang,$legalid));
        }
        if($chkclient->getEpa()){
            $epaid=$chkclient->getEpa()->getId();
            $activity->setEpa($masterService->getSingleEpa($lang,$epaid));
        }
        if($chkclient->getCountry()){
            $contryid=$chkclient->getCountry()->getId();
            $activity->setCountry($masterService->getSingleCountry($lang,$contryid));
        }
        if($chkclient->getCompanytype()){
            $companytypeid=$chkclient->getCompanytype()->getId();
            $activity->setCompanytype($masterService->getSingleCompanytype($lang,$companytypeid));
        }
        if($chkclient->getCivility()){
            $civilid=$chkclient->getCivility()->getId();
            $activity->setCivility($masterService->getSingleCivility($lang,$civilid));
        }
        if($chkclient->getWhoclient()){
            $whoclientid=$chkclient->getWhoclient()->getId();
            $activity->setWhoclient($masterService->getSingleWhoClient($lang,$whoclientid));
        }
        if($chkclient->getTypeclient()){
            $typeclientid=$chkclient->getTypeclient()->getId();
            $activity->setTypeclient($masterService->getSingleTypeClient($lang,$typeclientid));
        }
        $clwcid=$dataclientwcRepo->findBy(['client'=>$id]);
        $Whereclientarray=array();
        $Wheresupplierarray=array();
        $CountryWhereclientarray=array();
        $arrayv1=array();
        // return new ApiResponse([$clwcid],200,["Content-Type"=>"application/json"],'json','success!!');
        foreach($clwcid as $clwc){
            array_push($Whereclientarray,$masterService->getSingleWhereclient($lang,$clwc->getWhereclient()->getId()));
        }
        $activity->setDataClientWhereclients($Whereclientarray);
        $clwsid=$dataclientwsRepo->findBy(['client'=>$id]);
        foreach($clwsid as $clws){
            array_push($Wheresupplierarray,$masterService->getSingleWhereSupplier($lang,$clws->getWheresupplier()->getId()));
        }
        $activity->setDataClientWheresuppliers($Wheresupplierarray);
        $cowcid=$datacountrywcRepo->findBy(['client'=>$id]);
       
        foreach($cowcid as $cowc){
            array_push($CountryWhereclientarray,$masterService->getSingleCountry($lang,$cowc->getCountry()->getId()));
        }
        $activity->setDataCountryWhereclients($CountryWhereclientarray);
        $cowsid=$datacountrywsRepo->findBy(['client'=>$id]);
      
        foreach($cowsid as $cows){
            array_push($arrayv1,$masterService->getSingleCountry($lang,$cows->getCountry()->getId()));
        }
        //  return new ApiResponse([$masterService->getSingleCountry($lang,2)],200,["Content-Type"=>"application/json"],'json','success!!');
       
        if($Whereclientarray||$CountryWhereclientarray){
            $activity->setIsDataWhereclient("OK");
        }
        else{
            $activity->setIsDataWhereclient(null);
        }
        if($Wheresupplierarray||$arrayv1){
            $activity->setIsDataWhereSupplier("OK");
        }
        else{
            $activity->setIsDataWhereSupplier(null);
        }
        $stringwhereclient=null;
        $stringwheresupplier=null;
        if($lang=='fr'){
            if($Whereclientarray){
                $stringwhereclient=$this->stringConversion($Whereclientarray);
            }
            if($CountryWhereclientarray){
                $stringwhereclient1 = $this->countryStringConversion($CountryWhereclientarray);
                $stringwhereclient = $stringwhereclient.$stringwhereclient1;

            }
            if($Wheresupplierarray){
                $stringwheresupplier=$this->stringConversion($Wheresupplierarray);
            }
            if($arrayv1){
                $stringwheresupplier1 = $this->countryStringConversion($arrayv1);
                $stringwheresupplier = $stringwheresupplier.$stringwheresupplier1;

            }
            if($stringwhereclient!=null){
                $stringwhereclient=substr($stringwhereclient,0,strlen($stringwhereclient)-1);
            }
            if($stringwheresupplier){
                $stringwheresupplier=substr($stringwheresupplier,0,strlen($stringwheresupplier)-1);
            }

            // $stringwhereclient=strlen($stringwhereclient);

            // return new ApiResponse([$stringwheresupplier],200,["Content-Type"=>"application/json"],'json','success!!');

        }
        // $wheresupplierstring = str_replace(' ','',$stringwheresupplier);
        // $whereclientstring = str_replace(' ','',$stringwhereclient);
        $wheresupplierstring = str_replace("Union européenne",'Union Européenne',$stringwheresupplier);
        $whereclientstring = str_replace("Union européenne",'Union Européenne',$stringwhereclient);
        $activity->setStrWhereSupplier($wheresupplierstring);
        $activity->setStrWhereClient($whereclientstring);
        $activity->setDataCountryWheresuppliers($arrayv1);
        $activity->setActdescClient($chkclient->getActdescClient());
        $activity->setOtherbanqueClient($chkclient->getOtherbanqueClient());
        $activity->setOtherWhereclient($chkclient->getOtherWhereclient());
        $activity->setOtherWheresupplier($chkclient->getOtherWheresupplier());
        $activity->setSiren($chkclient->getSiren());
        $activity->setNomore25($chkclient->getNomore25());
        $addrassstring = $this->checkField($activity->getAddressClient());
        $zipcodestring = $this->checkField($activity->getZipcodeClient());
        $citystring = $this->checkField($activity->getCityClient());
        $countrystring = $this->checkField($activity->getCountry());
        $addresses = explode ("|", $addrassstring); 
        $astring="";
        $adcount=0;
        foreach($addresses as $address){
            $adcount = $adcount+1;
          if($adcount==1){
            $astring = $address;
            continue;
          }
          $astring = $astring.":".$address; 
        }
        if($addresses[0]!=null&&$activity->getZipcodeClient()!=null&&$activity->getCityClient()!=null&&$activity->getCountry()!=null){
            $activity->setacValidationAddress(true);    
        }
        else{
            $activity->setacValidationAddress(false);    
        }
        if($countrystring!=""){
            $countrystring = $countrystring->getDescCountry();
        }
        
        $stringarray = [$astring,$zipcodestring,$citystring,$countrystring];
        $string=null;
        $count=0;
        foreach($stringarray as $strarray){
            if($strarray==""){
               $count=$count+1;
               continue;
            }
            if($stringarray[0]==$strarray){
                $string = $strarray;
                continue;
             }
            $string = $string.":".$strarray;
        }
        if($count==4){
            $activity->setacValidationAddress(null);
        }
        // $string =$addrassstring." ".$zipcodestring." ".$citystring." ".$countrystring;
        $activity->setacStringAddress($string);
      
        return new ApiResponse([$activity],200,["Content-Type"=>"application/json"],'json','success!!',[ "__initializer__","__cloner__","__isInitialized__"]);


    }

    public function stringConversion($datas){
        $string = null;
        foreach($datas as $data){
            $string1 = $data->getRefLabel();
            $string = $string1.','.$string;
           
        }
        return $string;

    }
    public function countryStringConversion($datas){
        $string = null;
        foreach($datas as $data){
            $string1 = $data->getDescCountry();
            $string = $string1.','.$string;
           
        }
        return $string;

    }
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
    /*$client = new MQTTClient('localhost', 1883);
    $success = $client->sendConnect(12345);  // set your client ID
    if ($success) {
       $client->sendPublish('/messages8', '{"id":5}',0,0);
       $client->sendDisconnect();    
    }
    $client->close();*/
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
  
}