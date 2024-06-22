<?php

namespace App\Service;
use Pikirasa\RSA;
use App\Utils\Eloqua;
use App\Utils\ApiHelper;
use App\Entity\DataAttorney;
use App\Entity\OptionsTabel;
use App\Entity\DataEloquaCdo;
use App\Entity\DataEloquaContact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EloquaService{

    private $EM;
    private $params;
    private $apihelper;

    public function __construct(EntityManagerInterface $EM,ApiHelper $apihelper,ParameterBagInterface $params)
    {
        $this->EM = $EM;
        $this->apihelper = $apihelper;
        $this->params = $params;

    }

    public function eloquaCall($datarequestchk,$userspacerepo,$step){
            $userspace = $userspacerepo->findOneBy(['id_request'=>$datarequestchk->getId()]);
            $email_id = $userspace->getEmailUs(); 
            $contactrepo = $this->EM->getRepository(DataEloquaContact::class);
            $chkcontact = $contactrepo->findOneBy(['emailAddress'=>$email_id]);
            if(!$chkcontact){
            $eloquaContact = new DataEloquaContact();
            $eloquaContact->setEmailAddress($email_id);
            $eloquaContact->setDate(new \DateTime());
            $eloquaContact->setIsSend(false);
            $eloquaContact->setRequest($datarequestchk);
            $this->EM ->persist($eloquaContact);
            $this->EM ->flush();
            }
            $attoneyrepo = $this->EM->getRepository(DataAttorney::class);
            $attoney  = $attoneyrepo->findOneBy(['ismandatory_attorney'=>true,'client'=>$datarequestchk->getClient()]);
            $nom = null;
            $prenom = null;
            $civility = null;
            $companyname = $datarequestchk->getClient()->getCompanynameClient();
            if($attoney){
                $nom = $attoney->getNameAttorney();
                $prenom = $attoney->getSurnameAttorney();
                $birthname   = $attoney->getBirthName();
                if($attoney->getCivilityAttorney()){
                    $civility = $attoney->getCivilityAttorney()->getDescCivility();
                }
            }
            // $contactdata = $this->eloquaContact($email_id);

            $pub_key = file_get_contents('../pub.crt');
            $pri_key = file_get_contents('../pri.crt');
            $rsa     = new RSA($pub_key, $pri_key);
            $data    = $email_id;
            $encrypted = $rsa->base64Encrypt($data);

            $baseurl = base64_encode($encrypted);
            //$base_url = $this->params->get('app.ser1')."/otp?euid=$baseurl";

            $optionsrepo = $this->EM->getRepository(OptionsTabel::class);
            $option = $optionsrepo->findOneBy(['client'=>$datarequestchk->getClient()]);
            $eloquarepo = $this->EM->getRepository(DataEloquaCdo::class);
            $eloquadata = $eloquarepo->findOneBy(['request'=>$datarequestchk]);
            $url = null;
            if($eloquadata){
                $url = $eloquadata->getUrlsource();
            }
            $eloquaCdo = new DataEloquaCdo();
            $eloquaCdo->setEmailAddress($email_id);
            $eloquaCdo->setDate((string)time());
            if($datarequestchk->getClient()->getCompanytype()){
                $companutype = $datarequestchk->getClient()->getCompanytype()->getId();
                if($companutype==1){
                    $companutype="Société";
                }
                if($companutype==2){
                    $companutype="Créateur";
                }
                $eloquaCdo->setOffreSituation($companutype);
            }        
            if($option){
                // return $option;
                if($option->getProduct()){
                    $eloquaCdo->setOffreFormule($option->getProduct()->getName()); 
                }
            }
            $eloquaCdo->setEtape($step);
            $eloquaCdo->setLoginUrl($baseurl);
            $eloquaCdo->setIsSend(false);
            $eloquaCdo->setRequest($datarequestchk);
            $eloquaCdo->setCivility($civility);
            $eloquaCdo->setNom($nom);
            $eloquaCdo->setPrenom($prenom);
            $eloquaCdo->setBirthName($birthname);
            $eloquaCdo->setRaisonSociale($companyname);
            $eloquaCdo->setUrlsource($url);
            $this->EM ->persist($eloquaCdo);
            $this->EM ->flush();
            // $this->eloquaCdo($eloquaCdo);
            return "success";

    }
    public function EloquaContactAutoCall()
    {
        $contactrepo = $this->EM->getRepository(DataEloquaContact::class);
        
        $contacts = $contactrepo->findBy(['isSend'=>false]);
        foreach($contacts as $contact){
            $eloquadata = $this->eloquaContact($contact->getEmailAddress());
            if($eloquadata != 'ok'){
                $contact->setEloquaId($eloquadata->getId());
                $contact->setIsSend(true);
                $this->EM ->persist($contact);
                $this->EM ->flush();
            }
            else{
                $contact->setIsSend(true);
                $this->EM ->persist($contact);
                $this->EM ->flush();
            }
        }
    }

    public function EloquaCdoAutoCall()
    {
        $cdorepo = $this->EM->getRepository(DataEloquaCdo::class);
        $cdos = $cdorepo->findBy(['isSend'=>false]);
        foreach($cdos as $cdo){
            $eloquadata = $this->eloquaCdo($cdo);
            // return $eloquadata;
            $cdo->setEloquaId($eloquadata->getId());
            $cdo->setIsSend(true);
            $this->EM ->persist($cdo);
            $this->EM ->flush();
        }
    }

    public function eloquaContact($emailid){
        $exception = false;
        $route="api/REST/1.0/data/contact";
        $data = [  
           "emailAddress" => $emailid,
       ];
       try{
            $response=$this->apihelper->eloquaPost($route,$data);
            if(!$response){
                throw new HttpException(400,"email already exist");    
            }
       }
       catch(\Exception $e){
            $exception = true; 
       }
       if($exception==false){
       $bodycontent = $response-> getBody();
       $encoders = [ new JsonEncoder()];
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers ,$encoders);
       $data = $serializer->deserialize($bodycontent, Eloqua::class, 'json');
       return $data; 
       }
       return "ok"; 
    }

    public function eloquaCdo($cdo){
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
        $loginurl = $cdo->getLoginUrl();
        // if($cdo->getLoginUrl()){
        //     $loginurl = $cdo->getLoginUrl();
            // $loginurl = explode("'", $loginurl);
            // $loginurl = $loginurl[1];
        // }
        // return $loginurl;
        $data =[
          "type"=>"CustomObjectData",
          "fieldValues"=> [
            [
                "id"=> "234",
                "value"=> $loginurl
            ],
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
       $response=$this->apihelper->eloquaPost($route,$data);
       $bodycontent = $response-> getBody();
       $encoders = [ new JsonEncoder()];
       $normalizers = [new ObjectNormalizer()];
       $serializer = new Serializer($normalizers ,$encoders);
       $data = $serializer->deserialize($bodycontent, Eloqua::class, 'json');
       // $arraybodycontent=json_decode(json_encode($bodycontent), true);
       return $data;    
    }

}
