<?php

namespace App\Controller;
use App\Entity\Box;
use App\Entity\Cards;
use App\Entity\DataCTO;
use App\Utils\ApiHelper;
use App\UtilsV3\Contact;
use App\UtilsV3\Passage;
use App\Utils\ClientList;
use App\Entity\DataClient;
use App\Utils\ApiResponse;
use App\Entity\DataContact;
use App\Entity\DataRequest;
use App\Entity\DataAttorney;
use App\Entity\DataTemplate;
use App\Entity\OptionsTabel;
use App\Entity\RefLegalform;
use App\Entity\DataEloquaCdo;
use App\Entity\DataSignature;
use App\Entity\DataUserSpace;
use App\Service\AlertService;
use App\Service\EmailService;
use App\Entity\DataFieldIssue;
use App\Service\ClientService;
use App\Service\EloquaService;
use App\Service\MasterService;
use App\Entity\DataRequestFile;
use App\Service\EmailServiceV1;
use App\UtilsV3\Scenario2\Card;
use App\Service\DocumentService;
use App\UtilsV3\Scenario2\Login;
use App\Entity\DataEloquaContact;
use App\Entity\RefRequeststatus ;
use App\Repository\BoxRepository;
use App\Service\ContraliaService;
use App\UtilsV3\Scenario2\Cardss;
use App\UtilsV3\Scenario2\Option;
use App\UtilsV3\Scenario2\StepV1;
use App\UtilsV3\Scenario2\Product;
use App\Repository\CardsRepository;
use App\Service\IntegrationService;
use App\UtilsV3\Scenario2\Activity;
use App\UtilsV3\Scenario2\Contact1;
use App\UtilsV3\Scenario2\LegStep1;
use App\UtilsV3\Scenario2\LegStep2;
use App\UtilsV3\Scenario2\LegStep3;
use App\Repository\RefEpaRepository;
use App\UtilsV3\Scenario2\IsCompany;
use App\Entity\DataClientWhereclient;
use App\Repository\DataCTORepository;
use App\Entity\DataCountryWhereclient;
use App\Entity\RefFid;
use App\Repository\RefLabelRepository;
use App\UtilsV3\Scenario2\Description;
use App\Entity\DataClientWheresupplier;
use App\Entity\DataTemplateVariablesV1;
use App\UtilsV3\Scenario2\PersonalData;
use App\Entity\DataCountryWheresupplier;
use App\Entity\DataFid;
use App\Entity\DataRequestRequeststatus;
use App\Repository\DataClientRepository;
use App\Repository\RefCountryRepository;
use App\Repository\RefProductRepository;
use App\UtilsV3\Scenario2\ActivityStep1;
use App\UtilsV3\Scenario2\ActivityStep2;
use App\UtilsV3\Scenario2\ActivityStep3;
use App\UtilsV3\Scenario2\ActivityStep4;
use App\Repository\DataContactRepository;
use App\Repository\DataRequestRepository;
use App\Repository\RefCardtypeRepository;
use App\Repository\RefCivilityRepository;
use App\Repository\RefFunctionRepository;
// use App\UtilsV3\Scenario2\Contact;
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
use App\Repository\DataClientSabRepository;
use App\Repository\DataUserspaceRepository;
use App\Repository\DataFieldIssueRepository;
use App\Repository\RefCdpricecalcRepository;
use App\Repository\RefCompanytypeRepository;
use App\Repository\RefTypecontactRepository;
use App\Repository\RefWhereclientRepository;
use Symfony\Component\Serializer\Serializer;
use App\Repository\DataRequestFileRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RefRequeststatusRepository;
use App\Repository\RefWheresupplierRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RefProductContentRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\UtilsV3\Scenario2\DataRequestRequststatus;
use App\Repository\DataClientWhereclientRepository;
use App\Repository\DataCountryWhereclientRepository;
use App\Repository\DataClientWheresupplierRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\Repository\DataRequestRequeststatusRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use App\Repository\DataCountryWheresupplierRepository; 
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class ProcessV3scenario2Controller extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/process/v3scenario2", name="process_v3scenario2")
     */
    public function index()
    {
        return $this->render('process_v3scenario2/index.html.twig', [
            'controller_name' => 'ProcessV3scenario2Controller',
        ]);
    }

     /**
     * @Route("/api/v3/scenario2/alreadyregistered/registered/legalrepresentative/step1", name="sessio11",methods={"POST"})
     */
    public function sessio11_(Request $request,RefCivilityRepository $civilityrepo,RefTypecontactRepository $typecontrepo,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,
    DataContactRepository $datacontactrepo,DataUserspaceRepository $userspacerepo,DataRequestRepository $datarequestrepo,BoxRepository $boxrepo,ClientService $clientser)
    {
        $encoders      =  [new JsonEncoder()];
        $normalizers   =  [new ObjectNormalizer()];
        $serializer    =  new Serializer($normalizers ,$encoders);
        $content       =  $request->getContent();
        $entityManager =  $this->EM;
        $step1         =  $serializer->deserialize($content, LegStep1::class, 'json');
        if(!$clientser->checkClientId($step1->getClientId(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $clientchk     =  $clientrepo->findOneBy(['id'=>$step1->getClientId()]);
        $datarequest   =  $datarequestrepo->findOneBy(['client'=>$step1->getClientId()]);
        $email         =  $userspacerepo->findOneBy(['id_request'=>$datarequest->getId()]);
        $email_id      =  null;

        if($email){
            $email_id  =  $email->getEmailUs();
        }

        if(!$clientchk){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json',"invalid client!!!!");
        }

        $name         = null;
        $surname      = null;
        $chkcivility  = null; 
        $birthname =null;
        if($step1->getCivility()){
            $chkcivility = $civilityrepo->findOneBy(['id'=>$step1->getCivility()]);
        }
        if($step1->getName()){
            $name    =  $step1->getName();
        }
        if($step1->getSurname()){
            $surname =  $step1->getSurname();
        }
        if($step1->getAttorney()){
            $attony  =  $attonyrepo->findOneBy(['id'=>$step1->getAttorney()]);
        }
        if($step1->getAttorney()==null){
            $attony  =  new DataAttorney;
        }
        if($step1->getBirthName()){
            $birthname =  $step1->getBirthName();
        }
        
        $attony  ->  setCivilityAttorney($chkcivility);
        $attony  ->  setClient($clientchk);
        $attony  ->  setNameAttorney($name);
        $attony  ->  setSurnameAttorney($surname);
        $attony  ->  setBirthName($birthname);
        $attony  ->  setIsmandatoryAttorney(true);   
        if ($step1->getAttorney() == null) {
            $attony  ->  setIsshareholderAttorney(false);
        }                
        $entityManager->persist($attony);
  
        if($step1 -> getContacts()){
            foreach($step1 -> getContacts() as $noncontact )
            {
                $contact      = $serializer->deserialize( json_encode($noncontact), Contact1::class, 'json');
                $utilContacts = new Contact1();
                $tc           = $typecontrepo->find($contact->getTypeContact());
                if(!$tc){
                return new ApiResponse($tc,400,["Content-Type"=>"application/json"],'json',"Invalid type contact");
                }
                $telecode=null;
                if($contact->getTelecode()){
                    $telecode=$contact->getTelecode();
                }
                  $dataContact   =  $datacontactrepo->findOneBy(["attorney"=>$attony,"type_contact"=>$tc]);
                  if(!$dataContact){
                     $dataContact   =  new DataContact();
                  }
                  $dataContact   -> setClient($clientchk);
                  $dataContact   -> setAttorney($attony);
                  $dataContact   -> setTypeContact($tc);
                  $dataContact   -> setValueContact($contact->getValueContact());
                  $dataContact   -> setTelecode($telecode);
                  $entityManager -> persist($dataContact);
            }
            // $entityManager->flush();           
        }
        $box=$boxrepo->findOneBy(['client'=>$step1->getClientId()]);
        if(!$box)
        {
            $box   =new Box;
        }
        $box->setClient($step1->getClientId());
        $box->setBoxId($step1->getBoxId());
        $entityManager -> persist($box);
        $entityManager -> flush();
        if($step1 ->getAttorney()==null){
            return new ApiResponse(["client_id"=>$clientchk->getId(),"attorny_id"=>$attony->getId(),"email"=>$email_id],200,["Content-Type"=>"application/json"],'json',"successfully added");
        } 
        if($step1 ->getAttorney()){
            return new ApiResponse(["client_id"=>$clientchk->getId(),"attorny_id"=>$attony->getId(),"email"=>$email_id],200,["Content-Type"=>"application/json"],'json',"successfully updated");
        } 
    }

      /**
     * @Route("/api/v3/scenario2/alreadyregistered/registered/legalrepresentative/step2", name="sessio2",methods={"POST"})
     */
    public function sessio2_(Request $request,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefCountryRepository $contryrepo,BoxRepository $boxrepo,ClientService $clientser)
    {
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $content       = $request->getContent();
        $entityManager = $this->EM;
        $step2         = $serializer->deserialize($content, LegStep2::class, 'json');
        if(!$clientser->checkClientId($step2->getClient(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $chkclient     = $clientrepo->findOneBy(['id'=>$step2->getClient()]);
        $attony        = $attonyrepo->findOneBy(['id'=>$step2->getAttorney()]);
        
        if(!$chkclient){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
        }
        $country = null;
        $city    = null;
        $zipcode = null;
        $address = null;
        if($step2 -> getResContry()){
            $country = $contryrepo->findOneBy(['id'=>$step2->getResContry()]);
        }
        if($step2->getCity()){
            $city    = $step2->getCity();
        }
        if($step2->getZipcode()){
            $zipcode = $step2->getZipcode();
        }
        if($step2->getAddress()){
            $address = $step2->getAddress();
        }
        // return new ApiResponse([$step2->getCity()],200,["Content-Type"=>"application/json"],'json',"updated success!!!!");

        if($step2->getAttorney()){
            $attony   ->  setAddressAttorney($address);
            $attony   ->  setZipcodeAttorney($zipcode);
            $attony   ->  setCityAttorney($city);
            $attony   ->  setResidentcountryAttorney($country);
            $entityManager  ->  persist($attony);
            $entityManager  ->  flush();
            $box=$boxrepo->findOneBy(['client'=>$step2->getClient()]);
            if(!$box)
            {
                $box   =new Box;
            }
            $box->setClient($step2->getClient());
            $box->setBoxId($step2->getBoxId());
            $entityManager -> persist($box);
            $entityManager -> flush();
            $entityManager  ->  flush();
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"updated success");
        }
            $attony  =  new DataAttorney;
            $attony  -> setClient($chkclient);
            $attony  -> setAddressAttorney($address);
            $attony  -> setZipcodeAttorney($zipcode);
            $attony  -> setCityAttorney($city);
            $attony  -> setResidentcountryAttorney($country);
            $entityManager  ->  persist($attony);

            $box=$boxrepo->findOneBy(['client'=>$step2->getClient()]);
            if(!$box)
            {
                $box   =new Box;
            }
            $box->setClient($step2->getClient());
            $box->setBoxId($step2->getBoxId());
            $entityManager -> persist($box);
            $entityManager -> flush();
            $entityManager  ->  flush();
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"added  success");
  
    }

     /**
     * @Route("/api/v3/scenario2/alreadyregistered/registered/legalrepresentative/step3", name="sessio3",methods={"POST"})
     */
    public function sessio3_(Request $request,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefCountryRepository $contryrepo,BoxRepository $boxrepo,ClientService $clientser)
    {
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $content       = $request->getContent();
        $entityManager = $this->EM;
        $step3         = $serializer->deserialize($content, LegStep3::class, 'json');
        if(!$clientser->checkClientId($step3->getClient(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $chkclient     = $clientrepo->findOneBy(['id'=>$step3->getClient()]);
        $dob           = null;
        $pob           = null;
        $contrybirth   = null;
        $nationality   = null;
        $ficacontry    = null;
        $ficanumber    = null;
        if(!$chkclient){
            return new ApiResponse([$step3->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
        }
       
        if($step3 -> getDob()){
            $dob  =new \DateTime($step3->getDob());
        }
        if($step3 -> getFiscalnumber()){
            $ficanumber  = $step3->getFiscalnumber();
        }
        if($step3 -> getPlacebirth()){
            $pob  = $step3->getPlacebirth();
        }
        if($step3 -> getFiscalcontry()){
            $ficacontry  =  $contryrepo->findOneBy(['id'=>$step3->getFiscalcontry()]);
        }
        if($step3 -> getNationality()){
            $nationality =  $contryrepo->findOneBy(['id'=>$step3->getNationality()]);
        }
        if($step3 -> getContrybirth()){
            $contrybirth =  $contryrepo->findOneBy(['id'=>$step3->getContrybirth()]);
        }

        if($step3 -> getAttorney()){
            $attony  =   $attonyrepo->findOneBy(['id'=>$step3->getAttorney()]);
            $attony  ->  setDatebirthAttorney($dob);
            $attony  ->  setPlacebirthAttorney($pob);
            $attony  ->  setCountrybirthAttorney($contrybirth);
            $attony  ->  setNationalityAttorney($nationality);
            $attony  ->  setFiscalcountryAttorney($ficacontry);
            $attony  ->  setFiscalnumberAttorney($ficanumber);
            $attony  ->  setAmericanAttorney($step3 -> getAmerican());
            $entityManager -> persist($attony);
            $box=$boxrepo->findOneBy(['client'=>$step3->getClient()]);
            if(!$box)
            {
                $box   =new Box;
            }
            $box->setClient($step3->getClient());
            $box->setBoxId($step3->getBoxId());
            $entityManager -> persist($box);
            $entityManager -> flush();
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"updated success");
        }    
            $attony  =   new DataAttorney;
            $attony  ->  setClient($chkclient);
            $attony  ->  setDatebirthAttorney($dob);
            $attony  ->  setPlacebirthAttorney($pob);
            $attony  ->  setCountrybirthAttorney($contrybirth);
            $attony  ->  setNationalityAttorney($nationality);
            $attony  ->  setFiscalcountryAttorney($ficacontry);
            $attony  ->  setFiscalnumberAttorney($ficanumber);
            $attony  ->  setAmericanAttorney($step3 -> getAmerican());
            $entityManager->persist($attony);

            $box=$boxrepo->findOneBy(['client'=>$step3->getClient()]);
            if(!$box)
            {
                $box   =new Box;
            }
            $box->setClient($step3->getClient());
            $box->setBoxId($step3->getBoxId());
            $entityManager -> persist($box);
           
            $entityManager->flush();
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"added success");
    }

     /**
     * @Route("/api/v3/scenario2/alreadyregistered/registered/activity/step1", name="sesio1",methods={"POST"})
     */
    public function sesio1_(ClientService $clientser,Request $request,DataClientRepository $clientrepo,RefCountryRepository $contryrepo,RefLegalformRepository $legalrepo,RefEpaRepository $eparepo,BoxRepository $boxrepo)
    {
        $encoders       =   [ new JsonEncoder()];
        $normalizers    =   [new ObjectNormalizer()];
        $serializer     =   new Serializer($normalizers ,$encoders);
        $content        =   $request->getContent();
        $entityManager  =   $this->EM;
        $step1          =   $serializer->deserialize($content, ActivityStep1::class, 'json');
        if(!$clientser->checkClientId($step1->getClient(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $chkclient      =   $clientrepo->findOneBy(['id'=>$step1->getClient()]);
        if(!$chkclient){
            return new ApiResponse([$step1->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
        }
        
        $siren=null;
        $legalform=null;
        $epa=null;
        $companyname=null;
        $caption=null;
        
        if($step1->getSiren()){
            $siren=$step1->getSiren();
        }
        if($step1->getCompanyName()){
            $companyname=$step1->getCompanyName();
        }
        if($step1->getCaption()){
            $caption=$step1->getCaption();
        }
        if($step1->getLegalForm()){
            $chklegalform   =   $legalrepo->findOneBy(['id'=>$step1->getLegalForm()]);
            if(!$chklegalform){
                return new ApiResponse([$step1->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid legalform");
            }
            $legalform=$chklegalform;
        }
        if($step1->getEpa()){
            $chkepa         =   $eparepo->findOneBy(['id'=>$step1->getEpa()]);
            if(!$chkepa){
                return new ApiResponse([$step1->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid Epa");
            }
            $epa=$chkepa;
        }
         
        $chkclient  ->  setSiren($siren);
        $chkclient  ->  setCompanynameClient($companyname);
        $chkclient  ->  setCaptionClient($caption);
        $chkclient  ->  setLegalform($legalform);
        $chkclient  ->  setEpa($epa);
        $entityManager  ->  persist($chkclient);
        if($step1->getDataFidArr()){
            $fidrepo  =  $this->EM->getRepository(RefFid::class);
            foreach($step1->getDataFidArr() as $fid){
                $reffid = $fid["fidId"];
                $yesOrNo = $fid["yesOrNo"];
                $reffiddata = $fidrepo->findOneBy(["id"=>$reffid]);
                if(!$reffiddata){
                     return new ApiResponse([$step1->getClient()],401,["Content-Type"=>"application/json"],'json',"invalid fid_id");
                }
                $datafidrepo  =  $this->EM->getRepository(DataFid::class);
                $datafid = $datafidrepo->findOneBy(["client"=>$chkclient,"fid"=>$reffiddata]);
                if(!$datafid){
                    $datafid = new DataFid;
                }
                $datafid->setClient($chkclient);
                $datafid->setFid($reffiddata);
                $datafid->setYesOrNo($yesOrNo);
                $entityManager -> persist($datafid);
            }
        }
        $box=$boxrepo->findOneBy(['client'=>$step1->getClient()]);
        if(!$box)
        {
            $box   =new Box;
        }
        $box->setClient($step1->getClient());
        $box->setBoxId($step1->getBoxId());
        $entityManager -> persist($box);

        $entityManager  ->  flush();
        return new ApiResponse([$step1],200,["Content-Type"=>"application/json"],'json',"success");
        
    }

     /**
     * @Route("/api/v3/scenario2/alreadyregistered/registered/activity/step2", name="scenariosesio2",methods={"POST"})
     */
    public function scenariosesio2_(ClientService $clientser,Request $request,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefCountryRepository $contryrepo,BoxRepository $boxrepo)
    {
        $encoders       =   [ new JsonEncoder()];
        $normalizers    =   [new ObjectNormalizer()];
        $serializer     =   new Serializer($normalizers ,$encoders);
        $content        =   $request->getContent();
        $entityManager  =   $this->EM;
        $step2          =   $serializer->deserialize($content, ActivityStep2::class, 'json');
        if(!$clientser->checkClientId($step2->getClient(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $chkclient      =   $clientrepo->findOneBy(['id'=>$step2->getClient()]);
        if(!$chkclient){  
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
        }
        $address=null;
        $zipcode=null;
        $city=null;
        $contry=null;
        if($step2->getAddress()){
            $address=$step2->getAddress();
        }
        if($step2->getZipcode()){
            $zipcode=$step2->getZipcode();
        }
        if($step2->getCity()){
            $city=$step2->getCity();
        }
        if($step2->getContry()){
            $contry         =   $contryrepo->findOneBy(['id'=>$step2->getContry()]);
            if(!$contry){
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid contry");
            }
        }
        $chkclient  ->  setAddressClient($address);
        $chkclient  ->  setZipcodeClient($step2->getZipcode());
        $chkclient  ->  setCityClient($step2->getCity());
        $chkclient  ->  setCountry($contry);
        $box=$boxrepo->findOneBy(['client'=>$step2->getClient()]);
        if(!$box)
        {
            $box   =new Box;
        }
        $box->setClient($step2->getClient());
        $box->setBoxId($step2->getBoxId());
        $entityManager  -> persist($box);
        $entityManager  ->  persist($chkclient);
        $entityManager  ->  flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");
    }

     /**
     * @Route("/api/v3/scenario2/alreadyregistered/registered/activity/step3", name="scenari2",methods={"POST"})
     */
    public function session3_(ClientService $clientser,Request $request,DataClientRepository $clientRepo,DataClientWhereclientRepository $dataclientwcRepo,
    DataClientWheresupplierRepository $dataclientwsRepo,DataCountryWhereclientRepository $datacountrywcRepo,
    DataCountryWheresupplierRepository $datacountrywsRepo,RefWhereclientRepository $refwcrepo,
    RefWheresupplierRepository $refwsrepo,RefCountryRepository $refcountryrepo,RefWhoclientRepository $whoclientrepo,BoxRepository $boxrepo)
    {
        $encoders      =  [ new JsonEncoder()];
        $normalizers   =  [new ObjectNormalizer()];
        $serializer    =  new Serializer($normalizers ,$encoders);
        $content       =  $request->getContent();
        $entityManager =  $this->EM;
        $step3         =  $serializer->deserialize($content, ActivityStep3::class, 'json');
        if(!$clientser->checkClientId($step3->getClient(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $chkclient     =  $clientRepo->findOneBy(['id'=>$step3->getClient()]);
        
        if(!$chkclient){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
        }
        
        $actdesc=null;
        $other_whereclient=false;
        $other_wheresupplier=false;
        $whoclient=null;
        $turnover=null;
        if($step3->getActdesc()){
            $actdesc=$step3->getActdesc();
        }
        if($step3->getTurnover()){
            $turnover=$step3->getTurnover();
        }
        if($step3->getWhoclient()){
            $chkwhoclient  =  $whoclientrepo->findOneBy(['id'=>$step3->getWhoclient()]);
            if(!$chkwhoclient){
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json',"invalid client_id");
            }
            $whoclient=$chkwhoclient;
        }
        if($step3->getOtherWheresupplier()){
            $other_wheresupplier=$step3->getOtherWheresupplier();
        }
        if($step3->getOtherWhereclient()){
            $other_whereclient=$step3->getOtherWhereclient();
        }
        $chkclient     ->  setActdescClient($actdesc);
        $chkclient     ->  setTurnoverClient($turnover);
        $chkclient     ->  setOtherWheresupplier($other_wheresupplier);
        $chkclient     ->  setOtherWhereclient($other_whereclient);
        $chkclient     ->  setWhoclient($whoclient);
        $entityManager ->  persist($chkclient);
      
        if($step3 ->  getWhereClientList()!=[]){
        //Removing all countries
        $datacounwhereclient =  $datacountrywcRepo -> findBy(["client"=>$chkclient]);
       
        if($datacounwhereclient){
     

         foreach($datacounwhereclient as $data){
           
             $entityManager -> remove($data);
             $entityManager -> flush();
             }
        }
        if($step3 ->  getWhereClientList() ){
            foreach($step3 ->  getWhereClientList() as $clientlist ){
                $datawhereclient     =  $dataclientwcRepo  -> findBy(["client"=>$chkclient]);
                
                    foreach($datawhereclient as $data){
                             $entityManager  ->  remove($data);
                             $entityManager  ->  flush();
                                
                }
            }
        }
        
        // Updating data where client
        foreach($step3 ->  getWhereClientList() as $clientlist ){
            // return new ApiResponse($clientlist,400,["Content-Type"=>"application/json"],'json',"Invalid input..... ");
            $wc   =  $refwcrepo->findOneBy(['id'=>$clientlist]);
           if(!$wc){
           return new ApiResponse($wc,400,["Content-Type"=>"application/json"],'json',"Invalid input..... ");
           }
           $dwc  =  new DataClientWhereclient();
           $dwc  -> setClient($chkclient);
           $dwc  -> setWhereclient($wc);
           $entityManager  ->  persist($dwc);
           $entityManager -> flush();
        }
    }
    if($step3 ->  getWhereClientList()==[]){
        $datacounwhereclient =  $datacountrywcRepo -> findBy(["client"=>$chkclient]);
        if($datacounwhereclient){
 

            foreach($datacounwhereclient as $data){
              
                $entityManager -> remove($data);
                $entityManager -> flush();
                }
           }

     
            $datawhereclient     =  $dataclientwcRepo  -> findBy(["client"=>$chkclient]);
            
                foreach($datawhereclient as $data){
                         $entityManager  ->  remove($data);
                         $entityManager  ->  flush();
                            
            }
        
    }

      
    if($step3  ->  getWhereSupplierList()!=[]){

        //Remove other where country supplier
        $datacounwheresupplier =  $datacountrywsRepo->findBy(["client"=>$chkclient]);

        if($datacounwheresupplier){
         foreach($datacounwheresupplier as $data){
             $entityManager  ->  remove($data);
             $entityManager  ->  flush();
             
             }
        }
        if($step3  ->  getWhereSupplierList()){
            foreach($step3  ->  getWhereSupplierList() as $supplierlist ){
                $datawheresupplier     =  $dataclientwsRepo->findBy(["client"=>$chkclient]);
                foreach($datawheresupplier as $data){
                    $entityManager  ->  remove($data);
                    $entityManager  ->  flush();
                    }
            }
        }
        if($step3  ->  getWhereSupplierList()==[]){
            foreach($step3  ->  getWhereSupplierList() as $supplierlist ){
                $datawheresupplier     =  $dataclientwsRepo->findBy(["client"=>$chkclient]);
                foreach($datawheresupplier as $data){
                    $entityManager  ->  remove($data);
                    $entityManager  ->  flush();
                    }
            }
        }
        foreach($step3  ->  getWhereSupplierList() as $supplierlist )
        {
           
           // $list = $step1 = $serializer->deserialize( json_encode($supplierlist,JSON_NUMERIC_CHECK ), Step2::class, 'json');
           $ws   =$refwsrepo->findOneBy(['id'=>$supplierlist]);
           if(!$ws){
           return new ApiResponse($supplierlist,400,["Content-Type"=>"application/json"],'json',"Invalid input");
           }
             $dws  =   new DataClientWheresupplier();
             $dws  ->  setClient($chkclient);
             $dws  ->  setWheresupplier($ws);
             $entityManager  ->  persist($dws);
             $entityManager -> flush();        

        }
    }
    if($step3  ->  getWhereSupplierList()==[]){
        $datacounwheresupplier =  $datacountrywsRepo->findBy(["client"=>$chkclient]);

        if($datacounwheresupplier){
         foreach($datacounwheresupplier as $data){
             $entityManager  ->  remove($data);
             $entityManager  ->  flush();
             
             }
        }

        $datawheresupplier     =  $dataclientwsRepo->findBy(["client"=>$chkclient]);
        foreach($datawheresupplier as $data){
            $entityManager  ->  remove($data);
            $entityManager  ->  flush();
            }

    }
 
        if($step3  ->  getOtherWhereclient()==true){
            if($step3  ->  getOtherWCCountryList()==[]){
                $datacounwhereclient  =  $datacountrywcRepo ->  findBy(["client"=>$chkclient]);
                foreach($datacounwhereclient as $data){
                    $entityManager  ->  remove($data);
                    $entityManager  ->  flush();
                    }
                }
            if($step3  ->  getOtherWCCountryList()!=[]){
            $datacounwhereclient  =  $datacountrywcRepo ->  findBy(["client"=>$chkclient]);
            foreach($datacounwhereclient as $data){
                $entityManager  ->  remove($data);
                $entityManager  ->  flush();
                }
          
        foreach($step3  ->  getOtherWCCountryList() as $clientcountrylist )
        {
        
           $wcc  =  $refcountryrepo  ->  findOneBy(['id'=>$clientcountrylist]);
         
           if(!$wcc){
           return new ApiResponse($wcc,400,["Content-Type"=>"application/json"],'json',"Invalid type contact");
           }
     
             $dwcc  =   new DataCountryWhereclient();
             $dwcc  ->  setClient($chkclient);
             $dwcc  ->  setCountry($wcc);
             $entityManager->persist($dwcc);
             $entityManager -> flush();
             
           
      

        }
    }

       }

    

        if($step3  ->  getOtherWheresupplier()==true){
            if($step3  ->  getOtherWSCountryList()==[]){
            
                $datacounwheresupplier =  $datacountrywsRepo ->  findBy(["client"=>$chkclient]);
                foreach($datacounwheresupplier as $data){
                    $entityManager  ->  remove($data);
                    $entityManager  ->  flush();
                    }  
                }
            if($step3  ->  getOtherWSCountryList()!=[]){
            
            $datacounwheresupplier =  $datacountrywsRepo ->  findBy(["client"=>$chkclient]);
            foreach($datacounwheresupplier as $data){
                $entityManager  ->  remove($data);
                $entityManager  ->  flush();
                }  
             
               foreach($step3  ->  getOtherWSCountryList() as $suppliercountrylist )
               {
                  $wcs  =  $refcountryrepo  ->  findOneBy(['id'=>$suppliercountrylist]);
                  if(!$wcs){
                  return new ApiResponse($wcs,400,["Content-Type"=>"application/json"],'json',"Invalid type country");
                  }
               
                  
                    $dwsc  =  new DataCountryWheresupplier();
                    $dwsc  -> setClient($chkclient);
                    $dwsc  -> setCountry($wcs);
                    $entityManager  ->  persist($dwsc);
                    $entityManager  ->  flush();
              
               }
            }
        }
        $box=$boxrepo->findOneBy(['client'=>$step3->getClient()]);
        if(!$box)
        {
            $box   =new Box;
        }
        $box->setClient($step3->getClient());
        $box->setBoxId($step3->getBoxId());
        $entityManager  -> persist($box);
        $entityManager  ->  flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");
             }
    // /**
    //  * @Route("/api/v3/emptyattorney/{client_id}", name="emptyAttorney",methods={"POST"})
    //  */
    // public function emptyAttorney(Request $request,DataClientRepository $clientrepo,$client_id){
  
    //     $clientchk        =   $clientrepo->findOneBy(['id'=>$client_id]);
    //     if(!$clientchk){
    //         return new ApiResponse([],404,["Content-Type"=>"application/json"],'json',"invalid client!!!!");
    //     } 
    //     $attony  =  new DataAttorney;
    //     $attony->setClient($clientchk);
    //     $entityManager  -> persist($attony);
    //     $entityManager  ->  flush();
    //     return new ApiResponse($attony->getId(),200,["Content-Type"=>"application/json"],'json',"success");
    // }

     /**
     * @Route("/api/v3/scenario2/alreadyregistered/registered/activity/step4", name="scenario2Step4",methods={"POST"})
     */
    public function scenario2Step4_(ClientService $clientser,Request $request,RefFunctionRepository $functionrepo,DataAttorneyRepository $attonyrepo,DataClientRepository $clientrepo,
                                   RefCivilityRepository $civilityrepo,RefCountryRepository $countryrepo,RefTypecontactRepository $typecontrepo,DataContactRepository $datacontactrepo,RefLegalformRepository $legalformrepo,BoxRepository $boxrepo){
  
  
        $encoders         =   [ new JsonEncoder()];
        $normalizers      =   [new ObjectNormalizer()];
        $serializer       =   new Serializer($normalizers ,$encoders);
        $content          =   $request->getContent();
        $entityManager    =   $this->EM;
        $step4            =   $serializer->deserialize($content, ActivityStep4::class, 'json');
        if(!$clientser->checkClientId($step4->getClient(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $clientchk        =   $clientrepo->findOneBy(['id'=>$step4->getClient()]);
        $attoneychk       =   $attonyrepo->findBy(['client'=>$step4->getClient(),'isshareholder_attorney'=>true,'active_attorney'=>'Active']);
        $newattorney      =   $attonyrepo->findOneBy(['client'=>$step4->getClient()]);
    
        $presentage=0;
        $remainingpresentage=0;
        if(!$clientchk){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json',"invalid client!!!!");
        }  
        $chkcivility           =  null;
        $chkfunction           =  null;
        $countrychk            =  null;
        $nationalitychk        =  null;
        $fiscalcountrychk      =  null;
        $chklegalform          =  null;
        $name_attorney         =  null;
        $surname_attorney      =  null;
        $birthname             =  null;
        $datebirth_attorney    =  null;
        $placebirth_attorney   =  null;
        $address_attorney      =  null;
        $zipcode_attorney      =  null;
        $city_attorney         =  null;
        // $american_attorney     =  null;
        $function              =  null;
        $percentage_attorney   =  null;
        $iscompany             =  false;
        $isrepresentative	   =  null;
        $fiscalnumber_attorney =  null;
        $is_receiving_share    =  null;
        $company_name          =  null;
        $register_no           =  null;
        $residancecountry      =  null;
        $siren_attorney        =  null;
        $ismandatory_attorney  =  false;
        if($step4  ->  getFiscalnumberAttorney()){
            $fiscalnumber_attorney     =   $step4  ->  getFiscalnumberAttorney();
        }
        if($step4  ->  getNameAttorney()){
            $name_attorney     =   $step4  ->  getNameAttorney();
        }
        if($step4  ->  getSurnameAttorney()){
            $surname_attorney     =   $step4  ->  getSurnameAttorney();
        }
        if($step4->getBirthName()){
            $birthname =  $step4->getBirthName();
        }
        if($step4  ->  getDatebirthAttorney()){
            $datebirth_attorney     =   $step4  ->  getDatebirthAttorney();
            $datebirth_attorney     =	new \DateTime($datebirth_attorney);
        }
        if($step4  ->  getPlacebirthAttorney()){
            $placebirth_attorney     =   $step4  ->  getPlacebirthAttorney();
        }
        if($step4  ->  getAddressAttorney()){
            $address_attorney     =   $step4  ->  getAddressAttorney();
        }
        if($step4  ->  getZipcodeAttorney()){
            $zipcode_attorney     =   $step4  ->  getZipcodeAttorney();
        }
        if($step4  ->  getCityAttorney()){
            $city_attorney     =   $step4  ->  getCityAttorney();
        }
        if($step4  ->  getFunction()){
            $function     =   $step4  ->  getFunction();
        }
        if($step4  ->  getPercentageAttorney()){
            $percentage_attorney     =   $step4  ->  getPercentageAttorney();
        }
        if($step4  ->  getIscompany()){
            $iscompany     =   $step4  ->  getIscompany();
        }
        if($step4  ->  getIsrepresentative() === true){
            $isrepresentative     =   $step4  ->  getIsrepresentative();
        } elseif ($step4  ->  getIsrepresentative() === false) {
			$isrepresentative     =   $step4  ->  getIsrepresentative();
		}
        if($step4  ->  getLegalform()){
            $chklegalform     =   $legalformrepo->findOneBy(['id'=>$step4->getLegalform()]);
        }
        if($step4  ->  getCivilityAttorney()){
            $chkcivility  =  $civilityrepo->findOneBy(['id'=>$step4->getCivilityAttorney()]);
        }
        if($step4  ->  getFunction()){
            $chkfunction  =  $functionrepo->findOneBy(['id'=>$step4->getFunction()]);
        }
        if($step4  ->  getCountrybirthAttorney()){
            $countrychk   =  $countryrepo->findOneBy(['id'=>$step4->getCountrybirthAttorney()]);
        }
        if($step4  ->  getNationalityAttorney()){
            $nationalitychk = $countryrepo->findOneBy(['id'=>$step4->getNationalityAttorney()]);
        }
        if($step4  ->  getFiscalcountryAttorney()){
            $fiscalcountrychk = $countryrepo->findOneBy(['id'=>$step4->getFiscalcountryAttorney()]);
        }  
        if($step4  ->getSirenAttorney()){
            $siren_attorney   = $step4  ->getSirenAttorney();
        }
        if($step4  ->getResidentcountryAttorney()){
            $residancecountry   =  $countryrepo->findOneBy(['id'=>$step4  ->getResidentcountryAttorney()]);
           
        }

        if(($step4 ->  getIscompany()==false)){
           
            $attony   = null;
            if($step4 -> getId()){
            
                $attony  =  $attonyrepo->findOneBy(['id'=>$step4->getId()]);
                $attopresentage=$attony   ->  getPercentageAttorney();
                foreach($attoneychk as $atto){
                    $presentage  =  $presentage+$atto->getPercentageAttorney();
                }
                $calcpresentage  =  $presentage-$attopresentage+$step4->getPercentageAttorney();

                $remainingpresentage=100-$calcpresentage;
               
            } 
            if(!$attony){
                $attony  =  new DataAttorney;
                foreach($attoneychk as $atto){
                    $presentage  =  $presentage+$atto->getPercentageAttorney();
                }
                $calcpresentage  =  $presentage+$step4->getPercentageAttorney();
                $remainingpresentage=100-$calcpresentage;
            }  
        $attony  ->  setCivilityAttorney($chkcivility);
        $attony  ->  setClient($clientchk);
        $attony  ->  setNameAttorney($name_attorney);
        $attony  ->  setSurnameAttorney($surname_attorney);
        $attony  ->  setBirthName($birthname);
        $attony  ->  setDatebirthAttorney($datebirth_attorney);
        $attony  ->  setPlacebirthAttorney($placebirth_attorney);
        $attony  ->  setCountrybirthAttorney($countrychk);
        $attony  ->  setNationalityAttorney($nationalitychk);
        $attony  ->  setZipcodeAttorney($zipcode_attorney);
        $attony  ->  setAddressAttorney($address_attorney);
        $attony  ->  setCityAttorney($step4->getCityAttorney());
        $attony  ->  setResidentcountryAttorney($residancecountry);
        $attony  ->  setFiscalcountryAttorney($fiscalcountrychk);
        $attony  ->  setAmericanAttorney($step4  ->  getAmericanAttorney());
        $attony  ->  setFunction($chkfunction);
        $attony  ->  setPercentageAttorney($percentage_attorney);
        $attony  ->  setIscompany($iscompany);
        $attony  ->  setIsrepresentative($isrepresentative);
        $attony  ->  setIsmandatoryAttorney($step4  ->  getIsmandatoryAttorney());
        $attony  ->  setIsshareholderAttorney(true);
        $attony  ->  setFiscalnumberAttorney($fiscalnumber_attorney);
        $attony  ->  setActiveAttorney("Active");
        $entityManager  ->  persist($attony);
        $entityManager  ->  flush();
        $chkdatacontact = $datacontactrepo->findBy(['client'=>$clientchk->getId(),'attorney'=>$attony->getId()]);
        if($step4  ->  getContacts()!=[]){
        foreach($step4  ->  getContacts() as $noncontact )
        {

            $contact      = $serializer->deserialize( json_encode($noncontact),Contact1::class, 'json');
            if($contact->getTypeContact()==13){
                continue;
            }
            $utilContacts = new Contact1();
           
                $tc           = $typecontrepo->find($contact->getTypeContact());
                if(!$tc){                                      
                return new ApiResponse( $tc,400,["Content-Type"=>"application/json"],'json',"Invalid type contact");
                }
                $telecode=null;
                if($contact->getTelecode()){
                    $telecode=$contact->getTelecode();
                }
                 
              $dataContact = $datacontactrepo->findOneBy(['client'=>$clientchk->getId(),'attorney'=>$attony->getId(),'type_contact'=>$tc]);
                 if(!$dataContact){
                    $dataContact  =  new DataContact();
                 }
                  $dataContact ->  setClient($clientchk);
                  $dataContact ->  setTypeContact($tc);
                  $dataContact ->  setAttorney($attony);
                  $dataContact ->  setValueContact($contact->getValueContact());
                  $dataContact   -> setTelecode($telecode);
                  $entityManager->persist($dataContact);
                  $entityManager  ->  flush();
           
        }
    }
         $box=$boxrepo->findOneBy(['client'=>$step4->getClient()]);
         if(!$box)
         {
             $box   =new Box;
         }
         $box->setClient($step4->getClient());
         $box->setBoxId($step4->getBoxId());
         $entityManager  -> persist($box);

        $entityManager  ->  flush();

        $attoneychk1    =   $attonyrepo  -> findBy(array('client'=>$step4->getClient(),'isshareholder_attorney'=>true,'active_attorney'=>'Active'),array('id' => 'ASC'));
        if(!$newattorney){
            $remainingpresentage=100-$step4->getPercentageAttorney();                 
        }
        return new ApiResponse($attoneychk1,200,["Content-Type"=>"application/json"],'json',"hi",['client','timezone'],$remainingpresentage);
    }
    else{
        if($step4  ->getIsReceivingShare()){
            $is_receiving_share   =  $step4  ->getIsReceivingShare();
           
        }
        if($step4  ->getCompanyName()){
            $company_name   =  $step4  ->getCompanyName();
           
        }
        if($step4  ->getRegisterNo()){
            $register_no   = $step4  ->getRegisterNo();
           
        }
            $attony   =  null;
            if($step4 -> getId()){
            
                $attony  =  $attonyrepo->findOneBy(['id'=>$step4->getId()]);
                $attopresentage=$attony   ->  getPercentageAttorney();
                foreach($attoneychk as $atto){
                    $presentage  =  $presentage+$atto->getPercentageAttorney();
                }
                $calcpresentage  =  $presentage-$attopresentage+$step4->getPercentageAttorney();
                $remainingpresentage=100-$calcpresentage;
            } 
            if(!$attony){
                $attony  =  new DataAttorney;
                foreach($attoneychk as $atto){
                    $presentage  =  $presentage+$atto->getPercentageAttorney();
                }
                $calcpresentage  =  $presentage+$step4->getPercentageAttorney();
                $remainingpresentage=100-$calcpresentage;
            }     
            $attony   ->  setClient($clientchk);
            $attony   ->  setCivilityAttorney($chkcivility);
            $attony   ->  setNameAttorney($step4->getNameAttorney());
            $attony   ->  setPercentageAttorney($step4->getPercentageAttorney());
            $attony   ->  setIscompany(true);
            $attony   ->  setNationalityAttorney($nationalitychk);
            $attony   ->  setLegalform($chklegalform);
            $attony   ->  setNameAttorney($name_attorney);
            $attony   ->  setSurnameAttorney($surname_attorney);
            $attony   ->  setBirthName($birthname);
            $attony   ->  setDatebirthAttorney($datebirth_attorney);
            $attony   ->  setCountrybirthAttorney($countrychk);
            $attony   ->  setAmericanAttorney($step4  ->  getAmericanAttorney());
            $attony   ->  setFiscalcountryAttorney($fiscalcountrychk);
            $attony   ->  setSirenAttorney($siren_attorney);
            $attony   ->  setResidentcountryAttorney($residancecountry);
            $attony   ->  setIsReceivingShare($is_receiving_share);
            $attony   ->  setCompanyName($company_name);
            $attony   ->  setRegisterNo($register_no);
            $attony   ->  setFiscalnumberAttorney($fiscalnumber_attorney);
            $attony   ->  setPlacebirthAttorney($placebirth_attorney);
            $attony   ->  setActiveAttorney("Active");
            $attony   ->  setIsmandatoryAttorney($step4  ->  getIsmandatoryAttorney());
            $attony   ->  setIsshareholderAttorney(true);
          
            $entityManager  ->  persist($attony);
            $box=$boxrepo->findOneBy(['client'=>$step4->getClient()]);
            if(!$box)
            {
                $box   =new Box;
            }
            $box->setClient($step4->getClient());
            $box->setBoxId($step4->getBoxId());
            $entityManager  -> persist($box);
            $entityManager  ->  flush();
            $attoneychk1    =   $attonyrepo  ->  findBy(array('client'=>$step4->getClient(),'isshareholder_attorney'=>true),array('id' => 'ASC'));
            if(!$newattorney){
                $remainingpresentage=100-$step4->getPercentageAttorney();                 
            }
            return new ApiResponse($attoneychk1,200,["Content-Type"=>"application/json"],'json',"success",['client','timezone'],$remainingpresentage);
    }

    }
     /**
     * @Route("/api/attorny/{siren}", name="getattony",methods={"GET"})
     */
    public function getattony_($siren,DataClientRepository $clientrepo,Request $request){
        $encoders      =  [new JsonEncoder()];
        $normalizer    =  new ObjectNormalizer();  
        $normalizers =    array($normalizer);
        $serializer  =    new Serializer($normalizers ,$encoders);
        $content     =    $request->getContent();
        $res         =    array();
        $client      =    $clientrepo->findOneBy(['siren'=>$siren]);
        return new ApiResponse($client,200,["Content-Type"=>"application/json"],'json',"success",["datebirthAttorney","dataUserspace","dateupdRequest","dataRequest","dataAttorneys"]);
    }

     /**
     * @Route("/api/shareholder/{client_id}", name="getshareholder",methods={"GET"})
     */
    public function getshareholder_($client_id,DataAttorneyRepository $attonyrepo,Request $request,DataContactRepository $datacontactrepo,DataRequestFileRepository $datareqfilerepo,MasterService $masterService, ClientService $clientser){

        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $shareholders =   $attonyrepo -> findBy(['client'=>$client_id,'isshareholder_attorney'=>true], ['id' => 'ASC']);
        if(!$shareholders){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"client id is invalid");
        }
        $contacts=array();
        // $attornies=array();
        // foreach($shareholders as $shareholder){
        //     array_push($contacts,$datacontact);
        // }
        
        foreach($shareholders as $shareholder){
            $dob=null;
            $placebirth=null;
            $countrybirth=null;
            if($shareholder->getDatebirthAttorney())
            {
                $dob=$shareholder->getDatebirthAttorney();
                $dob=$dob->getTimeStamp();
                $dob=date("d/m/Y",$dob);
            }
            if($shareholder->getPlacebirthAttorney()){
                $placebirth = $shareholder->getPlacebirthAttorney();
            }
            // return new ApiResponse($dob,200,["Content-Type"=>"application/json"],'json',"success",["client",'timezone','request']);

            $datacontact =  new Contact();
            $email       =  $datacontactrepo  ->  findOneBy(['type_contact'=>2,'attorney'=>$shareholder->getId()]);
            $phone       =  $datacontactrepo  ->  findOneBy(['type_contact'=>4,'attorney'=>$shareholder->getId()]);
            $phonefix    =  $datacontactrepo  ->  findOneBy(['type_contact'=>13,'attorney'=>$shareholder->getId()]);
            if($email){  
                $datacontact  -> setEmail($email->getValueContact());
            }
            if($phone){
                $datacontact  -> setPhone($phone->getValueContact());
                $datacontact  -> setTelecode($phone->getTelecode());
            }
            if($phonefix){
                $datacontact  -> setPhonefix($phonefix->getValueContact());  
                $datacontact  -> setTelecodeFix($phonefix->getTelecode());      
            }
            array_push($contacts,$datacontact);
            $shareholder  ->  setContact($contacts);
            $file= $datareqfilerepo->findOneBy(['attorney'=>$shareholder->getId()]);
            if(!$file){
                $file=null;
            }
            $shareholder->setFile($file);  
        // return new ApiResponse($file,200,["Content-Type"=>"application/json"],'json',"success",["client",'timezone']);
            $lang='fr';
            $contacts=array();
            if($shareholder->getCivilityAttorney()){
                $civilityid = $shareholder->getCivilityAttorney()->getId();   
                $shareholder->setCivilityAttorney($masterService->getSingleCivility($lang,$civilityid));
            }
            if($shareholder->getCountrybirthAttorney()){
                $countryid = $shareholder->getCountrybirthAttorney()->getId();   
                $shareholder->setCountrybirthAttorney($masterService->getSingleCountry($lang,$countryid));
                $countrybirth = $shareholder->getCountrybirthAttorney()->getRefLabel();         
            }
            if($shareholder->getNationalityAttorney()){
                $countryid = $shareholder->getNationalityAttorney()->getId();   
                $shareholder->setNationalityAttorney($masterService->getSingleCountry($lang,$countryid));
            }
            if($shareholder->getResidentcountryAttorney()){
                $countryid = $shareholder->getResidentcountryAttorney()->getId();   
                $shareholder->setResidentcountryAttorney($masterService->getSingleCountry($lang,$countryid));
            }
            if($shareholder->getFiscalcountryAttorney()){
                $countryid = $shareholder->getFiscalcountryAttorney()->getId();   
                $shareholder->setFiscalcountryAttorney($masterService->getSingleCountry($lang,$countryid));
            }   
            if($shareholder->getFunction()){
                $functionid = $shareholder->getFunction()->getId();   
                $shareholder->setFunction($masterService->getSingleFunction($lang,$functionid));
            }    
                
            $addrassstring = $this->checkField($shareholder->getAddressAttorney());
            $zipcodestring = $this->checkField($shareholder->getZipcodeAttorney());
            $citystring = $this->checkField($shareholder->getCityAttorney());
            $countrystring = $this->checkField($shareholder->getResidentcountryAttorney());
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
            if($addresses[0]!=null&&$shareholder->getZipcodeAttorney()!=null&&$shareholder->getCityAttorney()!=null&&$shareholder->getResidentcountryAttorney()!=null){
                $shareholder->setlrValidationAddress(true);    
            }
            else{
                $shareholder->setlrValidationAddress(false);    
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
                $shareholder->setlrValidationAddress(null);
            }
            // $string =$astring."-".$zipcodestring."-".$citystring."-".$countrystring;
            $strdob='Néle'.'-'.$dob.'-'.'à'.'-'.$placebirth.'-'.'('.$countrybirth.')';
            $shareholder->setlrStringAddress($string);
            $shareholder->setStrDob($strdob);
                    
            }
        
        return new ApiResponse($shareholders,200,["Content-Type"=>"application/json"],'json',"success",["client",'timezone','request']);
    }
    public function checkField($variable){
        if($variable==null){
            $variable="";
            return $variable;
        }
        return $variable;
    }
     /**
     * @Route("/api/deleteshareholder/{client_id}", name="deleteshareholder",methods={"PUT"})
     */
    public function deleteshareholder(ClientService $clientser,$client_id,DataAttorneyRepository $attonyrepo,Request $request,DataContactRepository $datacontactrepo,DataRequestFileRepository $datareqfilerepo,MasterService $masterService){
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $entityManager    =   $this->EM;
        $shareholders =   $attonyrepo -> findBy(['client'=>$client_id]);
        if(!$shareholders){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"client id is invalid");
        }
        foreach($shareholders as $shareholder){
            $shareholder->setIsshareholderAttorney(false);
            $entityManager  -> persist($shareholder);
            $entityManager  ->  flush();
        }
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"update successfully");

    }

     /**
     * @Route("/api/enableshareholder/{client_id}", name="enableshareholder",methods={"PUT"})
     */
    public function enableshareholder($client_id,DataAttorneyRepository $attonyrepo,Request $request,DataContactRepository $datacontactrepo,DataRequestFileRepository $datareqfilerepo,MasterService $masterService){
        $entityManager    =   $this->EM;
        $shareholders =   $attonyrepo -> findBy(['client'=>$client_id]);
        if(!$shareholders){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"client id is invalid");
        }
        foreach($shareholders as $shareholder){
            $shareholder->setIsshareholderAttorney(true);
            $entityManager  -> persist($shareholder);
            $entityManager  ->  flush();
        }
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"update successfully");

    }

     /**
     * @Route("/api/checkcompany/{legalform_id}", name="findcompany",methods={"GET"})
     */
    public function findcompany_($legalform_id,RefLegalformRepository $legalformrepo,Request $request){
        $encoders      =  [new JsonEncoder()];
        $normalizer    =  new ObjectNormalizer();
        $normalizers   = array($normalizer);
        $serializer    = new Serializer($normalizers ,$encoders);
        $content       = $request->getContent();
        $res           = array();
        $legalform     = $legalformrepo->findOneBy(['id'=>$legalform_id]);
        if(!$legalform){
            return new ApiResponse($legalform,400,["Content-Type"=>"application/json"],'json',"invalid id");
        }
        $id  =  $legalform->getIdCompany()->getId();
        if($id==1){
            return new ApiResponse($id,200,["Content-Type"=>"application/json"],'json',"success",["client","datebirthAttorney"]);
        }
        $id  =  2;
        return new ApiResponse($id,200,["Content-Type"=>"application/json"],'json',"success",["client","datebirthAttorney"]);
    }



    //  /**
    //  * @Route("/api/iscompany", name="isComapany",methods={"POST"})
    //  */
    // public function isComapany(Request $request,DataClientRepository $clientrepo,RefFunctionRepository $functionrepo,DataAttorneyRepository $attonyrepo,RefCountryRepository $countryrepo){


    //     $encoders      =   [ new JsonEncoder()];
    //     $normalizers   =   [new ObjectNormalizer()];
    //     $serializer    =   new Serializer($normalizers ,$encoders);
    //     $content       =   $request->getContent();
    //     $entityManager =   $this->EM;
    //     $iscompany     =   $serializer->deserialize($content, IsCompany::class, 'json');
    //     $client        =   $clientrepo->findOneBy(['id'=>$iscompany->getClient()]);
    //     $clientchk     =   $attonyrepo->findOneBy(['client'=>$iscompany->getClient(),'siren_attorney'=>$iscompany->getSirenAttorney()]);
    //     $chkfunction   =   $functionrepo->findOneBy(['id'=>$iscompany->getFunction()]);
    //     $chknational   =   $countryrepo->findOneBy(['id'=>$iscompany->getNationalityAttorney()]);
    //     if(!$client){
    //         return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"client id is invalid");
    //     }
    //     if(!$clientchk){
    //        $dataattorny   =  new DataAttorney;
    //        $dataattorny   -> setClient($client);
    //        $dataattorny   -> setSirenAttorney($iscompany->getSirenAttorney());
    //        $dataattorny   -> setFunction($chkfunction);
    //        $dataattorny   -> setPercentageAttorney($iscompany->getPercentageAttorney());
    //        $dataattorny   -> setNationalityAttorney($chknational);
    //        $dataattorny   -> setIscompany($iscompany->getIscompany());
    //        $dataattorny   -> setIsshareholderAttorney(true);
    //        $entityManager -> persist($dataattorny);
    //        $entityManager -> flush();
    //        $allattony     =  $attonyrepo->findBy(['client'=>$iscompany->getClient()]);
    //        return new ApiResponse($allattony,200,["Content-Type"=>"application/json"],'json',"Success",['client']);
    //     }
    //     if(!$chkfunction){
    //         return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"function id is invalid");
    //     }
    //     if(!$chknational){
    //         return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"nationality id is invalid");
    //     }
    //     $clientchk  ->  setSirenAttorney($iscompany->getSirenAttorney());
    //     $clientchk  ->  setFunction($chkfunction);
    //     $clientchk  ->  setPercentageAttorney($iscompany->getPercentageAttorney());
    //     $clientchk  ->  setNationalityAttorney($chknational);
    //     $clientchk  ->  setIscompany($iscompany->getIscompany());
    //     $allattony  =   $attonyrepo->findBy(['client'=>$iscompany->getClient()]);
    //     return new ApiResponse($allattony,200,["Content-Type"=>"application/json"],'json',"Success",['client']);
    // }

    // /**
    //  * @Route("/api/create/datarequest_requeststatus", name="datarequest_requeststatus",methods={"POST"})
    //  */
    // public function datarequest_requeststatus(Request $request,DataReq_ReqstatusService $datareqreq){
    //     $encoders         = [ new JsonEncoder()];
    //     $normalizers      = [new ObjectNormalizer()];
    //     $serializer       = new Serializer($normalizers ,$encoders);
    //     $content          = $request->getContent();
    //     $entityManager    = $this->EM;
    //     $datareqreqstatus = $serializer->deserialize($content, DataRequestRequststatus::class, 'json');
    //     $data  =  $datareqreq ->create($datareqreqstatus,$entityManager);
    //     return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"updated successfully");


    // }

    /**
     * @Route("/api/create/datarequest_statuswithcode", name="datarequestStatusWithCode",methods={"POST"})
     */
    public function datarequestStatusWithCode(EloquaService $eloquaservice,Request $request, DataReq_ReqstatusService $datareqreq, IntegrationService $integration, ApiHelper $api, DataClientRepository $clientrepo, DataAttorneyRepository $attorneyrepo, DataUserspaceRepository $userspacerepo,  DataContactRepository $datacontrepo, OptionsTabelRepository $prodrepo, DataClientSabRepository $sabrepo, ContraliaService $contralia, EmailServiceV1 $es, DocumentService $ds){
        $encoders         = [new JsonEncoder()];
        $normalizers      = [new ObjectNormalizer()];
        $serializer       = new Serializer($normalizers ,$encoders);
        $content          = $request->getContent();
        $entityManager    = $this->EM;
        $datareqreqstatus = $serializer->deserialize($content, DataRequestRequststatus::class, 'json');

        $data  =  $datareqreq->createWithCode($eloquaservice,$datareqreqstatus, $entityManager, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $es, $ds);

        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"updated successfully");
    }

    //  /**
    //  * @Route("/api/get/companylegalform", name="companylegalform1",methods={"GET"})
    //  */
    // public function companyLegalform1(RefLegalformRepository $legalformrepo,Request $request){
    //     $encoders    =  [new JsonEncoder()];
    //     $normalizer  =  new ObjectNormalizer();
    //     $normalizer  -> setCircularReferenceLimit(2);
    //     $normalizer  -> setCircularReferenceHandler(function ($object) {
    //     return $object->getId();
    //     });
    //     $normalizers = array($normalizer);
    //     $serializer  = new Serializer($normalizers ,$encoders);
    //     $content     = $request->getContent();
    //     $res         = array();
    //     $legalform   = $legalformrepo->findBy(['id_company'=>1]);
    //     if(!$legalform){
    //         return new ApiResponse($legalform,400,["Content-Type"=>"application/json"],'json',"invalid id");
    //     }
    //     return new ApiResponse($legalform,200,["Content-Type"=>"application/json"],'json',"success");
        
    // }

    /**
     * @Route("/api/get/mandatoryattornydetail/{client_id}", name="mandatoryattonydetail",methods={"GET"})
     */
    public function mandatoryAttonyDetail(DataAttorneyRepository $dataattonyrepo,Request $request,$client_id, ClientService $clientser){
        $encoders     =  [new JsonEncoder()];
        $normalizer   =  new ObjectNormalizer();
        $normalizers =  array($normalizer);
        $serializer  =  new Serializer($normalizers ,$encoders);
        $content     =  $request->getContent();
        $res         =  array();
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $mandatoryattorny  =  $dataattonyrepo->findOneBy(['client'=>$client_id,'ismandatory_attorney'=>true]);
        $respData    =  "";
        if($mandatoryattorny){$respData= $mandatoryattorny->getId();}else{ $respData= null;}
        return new ApiResponse($respData,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client']);        
    }

    /**
     * @Route("/api/v3/scenario2/finalsubmit/{client_id}", name="finalSubmit",methods={"PUT"})
     */
    public function finalSubmit_(Request $request,DataRequestRepository $datareqrepo,RefRequeststatusRepository $reqstarepo, $client_id, DataUserSpaceRepository $userspacerepo, EloquaService $eloquaservice, ApiHelper $api, ContraliaService $contralia, DataFieldIssueRepository $dataissuerepo) {

        $encoders      =  [ new JsonEncoder()];
        $normalizers   =  [new ObjectNormalizer()];
        $serializer    =  new Serializer($normalizers ,$encoders);
        $content       =  $request->getContent();
        $entityManager =  $this->EM;

       
        $requeststatus =  $datareqrepo->findOneBy(['client'=>$client_id]);
        if(!$requeststatus){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"client_id invalid"); 
        }

        if ($requeststatus->getRequeststatus() !== null) {
			if ($requeststatus->getRequeststatus()->getId() == 3)
				$statusdata    =  $reqstarepo->findOneBy(['id'=>4]);
			else 
                $statusdata    =  $reqstarepo->findOneBy(['id'=>6]);
                
                $dataissues = $dataissuerepo->findBy(array('client' => $client_id));
                foreach($dataissues as $dataissue){
                    $dataissue->setClientCorrection(true);
                    $entityManager->persist($dataissue);
                    $entityManager->flush();
                }

		} else {
			$statusdata    =  $reqstarepo->findOneBy(['id'=>4]);
        }

        $datarequestchk = $datareqrepo->findOneBy(['id' => $client_id]);
        if(!$datarequestchk){
            return 'datarequest id is invalid';  
        }

        $step = $statusdata->getStep();

        if($statusdata->getEloqua() == 1) {
            $data = $eloquaservice->eloquaCall($datarequestchk,$userspacerepo,$step);
        }

        $reqreqstatus=new DataRequestRequeststatus();
        $requeststatus -> setRequeststatus($statusdata);
        $reqreqstatus->setIdRequest($requeststatus);
        $reqreqstatus->setIdRequeststatus($statusdata);
        $reqreqstatus->setDateRequestRequeststatus(new \DateTime());
        $entityManager -> persist($reqreqstatus);
        $entityManager -> persist($requeststatus);
        $entityManager -> flush();

        $contralia->setBankSignature($api, $client_id);
        $contralia->terminate($api, $client_id);
        $contralia->documentDownload($api, $client_id, $entityManager);

        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");     
    }

    /**
     * @Route("/api/v3/scenario2/docfinalsubmit/{client_id}", name="docfinalSubmit",methods={"PUT"})
     */
    public function docfinalSubmit_(Request $request,DataRequestRepository $datareqrepo,RefRequeststatusRepository $reqstarepo, $client_id, DataUserSpaceRepository $userspacerepo, EloquaService $eloquaservice, ApiHelper $api, ContraliaService $contralia, DataFieldIssueRepository $dataissuerepo) {

        $encoders      =  [ new JsonEncoder()];
        $normalizers   =  [new ObjectNormalizer()];
        $serializer    =  new Serializer($normalizers ,$encoders);
        $content       =  $request->getContent();
        $entityManager =  $this->EM;

       
        $requeststatus =  $datareqrepo->findOneBy(['client'=>$client_id]);
        if(!$requeststatus){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"client_id invalid"); 
        }

		$statusdata    =  $reqstarepo->findOneBy(['id'=>6]);
		
		$dataissues = $dataissuerepo->findBy(array('client' => $client_id));
		foreach($dataissues as $dataissue){
			$dataissue->setClientCorrection(true);
			$entityManager->persist($dataissue);
			$entityManager->flush();
		}

        $datarequestchk = $datareqrepo->findOneBy(['id' => $client_id]);
        if(!$datarequestchk){
            return 'datarequest id is invalid';  
        }

        $step = $statusdata->getStep();

        if($statusdata->getEloqua() == 1) {
            $data = $eloquaservice->eloquaCall($datarequestchk,$userspacerepo,$step);
        }

        $reqreqstatus=new DataRequestRequeststatus();
        $requeststatus -> setRequeststatus($statusdata);
        $reqreqstatus->setIdRequest($requeststatus);
        $reqreqstatus->setIdRequeststatus($statusdata);
        $reqreqstatus->setDateRequestRequeststatus(new \DateTime());
        $entityManager -> persist($reqreqstatus);
        $entityManager -> persist($requeststatus);
        $entityManager -> flush();

        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"success");     
    }

    /**
     * @Route("/api/get/email_id/{client_id}", name="getEmail",methods={"GET"})
     */
    public function getEmail_(DataRequestRepository $datareqrepo,DataUserspaceRepository $userspacerepo,Request $request,$client_id, ClientService $clientser){
 
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        
        $datarequest = $datareqrepo->findOneBy(['client'=>$client_id]);
        if(!$datarequest){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"invalid client id");        
        }
        $email    = $userspacerepo->findOneBy(['id_request'=>$datarequest->getId()]);
        $email_id = null;
        if($email){
            $email_id=$email -> getEmailUs();
        }
        return new ApiResponse($email_id,200,["Content-Type"=>"application/json"],'json',"success");        
    }


     /**
     * @Route("/api/attornyDetail/{id}", name="getAttornyDetail",methods={"GET"})
     */
    public function getAttornyDetail_($id,DataAttorneyRepository $attonyrepo,Request $request,DataContactRepository $datacontactrepo,DataRequestFileRepository $datareqfilerepo, ClientService $clientser){
        $encoders      =  [new JsonEncoder()];
        $normalizer    =  new ObjectNormalizer();   
        $normalizers  =   array($normalizer);
        $serializer   =   new Serializer($normalizers ,$encoders);
        $content      =   $request -> getContent();
        $res          =   array();
        if(!$clientser->checkAttorneyId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $shareholder =   $attonyrepo -> findOneBy(['id'=>$id]);
        $datacontacts=$datacontactrepo->findBy(['client'=>$shareholder->getClient(),'attorney'=>$shareholder->getId()]);
        $contact=array();
            $datacontact =  new Contact();
            $email       =  $datacontactrepo  ->  findOneBy(['type_contact'=>2,'attorney'=>$shareholder->getId()]);
            $phone       =  $datacontactrepo  ->  findOneBy(['type_contact'=>4,'attorney'=>$shareholder->getId()]);
            $phonefix    =  $datacontactrepo  ->  findOneBy(['type_contact'=>13,'attorney'=>$shareholder->getId()]);
            // return $phone;
                if($email){  
                $datacontact  -> setEmail($email->getValueContact());
                }
                if($phone){
                $datacontact  -> setPhone($phone->getValueContact());
                $datacontact  -> setTelecode($phone->getTelecode());
                }
                if($phonefix){
                $datacontact  -> setPhonefix($phonefix->getValueContact());  
                $datacontact  -> setTelecodeFix($phonefix->getTelecode());      
                }
            array_push($contact,$datacontact);
        
        $shareholder->setContact($contact);
        $file= $datareqfilerepo->findOneBy(['attorney'=>$shareholder->getId()]);
        if(!$file){
           $file=null;
        }
        $shareholder->setFile($file);
        return new ApiResponse($shareholder,200,["Content-Type"=>"application/json"],'json',"success",['client','timezone','request']);        

    }


     /**
     * @Route("/api/delete/shareolder/{id}", name="deleteShareholder",methods={"PUT"})
     */
    public function deleteShareholder_(ClientService $clientser,DataAttorneyRepository $dataattonyrepo,Request $request,$id){
        $encoders     =  [new JsonEncoder()];
        $normalizer   =  new ObjectNormalizer();
        $normalizers =  array($normalizer);
        $serializer  =  new Serializer($normalizers ,$encoders);
        $content     =  $request->getContent();
        $entityManager    = $this->EM;
        $res         =  array();
        $presentage=0;
        $remainingpresentage=0;
        if(!$clientser->checkAttorneyId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $dataattorney  =  $dataattonyrepo->findOneBy(['id'=>$id,'isshareholder_attorney'=>true,'active_attorney'=>'Active']);
        // return new ApiResponse(  $dataattorney,401,["Content-Type"=>"application/json"],'json',"presentage is exist",$remainingpresentage);
        if(!$dataattorney){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"invalid attorney id",['timezone','client']);        
        }
        $client =$dataattorney->getClient();
        $attoneychk  =  $dataattonyrepo->findBy(['client'=>$client,'isshareholder_attorney'=>true,'active_attorney'=>'Active']);

        
        foreach($attoneychk as $atto){
            $presentage  =  $presentage+$atto->getPercentageAttorney();
        }
        $attopresentage=$dataattorney->getPercentageAttorney();
        $calcpresentage  =  $presentage-$attopresentage;
        $remainingpresentage=100-$calcpresentage;

        $mandatoryattorny  =  $dataattonyrepo->findOneBy(['id'=>$id,'ismandatory_attorney'=>true,'isshareholder_attorney'=>true]);
        if($mandatoryattorny){
            $mandatoryattorny->setIsshareholderAttorney(false);  
        }
        else{
            $mandatoryattorny  =  $dataattonyrepo->findOneBy(['id'=>$id]);
            $mandatoryattorny->setActiveAttorney("Deleted");
            $mandatoryattorny->setIsshareholderAttorney(false);
        }
        $entityManager -> persist($mandatoryattorny);
        $entityManager -> flush();
        $mandatoryattorny  =  $dataattonyrepo->findBy(array('client'=>$mandatoryattorny->getClient(),'isshareholder_attorney'=>true),array('id' => 'ASC'));
       
        // $respData    =  "";
        // if($mandatoryattorny){$respData= $mandatoryattorny->getId();}else{ $respData= null;}
        return new ApiResponse($mandatoryattorny,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client'],$remainingpresentage);        
    }


    /**
     * @Route("/api/legalform/socity", name="getSocity")
     */
    public function getSocity_(RefLegalformRepository $legalrepo)
    {
     
        $legalarray = $legalrepo->findBy(['id_company'=>1,'active_legalform'=>"Active"],array('desc_legalform'=>'ASC'));
        return new ApiResponse($legalarray,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client']);        

       
    }


    
    //  /**
    //  * @Route("/api/getclient/detail", name="getClientSampleDetail",methods={"GET"})
    //  */
    // public function getClientSampleDetail(DataClientRepository $clientrepo){
    //      $datas=$clientrepo->findAll();
    //      $client=array();
    //      foreach($datas as $data){
    //          $typeclient=null;
    //          if($data->getTypeclient()){
    //              $typeclient=$data->getTypeclient()->getId();
    //          }
    //          $clientlist=new ClientList;
    //          $clientlist->setClientId($data->getId());
    //          $clientlist->setTypeClient($typeclient);
    //          array_push($client,$clientlist);

    //      }
    //     return new ApiResponse($client,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client']);        

    // }

     /**
     * @Route("/api/updateAmount", name="updateAmountShareholder",methods={"PUT"})
     */
    public function updateAmountShareholder_(DataAttorneyRepository $dataattonyrepo,Request $request){
        $entityManager    = $this->EM;
        $encoders         = [ new JsonEncoder()];
        $normalizers      = [new ObjectNormalizer()];
        $serializer       = new Serializer($normalizers ,$encoders);
        $content          = $request->getContent();
        $entityManager    = $this->EM;
        $data = $serializer->deserialize($content, DataAttorney::class, 'json');
        $dataattorney  =  $dataattonyrepo->findOneBy(['id'=>$data->getId()]);
        if(!$dataattorney){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"invalid attorney id",['timezone','client']);        
        }
        $dataattorney->setAmountAttorney($data->getAmountAttorney());
        $dataattorney->setDateDateAttorney(new \DateTime());
        $entityManager -> persist($dataattorney);
        $entityManager -> flush();
        return new ApiResponse($dataattorney,200,["Content-Type"=>"application/json"],'json',"updated successfully",['timezone','client']);        
    }

    /**
     * @Route("/api/updateDepotDone", name="updateDepotDoneShareholder",methods={"PUT"})
     */
    public function updateDepotDonetShareholder_(DataAttorneyRepository $dataattonyrepo,Request $request){
        $entityManager    = $this->EM;
        $encoders         = [ new JsonEncoder()];
        $normalizers      = [new ObjectNormalizer()];
        $serializer       = new Serializer($normalizers ,$encoders);
        $content          = $request->getContent();
        $entityManager    = $this->EM;
        $data = $serializer->deserialize($content, DataAttorney::class, 'json');
        $dataattorney  =  $dataattonyrepo->findOneBy(['id'=>$data->getId()]);
        if(!$dataattorney){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"invalid attorney id",['timezone','client']);        
        }
        $dataattorney->setReceivedAmountAttorney($data->getReceivedAmountAttorney());
        $dataattorney->setIsokAmountAttorney($data->getIsokAmountAttorney());
        $dataattorney->setDateDateAttorney(new \DateTime());
        $dataattorney->setIsDepotDoneAttorney($data->getIsDepotDoneAttorney());
        $entityManager -> persist($dataattorney);
        $entityManager -> flush();
        return new ApiResponse($dataattorney,200,["Content-Type"=>"application/json"],'json',"updated successfully",['timezone','client']);  
    }

     /**
     * @Route("/api/updateCompanyInformation/{id}/{siret}/{apeid}", name="updateCompanyInformation",methods={"PUT"})
     */
    public function updateCompanyInformation(DataClientRepository $dataclientrepo,RefEpaRepository $eparepo,Request $request,$id,$siret,$apeid){
        $entityManager    = $this->EM;
        $dataClient  =  $dataclientrepo->findOneBy(['id'=>$id]);
        if(!$dataClient){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"invalid attorney id",['timezone','client']);        
        }
        $epa = $eparepo->findOneBy(['id'=>$apeid]);
        if(!$epa){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"invalid ape id",['timezone','client']);        
        }
        $dataClient->setEpa($epa);
        $dataClient->setSiren($siret);
        $entityManager -> persist($dataClient);
        $entityManager -> flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json',"updated successfully",['timezone','client']);        
    }

     /**
     * @Route("/api/cto_passage/{req_id}", name="ctoPassage")
     */
    public function ctoPassage(DataCTORepository $ctorepo,$req_id){
        $datacto = $ctorepo->findBy(['request'=>$req_id], ['passage' => 'DESC']);
        if(!$datacto){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"empty");        
        }
        $passage_check = array();
        foreach($datacto as $cto){
            if($cto->getPassage()!=null){
                array_push($passage_check,$cto->getPassage());
            }
        }
        if(!$passage_check){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"all passage arw null");        
        }
        $passages = array_unique($passage_check);
        $object = array();
        foreach($passages as $passage){
            $datactos = $ctorepo->findBy(['request'=>$req_id,'passage'=>$passage]);
            $okcount = 0;
            $kocount = 0;
            foreach($datactos as $datacto)
            {
                $code = $datacto->getCode(); 
                if($code =="OK"){
                    $okcount = $okcount+1;
                }
                if($code =="KO"){
                    $kocount = $kocount+1;
                }
                
            }
            $passageobj = new Passage;
            $passageobj->setPassage($passage);
            $passageobj->setOk($okcount);
            $passageobj->setKo($kocount);
            array_push($object,$passageobj);
        }
        return new ApiResponse($object,200,["Content-Type"=>"application/json"],'json',"success");        

    }
    /**
     * @Route("/api/bulkdataupload/{id}/{no_duplicate}/{code}", name="bulkdataupload",methods={"POST"})
     */
    public function bulkDataUpload($id,$no_duplicate,$code){
        $entityManager = $this->EM;
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $datarequestrepo  =  $this->EM->getRepository(DataRequest::class);
        $dataclientrepo  =  $this->EM->getRepository(DataClient::class);
        $optionrepo  =  $this->EM->getRepository(OptionsTabel::class);
        $attorneyrepo  =  $this->EM->getRepository(DataAttorney::class);
        $userspacerepo  =  $this->EM->getRepository(DataUserspace::class);
        $datacontactrepo  =  $this->EM->getRepository(DataContact::class);
        $dataCTOrepo  =  $this->EM->getRepository(DataCTO::class);
        $datasignaturerepo  =  $this->EM->getRepository(DataSignature::class);
        $dataCountryWhereclientrepo  =  $this->EM->getRepository(DataCountryWhereclient::class);
        $dataCountryWheresupplierrepo = $this->EM->getRepository(DataCountryWheresupplier::class);
        $dataClientWhereclientrepo  =  $this->EM->getRepository(DataClientWhereclient::class);
        $dataClientWheresupplierrepo = $this->EM->getRepository(DataClientWheresupplier::class);
        $eloquacdorepo = $this->EM->getRepository(DataEloquaCdo::class);
        $eloquacontactrepo = $this->EM->getRepository(DataEloquaContact::class);
        $dataFieldIssuerepo  =  $this->EM->getRepository(DataFieldIssue::class);
        $dataRequestFilerepo  =  $this->EM->getRepository(DataRequestFile::class);
        $refrequeststatusrepo  =  $this->EM->getRepository(RefRequeststatus::class);
        $datarequeststatusrepo  =  $this->EM->getRepository(DataRequestRequeststatus::class);
        $status = $refrequeststatusrepo->findOneBy(['status_requeststatus'=>$code]);    
        for($i=0;$i<$no_duplicate;$i++){ 
            $org_datarequestobject = $datarequestrepo->findOneBy(['requestRef'=>$id]);
            $org_datauserspace = $userspacerepo->findOneBy(['id_request'=>$org_datarequestobject]);
            $org_client = $org_datarequestobject->getClient();
            $org_eloquacontact = $eloquacontactrepo->findOneBy(['request'=>$org_datarequestobject]);
            $orgfieldissues = $dataFieldIssuerepo->findBy(['client'=>$org_client]);
            //DataClient

            $dup_client = clone $org_client;
            $entityManager->persist($dup_client);
            $entityManager->flush();
            
            //OptionsTabel
            $org_option = $optionrepo->findOneBy(['client'=>$org_client]);
            $dup_option = clone $org_option;
            $dup_option->setClient($dup_client);
            $entityManager->persist($dup_option);
            $entityManager->flush();

            //DataAttorney

            $org_attornies = $attorneyrepo->findBy(['client'=>$org_client->getId()]);
            
            foreach($org_attornies as $org_attorney){
                $dup_attorney = clone $org_attorney;
                $dup_attorney->setClient($dup_client);
                $entityManager->persist($dup_attorney);
                $entityManager->flush();
                $org_datacontacts = $datacontactrepo->findBy(['client'=>$org_client->getId(),"attorney"=>$org_attorney->getId()]);
                //DataContact
                if($org_datacontacts){
                    foreach($org_datacontacts as $org_datacontact){
                        $dup_datacontact = clone $org_datacontact;
                        $dup_datacontact->setClient($dup_client);
                        $dup_datacontact->setAttorney($dup_attorney);
                        $entityManager->persist($dup_datacontact);
                        $entityManager->flush();
                    }
                }
            }
            
            //DataRequest
            $dup_datarequestobject = clone $org_datarequestobject;
            $dup_datarequestobject->setClient($dup_client);
            $entityManager->persist($dup_datarequestobject);
            $entityManager->flush();

            //DataSignature
            $org_datasignatures = $datasignaturerepo->findBy(['request_id'=>$org_datarequestobject]);
            foreach($org_datasignatures as $org_datasignature){
                $dup_datasignature = clone $org_datasignature;
                $dup_datasignature->setRequestId($dup_datarequestobject);
                $entityManager->persist($dup_datasignature);
                $entityManager->flush();
            }

            //DataRequestRequeststatus
            $org_datarequetstatus = $datarequeststatusrepo->findOneBy(['id_request'=>$org_datarequestobject,'id_requeststatus'=>$status]);
            $dup_datarequetstatus = clone $org_datarequetstatus;
            $dup_datarequetstatus->setIdRequest($dup_datarequestobject);
            $entityManager->persist($dup_datarequetstatus);
            $entityManager->flush();

            //DataRequestFile
            $org_datarequestfiles = $dataRequestFilerepo->findBy(['request'=>$org_datarequestobject]);
            foreach($org_datarequestfiles as $org_datarequestfile){
                $dup_datarequestfile = clone $org_datarequestfile;
                $dup_datarequestfile->setRequest($dup_datarequestobject);
                $entityManager->persist($dup_datarequestfile);
                $entityManager->flush();
            }

            //DataUserspace
            $dup_datauserspace = clone $org_datauserspace;
            $dup_datauserspace->setIdRequest($dup_datarequestobject);
            $dup_datauserspace->setEmailUs("samplemail".rand(10,10000).".com");
            $entityManager->persist($dup_datauserspace);
            $entityManager->flush();

            //DataEloquaContact
            $dup_eloquacontact = clone $org_eloquacontact;
            $dup_eloquacontact->setRequest($dup_datarequestobject);
            $dup_eloquacontact->setEmailAddress($dup_datauserspace->getEmailUs());
            $entityManager->persist($dup_eloquacontact);
            $entityManager->flush();

            //DataEloquaCdo
            $org_dataeloquacdos = $eloquacdorepo->findBy(['request'=>$org_datarequestobject]);
            foreach($org_dataeloquacdos as $org_dataeloquacdo){
                $dup_dataeloquacdo = clone $org_dataeloquacdo;
                $dup_dataeloquacdo->setRequest($dup_datarequestobject);
                $entityManager->persist($dup_dataeloquacdo);
                $entityManager->flush();
            }

            //DataCTO
            $org_dataCTOs = $dataCTOrepo->findBy(['request'=>$org_datarequestobject]);
            foreach($org_dataCTOs as $org_dataCTO){
                $dup_dataCTO = clone $org_dataCTO;
                $dup_dataCTO->setRequest($dup_datarequestobject);
                $entityManager->persist($dup_dataCTO);
                $entityManager->flush();
            }
            
            //DataCountryWhereclient
            $org_dataCountryWhereclients = $dataCountryWhereclientrepo->findBy(['client'=>$org_client->getId()]);
            foreach($org_dataCountryWhereclients as $org_dataCountryWhereclient){
                $dup_dataCountryWhereclient = clone $org_dataCountryWhereclient;
                $dup_dataCountryWhereclient->setClient($dup_client);
                $entityManager->persist($dup_dataCountryWhereclient);
                $entityManager->flush();
            }

             //DataCountryWheresupplier
             $org_dataCountryWheresuppliers = $dataCountryWheresupplierrepo->findBy(['client'=>$org_client->getId()]);
             foreach($org_dataCountryWheresuppliers as $org_dataCountryWheresupplier){
                 $dup_dataCountryWheresupplier = clone $org_dataCountryWheresupplier;
                 $dup_dataCountryWheresupplier->setClient($dup_client);
                 $entityManager->persist($dup_dataCountryWheresupplier);
                 $entityManager->flush();
             }

             //DataClientWhereclient
            $org_dataClientWhereclients = $dataClientWhereclientrepo->findBy(['client'=>$org_client->getId()]);
            foreach($org_dataClientWhereclients as $org_dataClientWhereclient){
                $dup_dataClientWhereclient = clone $org_dataClientWhereclient;
                $dup_dataClientWhereclient->setClient($dup_client);
                $entityManager->persist($dup_dataClientWhereclient);
                $entityManager->flush();
            }

             //DataClientWheresupplier
             $org_dataClientWheresuppliers = $dataClientWheresupplierrepo->findBy(['client'=>$org_client->getId()]);
             foreach($org_dataClientWheresuppliers as $org_dataClientWheresupplier){
                 $dup_dataClientWheresupplier = clone $org_dataClientWheresupplier;
                 $dup_dataClientWheresupplier->setClient($dup_client);
                 $entityManager->persist($dup_dataClientWheresupplier);
                 $entityManager->flush();
             }


           //DataFieldIssue
           foreach($orgfieldissues as $orgfieldissue){
            $dup_fieldissue = clone $orgfieldissue;
            $dup_fieldissue->setClient($dup_client);
            $entityManager->persist($dup_fieldissue);
            $entityManager->flush();
        }    
            $a = $i; 
        }
        return new ApiResponse([$dup_client->getId()],200,["Content-Type"=>"application/json"],'json',"updated successfully",['timezone','client']);        
    }

     /**
     * @Route("/api/remove/document", name="RefuseDocument",methods={"GET"})
     */
    public function RefuseDocument(DocumentService $documentService){
        $data = $documentService->RefuseDocument();
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success");        

    }
    /**
     * @Route("/api/remove/document/{req_id}", name="RefuseDocumentParticularRequest",methods={"GET"})
     */
    public function RefuseDocumentParticularRequest(DocumentService $documentService,$req_id){
        $data = $documentService->RefuseDocumentParticularRequest($req_id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success");        

    }

     /**
     * @Route("/api/remove/document/list/sabclient", name="RefuseClientSabRequest",methods={"GET"})
     */
    public function RefuseClientSabRequest(DocumentService $documentService){
        $data = $documentService->RefuseClientSabRequest();
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client']);        

    }

    /**
     * @Route("/api/clientsab/close/{numero}/{request_id}", name="closeClientSab",methods={"GET"})
     */
    public function closeClientSab($numero,$request_id,DocumentService $documentService){
        $data = $documentService->closeClientSab($numero,$request_id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client']);        

    }

    
    /**
     * @Route("/api/fid/list/{type_client_id}", name="fidList",methods={"GET"})
     */
    public function fidList($type_client_id){
        $fidrepo  =  $this->EM->getRepository(RefFid::class);
        $data = $fidrepo->findBy(["typeClient"=>$type_client_id]);
        // $data = $documentService->closeClientSab($numero,$request_id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client']);        

    }
}
