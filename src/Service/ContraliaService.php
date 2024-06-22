<?php

namespace App\Service;
use GuzzleHttp\Client;
use App\Service\DocumentService;
use App\Utils\ApiHelper;
use App\Entity\DataContact;
use App\Entity\DataRequest;
use App\Entity\DataAttorney;
use App\Entity\DataSignature;
use GuzzleHttp\Psr7\Response;
use App\Entity\DataRequestFile;
use App\Entity\RefRequeststatus;
use function GuzzleHttp\json_encode;
use Symfony\Component\Finder\Finder;
use Psr\Http\Message\ResponseInterface;
use App\Entity\DataRequestRequeststatus;
use App\Entity\RefFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ContraliaService{
    private $EM;
    private $docservice;
    private $params;

    public function __construct(EntityManagerInterface $EM,DocumentService $docservice,ParameterBagInterface $params )
    {
        $this->EM = $EM;
        $this->docservice = $docservice;
        $this->params = $params;

    }

    public function initiate($requestid,$apihelper){
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize(new \DateTime());
        $datarepo=$this->EM->getRepository(DataRequest::class);
        $datarequest=$datarepo->findOneBy(['id'=>$requestid]);
        if(!$datarequest){
            return "invalid request_id";
        }
        $customref=$requestid.'_'.$dateAsString;
        
        $data = [           
                'organizationalUnitCode' => 'THEMIS_BANQUE-TEST-DISTRIB',
                'customRef' => $customref                           
        ];
      
        $response = $apihelper->apiPost('THEMIS_BANQUE-TEST/transactions', $data);
		
        $code= $response->getBody()->getContents();
        $xml = simplexml_load_string($code);
        $json = json_encode($xml);
        $data = json_decode($json,TRUE);
        $id = $data['@attributes']['id'];
        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);
        $datasignature = new DataSignature();  
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(1);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();
        return $id;


    }
    public function upload($request,$apihelper,$requestid, $emailservice, $documentMaker) {
        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findBy(['request_id'=>$requestid], ['id' => 'DESC']);

        if(!$data){
            return "invalid request id";
        }
        // $q              =  "SELECT MAX(transaction_status) as max FROM data_signature WHERE request_id_id = :requestId";
        // $conn           =  $this->EM ->getConnection();
        // $stmt           =  $conn->prepare($q);
        // $stmt           ->bindValue('requestId', $requestid);
        // $stmt           -> execute();
        // $result         =  $stmt->fetchAll();
        // $status         =  $result[0]['max'];
        $siganature     = $datarepo->findOneBy(['request_id'=>$requestid], ['id' => 'DESC']); 
        $id             = $siganature->getTransactionId();
        
        $listfile = $documentMaker->makeContractDocument($requestid, $emailservice);
        
        foreach ($listfile as $key => $value) {
            //$file = "client_document/contrat.pdf";
            $file =  $value;
            $data = [           
            'file' => 
                [
                    'name'     => 'file',
                    'contents' => file_get_contents($file),
                    'filename' => 'test'
                ],
				[
                    'name'     => 'champs',
                    'contents' => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
					<fields xmlns="http://www.contralia.fr/champsPdf">
						
						<organizationSignature>
							<box x="380" y="140" width="130" height="130" page="4"/>
						</organizationSignature>
						<signatorySignature  number="1">
							<box x="60" y="140" width="130" height="130" page="4"/>							
						</signatorySignature >
					</fields>'
                ]				
            ];            
            $response = $apihelper->apiFilePost('transactions/'.$id.'/document', $data);
        }

        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);

        $datasignature = new DataSignature(); 
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(2);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();
        return $response;
        // $this->legalRepresentant($apihelper,$requestid);
    }

    public function legalRepresentant($apihelper,$requestid){
        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findBy(['request_id'=>$requestid], ['id' => 'DESC']);
        $datarequestrepo = $this->EM->getRepository(DataRequest::class);
        $clientdata = $datarequestrepo->findOneBy(['id'=>$requestid]);
        if(!$clientdata){
            return "invalid request id";
        }
        $clientid = $clientdata->getClient()->getId(); //find client from Datarequest
        $dataattorneyrepo = $this->EM->getRepository(DataAttorney::class);
        $attorneydata = $dataattorneyrepo->findOneBy(['client'=>$clientid,'ismandatory_attorney'=>true]);
        $attorneyid = $attorneydata->getId();          //attorney id for representative
        $lastname = $attorneydata->getNameAttorney();   //firstname
        $firstname = $attorneydata->getSurnameAttorney(); //lastname
        $birthname   = $attorneydata->getBirthName();//birthname
        $datacontactrepo = $this->EM->getRepository(DataContact::class);
        $contact = $datacontactrepo->findOneBy(['attorney'=>$attorneyid,'type_contact'=>4]);
        $phone = $contact->getValueContact();
        // return $phone;
        if(!$data){
            return "invalid request id";
        }
        // $q              =  "SELECT MAX(transaction_status) as max FROM data_signature WHERE request_id_id = :requestId";
        // $conn           =  $this->EM ->getConnection();
        // $stmt           =  $conn->prepare($q);
        // $stmt           ->bindValue('requestId', $requestid);
        // $stmt           -> execute();
        // $result         =  $stmt->fetchAll();
        // $status         =  $result[0]['max'];
        $siganature = $datarepo->findOneBy(['request_id'=>$requestid], ['id' => 'DESC']); 
        $id= $siganature->getTransactionId();
        
        $data = [  
            "firstname"=>$firstname,
            "lastname"=>$lastname,
            "birthname"=>$birthname,
            "phone"=>$phone
   
            ];
        $response = $apihelper->apiPost('transactions/'.$id.'/signatory/1', $data);
        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);
        $datasignature = new DataSignature();  
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(3);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();

        // $this->edocUrl($requestid,$apihelper);
        return $response;
       

    }
    public function edocUrl($apihelper,$requestid){
        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findBy(['request_id'=>$requestid], ['id' => 'DESC']);
   
        if(!$data) {
            return "invalid request id";
        }
        // $q              =  "SELECT MAX(transaction_status) as max FROM data_signature WHERE request_id_id = :requestId";
        // $conn           =  $this->EM ->getConnection();
        // $stmt           =  $conn->prepare($q);
        // $stmt           ->bindValue('requestId', $requestid);
        // $stmt           -> execute();
        // $result         =  $stmt->fetchAll();
        // $status         =  $result[0]['max'];
        $siganature = $datarepo->findOneBy(['request_id'=>$requestid], ['id' => 'DESC']); 
        $id= $siganature->getTransactionId();
       
        // We ll change the url and set it in config file when it ll tested
        $data = [  
            "transactionId" => $id,
            "doneUrl"       => $this->params->get('app.sig').$requestid,
			"config"		=> '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<config>
    <otpDeliveryMode>
        <deliveryMode mode="SMS" count="3" />
    </otpDeliveryMode>
    <genOtpConfig>
        <test>false</test>
        <smsCustomSender>Fiducial</smsCustomSender>
        <smsCustomMessage>Fiducial Banque - Votre code de signature est : {OTP}</smsCustomMessage>
    </genOtpConfig>
</config>',
            "agreementText" => "J'ai lu le document et j'accepte sans réserve les Conditions Particulières Banque en Ligne – FIDUCIAL BANQUE."
        ];
        $response = $apihelper->eDocPost('document/signUrl', $data);
        $code= $response->getBody()->getContents();
        $xml = simplexml_load_string($code);
        $json = json_encode($xml);
        $data = json_decode($json,TRUE);
        // return $data;
        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);
        $datasignature = new DataSignature();  
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(4);
        $datasignature -> setTransactionUrl($data[0]);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();

        return $datasignature -> getTransactionUrl();

    }

    public function setBankSignature($apihelper,$requestid){
        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findBy(['request_id'=>$requestid]);

        if(!$data){
            return "invalid request id";
        }
        
        // $q              =  "SELECT MAX(transaction_status) as max FROM data_signature WHERE request_id_id = :requestId";
        // $conn           =  $this->EM ->getConnection();
        // $stmt           =  $conn->prepare($q);
        // $stmt           ->bindValue('requestId', $requestid);
        // $stmt           -> execute();
        // $result         =  $stmt->fetchAll();
        // $status         =  $result[0]['max'];
        $signature      = $datarepo->findOneBy(['request_id'=>$requestid], ['id'=> 'DESC']); 
        $id             = $signature->getTransactionId();

        $response = $apihelper->apiPost('transactions/'.$id.'/organizationSignature',[]);
        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);
        $datasignature = new DataSignature();  
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(6);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();
        return $response;
    }

    public function terminate($apihelper,$requestid){
        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findBy(['request_id'=>$requestid], ['id' => 'DESC']);
   
        if (!$data) {
            return "invalid request id";
        }

        // $q              =  "SELECT MAX(transaction_status) as max FROM data_signature WHERE request_id_id = :requestId";
        // $conn           =  $this->EM ->getConnection();
        // $stmt           =  $conn->prepare($q);
        // $stmt           ->bindValue('requestId', $requestid);
        // $stmt->execute();
        // $result         =  $stmt->fetchAll();
        // $status         =  $result[0]['max'];
        
        $signature  = $datarepo->findOneBy(['request_id'=>$requestid], ['id' => 'DESC']); 
        $id         = $signature->getTransactionId();
    
        $response = $apihelper->apiPost('transactions/'.$id.'/terminate',[]);
        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);
        $datasignature = new DataSignature();  
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(7);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();
        return $response;
    }

    public function documentDownload($apihelper, $requestid, $entityManager) {

        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findBy(['request_id'=>$requestid]);

        $datareqrepo = $this->EM->getRepository(DataRequest::class);
        $datareq = $datareqrepo->findOneBy(['id'=>$requestid]);

        $reffilerepo = $this->EM->getRepository(RefFile::class);
        $reffile = $reffilerepo->findOneBy(['jsonkey'=>'contrat']);

        if(!$data){
            return "invalid request id";
        }
        $q              =  "SELECT MAX(id) as max FROM data_signature WHERE request_id_id = :requestId";
        $conn           =  $this->EM ->getConnection();
        $result = $conn->executeQuery($q, ['requestId' => $requestid])->fetch();
        $status         =  $result['max'];

        $signature  = $datarepo->findOneBy(['request_id'=>$requestid,'id'=>$status]); 
        $id         = $signature->getTransactionId();

        $response = $apihelper->apiGet('transactions/'.$id.'/finalDoc', []);        
        if ($response) {			
            $folder         = '../file/'.$requestid.'/';   
            $filesystem     = new Filesystem();
            $path           = $filesystem->exists($folder);
            if ($path == false) {
                $filesystem->mkdir($folder);
            }
            file_put_contents($folder."contrat_".$requestid.".pdf", $response->getBody());	
			
            $unnid = $this->docservice->UuidGenerate($requestid);
			
            $datareqfile   =  new DataRequestFile();
            $datareqfile   -> setFile($reffile);
            $datareqfile   -> setRequest($datareq);
            $datareqfile   -> setPath($folder."contrat_".$requestid.".pdf");
            $datareqfile   -> setFilename("EER Fiducial - Contrat.pdf");
            $datareqfile   -> setFileUuid($unnid);
            $entityManager -> persist($datareqfile);
            $entityManager -> flush();
        }

        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);
        $datasignature = new DataSignature();  
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(8);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();
        //$this->statusChange($requestid);
        return $response;    
    }

    public function documentSign($requestid){
        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findOneBy(['request_id'=>$requestid], ['id' => 'DESC']);   
        if(!$data){
            return "invalid request id";
        }
        $id = $data->getTransactionId();
        $datarepo = $this->EM->getRepository(DataRequest::class);
        $datarequest = $datarepo->findOneBy(['id'=>$requestid]);
        $datasignature = new DataSignature();  
        $datasignature -> setRequestId($datarequest);
        $datasignature -> setTransactionId($id);
        $datasignature -> setTransactionDate(new \DateTime());
        $datasignature -> setTransactionStatus(5);
        $this->EM -> persist($datasignature);
        $this->EM -> flush();
        return $datasignature;    
    }

    public function getSignedStatus($requestid){
        $datarepo = $this->EM->getRepository(DataSignature::class);
        $data = $datarepo->findOneBy(['request_id'=>$requestid], ['id' => 'DESC']);
        if(!$data){
            return "invalid request id";
        }
        return $data;
    }

    public function statusChange($requestid) {
        $datarepo   = $this->EM->getRepository(DataRequest::class);
        $data       = $datarepo->findOneBy(['id'=>$requestid]);
        $ids        = [4,5];
        $reqstatusrepo = $this->EM->getRepository(RefRequeststatus::class);

        foreach($ids as $id){
            $reqstatus=$reqstatusrepo->findOneBy(['id'=>$id]);
            $reqreqstatus=new DataRequestRequeststatus();
            $reqreqstatus->setIdRequest($data);
            $reqreqstatus->setIdRequeststatus($reqstatus);
            $reqreqstatus->setDateRequestRequeststatus(new \DateTime());
            $this->EM->persist($reqreqstatus);
            $this->EM->flush();
        }
        return "success";        
    }
}