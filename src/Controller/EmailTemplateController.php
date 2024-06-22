<?php

namespace App\Controller;
// require_once '/path/to/vendor/autoload.php';

use Pikirasa\RSA;
use App\UtilsV3\Type;
use App\UtilsV3\Email;
use App\UtilsV3\Direct;


use App\Utils\ApiHelper;
use App\UtilsV2\DataTemp;
use App\UtilsV2\EmailAct;
use App\UtilsV3\Category;
use App\Entity\RefEmailid;
use App\Utils\ApiResponse;
use App\Entity\EmailAction;
use App\Entity\RefVariable;
use App\UtilsV3\CheckEmail;
use App\Entity\DataTemplate;
use App\Entity\EmailContent;
use App\Service\EmailService;
use App\Entity\DataConnection;
use App\Entity\DataFieldIssue;
use App\Service\ClientService;
use App\Service\EloquaService;
use App\UtilsV3\Master\Status;
use App\UtilsV3\SampleContent;
use App\Entity\DataFieldIssues;
use App\Service\EmailServiceV1;
use App\Service\DocumentService;
use App\Service\ContraliaService;
use App\Service\IntegrationService;
use App\Repository\RefTypeRepository;
use App\Repository\RefFieldRepository;
use App\Repository\RefTableRepository;
use App\Entity\DataTemplateVariablesV1;
use App\Repository\RefDirectRepository;
use App\Repository\DataClientRepository;
use App\Repository\RefEmailidRepository;
use App\Repository\DataContactRepository;
use App\Repository\DataRequestRepository;
use App\Repository\EmailActionRepository;
use App\Repository\RefVariableRepository;
use App\Service\DataReq_ReqstatusService;
use App\Repository\DataAttorneyRepository;
use App\Repository\DataTemplateRepository;
use App\Repository\EmailContentRepository;
use App\Repository\OptionsTabelRepository;
use App\Repository\RefSignatureRepository;
use App\Repository\DataClientSabRepository;
use App\Repository\DataUserspaceRepository;
use App\Repository\DataConnectionRepository;
use App\Repository\DataFieldIssueRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DataActionVariableRepository;
use App\Repository\DataTemplateVariablesV1Repository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use \Swift_SmtpTransport,\Swift_Mailer,\Swift_Message;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class EmailTemplateController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

  
     /**
     * @Route("/api/template/getvariables", name="getvariables")
     */
    public function getTemplateVariables(RefVariableRepository $datatempvs)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $chkstatus = $datatempvs->findBy(['isEmail'=>true]);
        if($chkstatus){
            return new ApiResponse($chkstatus,200,["Content-Type"=>"application/json"],'json','success',['query','script','value']);
        }
        return new ApiResponse($chkstatus,400,["Content-Type"=>"application/json"],'json','There is no active variables',['query','script','value']);
    }

    /**
     * @Route("/api/getallaction", name="getaction",methods={"POST"})
     */
    public function getaction_(Request $req,EmailActionRepository $emailactionrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $req->getContent();
        $data = $serializer->deserialize($content, EmailAct::class, 'json');
        $entityManager = $this->EM;
        $arraystatus = $data->getArrayStatus();
        $response = $emailactionrepo->findBy(array('status' => $arraystatus),array('id' => 'ASC'));
        if($response){
              return new ApiResponse($response,200,["Content-Type"=>"application/json"],'json','success');
        }
        return new ApiResponse($response,404,["Content-Type"=>"application/json"],'json','invalid');
       
    }

      /**
     * @Route("/api/emailaction/update", name="updateaction",methods={"PUT"})
     */
    public function updateAction_(Request $req,EmailActionRepository $emailactionrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $req->getContent();
        $data = $serializer->deserialize($content, EmailAct::class, 'json');
        $entityManager = $this->EM;
        $arraystatus = $data->getArrayStatus();
        $chkid = $emailactionrepo->findOneBy(['id'=>$data->getId()]);
        if($chkid=="") 
        {
           return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','invalid');    
        }
           $chkid->setTitle($data->getTitle());
           $chkid->setNoOfDays($data->getNoOfDays());          
           $chkid->setDescEmailaction($data->getDescEmailaction());
           $entityManager->persist($chkid);
           $entityManager->flush();
           return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','successfully updated!!');
       
    }

    /**
     * @Route("/api/newtemplate", name="newtemplate",methods={"POST"})
     */
    public function newtemplate_(Request $req,DataTemplateRepository $datatemprepo,RefTableRepository $refTabelRepo,EmailActionRepository $emailactionrepo,RefEmailidRepository $emailidrepo,RefSignatureRepository $sigrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        $values = $req->getContent();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer); 
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $serializer->deserialize($values, DataTemp::class, 'json');
        $actid = $emailactionrepo->findOneBy(['id'=>$data->getActionId()]);
        $emailid = $emailidrepo->findOneBy(['id'=>$data->getFromTemplate()]);
        $signature = null;
        if($data->getSignature()){
            $signature = $sigrepo->findOneBy(['id'=>$data->getSignature()]);
            if(!$signature) 
            {
                return new ApiResponse($data->getActionId(),404,["Content-Type"=>"application/json"],'json','Invalid SignatureId');
            }
        }
        if($actid=="") 
        {
             return new ApiResponse($data->getActionId(),404,["Content-Type"=>"application/json"],'json','Invalid Action_Id');
        }
        $datatemplate = new DataTemplate;
        $datatemplate->setHtml($data->getHtml());
        $datatemplate->setCss($data->getCss());
        $datatemplate->setTitle($data->getTitle());
        $datatemplate->setActionId($actid);
        $datatemplate->setCode($refTabelRepo->next('emailtemp'));
        $countactivetemplate = $datatemprepo->findOneBy(['action_id'=>$data->getActionId(),'status'=>'Active']);
        if (!$countactivetemplate){
            $datatemplate->setStatus("Active");
        }else{
            $datatemplate->setStatus("Disabled");
        }
        $datatemplate->setFromTemplate($emailid);
        $datatemplate->setCcTemplate(null);
        $datatemplate->setBccTemplate(null);
        $datatemplate->setSubjectTemplate($data->getSubjectTemplate());
        $datatemplate->setSignature($signature);
        $datatemplate->setAttachmentTemplate($actid->getAttachmentEmail());
        $entityManager->persist($datatemplate);
        $entityManager->flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','successs!!');
    }
    
    /**
     * @Route("/api/email/gettemplate/{id}", name="gettemplate",methods={"GET"})
     */
    public function gettemplate_(Request $req,DataTemplateRepository $datatemprepo,$id)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $chkactionid = $datatemprepo->findOneBy(['id'=>$id]);
        if($chkactionid=="")
        {
          return new ApiResponse($chkactionid,404,["Content-Type"=>"application/json"],'json','invalid id!!');  
        }
        return new ApiResponse($chkactionid,200,["Content-Type"=>"application/json"],'json','success!!'); 
    }

     /**
     * @Route("/api/email/getactiontemplate/{id}", name="getactiontemplate",methods={"GET"})
     */
    public function getactiontemplate(Request $req,DataTemplateRepository $datatemprepo,$id)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $chkactionid = $datatemprepo->findBy(['action_id'=>$id]);
        if($chkactionid=="")
        {
          return new ApiResponse($chkactionid,404,["Content-Type"=>"application/json"],'json','invalid id!!');  
        }
        return new ApiResponse($chkactionid,200,["Content-Type"=>"application/json"],'json','success!!'); 
    }

    /**
     * @Route("/api/email/updatetemplate", name="updatetemplate",methods={"PUT"})
     */
    public function updatetemplate(Request $req,EmailActionRepository $emailactionrepo,DataTemplateRepository $datatemprepo,RefEmailidRepository $refemailrepo,RefSignatureRepository $sigrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        $values = $req->getContent();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer); 
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $serializer->deserialize($values, DataTemp::class, 'json');
        $chkid = $datatemprepo->findOneBy(['id'=>$data->getId()]);
        $chkactionid = $emailactionrepo->findOneBy(["id"=>$data->getActionId()]);
        $chkemailid = $refemailrepo->findOneBy(['id'=>$data->getFromTemplate()]);
        if($chkid == "" || $chkid == null)
        {
             return new ApiResponse($data->getId(),404,["Content-Type"=>"application/json"],'json','Invalid Id');
        }
        elseif($chkactionid == "")
        {
            return new ApiResponse($data->getActionId(),404,["Content-Type"=>"application/json"],'json','Invalid Action_Id');
        } 
        $chkid->setHtml($data->getHtml());
        $chkid->setCss($data->getCss());
        $chkid->setTitle($data->getTitle());
        $chkid->setFromTemplate($chkemailid);
        $chkid->setCcTemplate($data->getCcTemplate());
        $chkid->setBccTemplate($data->getBccTemplate());
        $chkid->setSubjectTemplate($data->getSubjectTemplate());
        $chkid->setActionId($chkactionid);
        if($data->getSignature()){
            $signature = $sigrepo->findOneBy(['id'=>$data->getSignature()]);
            if(!$signature) 
            {
                 return new ApiResponse($data->getActionId(),404,["Content-Type"=>"application/json"],'json','Invalid SignatureId');
            }
            $chkid->setSignature($signature);
        }
        $entityManager->persist($chkid);
        $entityManager->flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success!!');
        
    }

     /**
     * @Route("/api/emailtemplate/get/emailid", name="emailid",methods={"GET"})
     */
    public function emailid_(RefEmailidRepository $emailrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
            
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $text = $emailrepo->findAll();
        return new ApiResponse($text,200,["Content-Type"=>"application/json"],'json','success');  

    }

    /**
     * @Route("/api/type/getall", name="typeGetAll",methods={"GET"})
     */
    public function typeGetAll(RefTypeRepository $typerepo)
    {
        $text = $typerepo->findAll();
        return new ApiResponse($text,200,["Content-Type"=>"application/json"],'json','success');  

    }


    /**
     * @Route("/api/emailtemplate/activatetemplate/{action_id}/{temp_id}", name="activatetemplate",methods={"PUT"})
     */
    public function activateTemplate(Request $req,DataTemplateRepository $datatemprepo,EmailActionRepository $emailrepo,$action_id,$temp_id)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
            
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $temps = $datatemprepo->findBy(['action_id'=>$action_id]);
        foreach($temps as $temp){
            if($temp->getId()==$temp_id){
                $temp->setStatus("Active");
                $entityManager->persist($temp);
                $entityManager->flush();
            }
            else{
                $temp->setStatus("Disabled");
                $entityManager->persist($temp);
                $entityManager->flush();
            }
        }
        return new ApiResponse($temps,200,["Content-Type"=>"application/json"],'json','success');     
    }

     /**
     * @Route("/api/auth/generateotpV1/{email}", name="authandicationV1",methods={"GET"})
     */
    public function authandicationV1_($email,DataUserspaceRepository $userRepo,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,DataRequestRepository $datareqrepo,EmailServiceV1 $emailserv1,DataConnectionRepository $dataconnectionrepo)
    {
        $entityManager = $this->EM;
        $pub_key = file_get_contents('../pub.crt');
        $pri_key = file_get_contents('../pri.crt');
        $rsa = new RSA($pub_key, $pri_key);
        $email = $rsa->base64Decrypt(base64_decode($email));
        $chkemail = $userRepo->findOneBy(['email_us'=>$email,'active_us'=>"Active"]);
        $client = $datareqrepo->findOneBy(['id'=>$chkemail->getIdRequest()]);
        $clientdata = $client->getClient();
        $param = $clientdata->getId();
        if(!$chkemail){
             return new ApiResponse($chkemail,400,["Content-Type"=>"application/json"],'json',"your email id is invalid!!!!");
        }       
        $digits = 6;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
        $currenttime = time();
        $expirytime = time()+(60*5);
        $dataconnections = $dataconnectionrepo->findBy(['clientId'=>$param,'status'=>'Active']);
        foreach($dataconnections as $dataconnection){
            $dataconnection->setStatus("Disabled");
            $entityManager->persist($dataconnection);
            $entityManager->flush();
        }
        $dataconnection = new DataConnection();
        $dataconnection->setOtp($otp);
        $dataconnection->setDateTime((new \DateTime()));
        $dataconnection->setExpiryTime((new \DateTime())->setTimestamp($expirytime));
        $dataconnection->setCount(0);
        $dataconnection->setStatus("Active");
        $dataconnection->setClientId($param);
        $entityManager->persist($dataconnection);
        $entityManager->flush();
        $extras = new RefVariable();
        $extras->setVariablename("otp");
        $extras->setValue($otp);
        $data = $emailserv1->sendEmailWIthExtras($varrepo,$datatemprepo,$param,2,$email,[$extras]);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"msg send!!!!");
    }

    /**
     * @Route("/api/emailtemplate/insert/fieldissues/{clientId}", name="insertFieldIssues",methods={"POST"})
     */
    public function insertFieldIssues($clientId,Request $request,DataClientRepository $clientrepo,RefTypeRepository $typerpo,DataFieldIssueRepository $fieldissuerepo,RefFieldRepository $reffieldrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content,DataFieldIssues::class, 'json');
        $entityManager = $this->EM;
        $clientId = (int)$clientId;
        $chkclient = $clientrepo->findOneBy(["id"=>$clientId]);
        if(!$chkclient){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalidClient_id'); 
        }
        $chkfieldissue = $fieldissuerepo->findOneBy(['client'=>$clientId]);
        $version = 0;
        if($chkfieldissue){
            $chkfieldissue = $fieldissuerepo->findOneBy(['client'=>$clientId]);
            $q = "SELECT (version) FROM data_field_issue WHERE client_id = :clientId";
            $conn = $entityManager->getconnection();
            $result = $conn->executeQuery($clientId, ['id' => $clientId])->fetch();
            $version = $result[0]['max'];
        }
        $company_issue_table=null;
        $field_issue_table=null;
        $shareholder_issue_table=null;
        foreach ($data->getIssues() as $key => $value) {
            $datafieldissue = $serializer->deserialize( json_encode($value,JSON_NUMERIC_CHECK ), DataFieldIssue::class, 'json');
            $datafieldissue->setVersion($version+1);
            $datafieldissue->setClient($chkclient);
            $chkfield = $reffieldrepo->findOneBy(['id'=>$datafieldissue->getFieldId()]);
            $chktype = $typerpo->findOneBy(["id"=>$datafieldissue->getTypeId()]);
            if($chktype){
                $datafieldissue->setType($chktype);
            }
            if($chkfield){
            $datafieldissue->setField($chkfield);
            }
            if($datafieldissue->getUser()){
                $datafieldissue->setUser($datafieldissue->getUser());
            }
            $entityManager->persist($datafieldissue);
            $entityManager->flush();
        }   
        return new ApiResponse([$datafieldissue],200,["Content-Type"=>"application/json"],'json','success',['timezone','client']); 
    } 

    /**
     * @Route("/api/emailtemplate/insert/fieldissuesv1/{clientId}/{newversion}", name="insertFieldIssuesv1",methods={"POST"})
     */
    public function insertFieldIssuesv1($clientId, $newversion, Request $request,DataClientRepository $clientrepo,RefTypeRepository $typerpo,DataFieldIssueRepository $fieldissuerepo,RefFieldRepository $reffieldrepo,DataAttorneyRepository $attornyrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content,DataFieldIssues::class, 'json');
        $entityManager = $this->EM;
        $clientId = (int)$clientId;
        $chkclient = $clientrepo->findOneBy(["id"=>$clientId]);
        if (!$chkclient) {
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalidClient_id'); 
        }
        $version = 0;
        $datafieldissue = null;
        foreach ($data->getIssues() as $key => $value) {
            $datafieldissue = $serializer->deserialize( json_encode($value,JSON_NUMERIC_CHECK), DataFieldIssue::class, 'json');
            if ($datafieldissue->getAttorneyId()) {
                $chkfieldissue = $fieldissuerepo->findOneBy(['client'=>$clientId,'attorney'=>$datafieldissue->getAttorneyId()]);
                if ($chkfieldissue) {
                    $q = "SELECT MAX(version) as max FROM data_field_issue WHERE client_id = :clientId";
                    $conn = $entityManager->getConnection();
                    $result = $conn->executeQuery($q, ['clientId' => $clientId])->fetch();        
                    $version = $result[0]['max'];
                }
                $chkattorny = $attornyrepo->findOneBy(['id'=>$datafieldissue->getAttorneyId()]);
                if (!$chkattorny) {
                    return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid attornyId',['timezone','client']); 
                } 
                // Getting backfield
                $backfield = $reffieldrepo->findOneBy(['back_field' => $datafieldissue->getFieldName()]);                
                if (!$backfield) {
                    return new ApiResponse([$datafieldissue->getFieldName()],200,["Content-Type"=>"application/json"],'json','invalid backfield',['timezone','client']); 
                } else {
                    $datafieldissue->setFieldId($backfield->getId());
                }
                
                if ($newversion == 1) {
                    $datafieldissue->setVersion($version+1);
                } elseif ($newversion == 0) {
                    $datafieldissue->setVersion($version);
                }

                $datafieldissue->setClient($chkclient);
                if($datafieldissue->getUser()){
                    $datafieldissue->setUser($datafieldissue->getUser());
                }
                if($datafieldissue->getDescription()){
                    $datafieldissue->setDescription($datafieldissue->getDescription());
                }
                $datafieldissue->setAttorney($chkattorny);
                $chkfield = $reffieldrepo->findOneBy(['id'=>$datafieldissue->getFieldId()]);                         
                $chktype = $typerpo->findOneBy(["id"=>$datafieldissue->getTypeId()]);
                if ($chktype) {
                    $datafieldissue->setType($chktype);
                }
                if ($chkfield) {
                    $datafieldissue->setField($chkfield);
                }
                if ($datafieldissue->getDescFieldissue() !== null) {
                    $entityManager->persist($datafieldissue);
                }
                 
            } else {
                $chkfieldissue = $fieldissuerepo->findOneBy(['client'=>$clientId]);
                if ($chkfieldissue) {
                    $chkfieldissue = $fieldissuerepo->findOneBy(['client'=>$clientId]);
                    $conn = $entityManager->getConnection();
                    $q = "SELECT MAX(version) as max FROM data_field_issue WHERE client_id = :clientId";
                    $result = $conn->executeQuery($q, ['clientId' => $clientId])->fetch();        
                    $version = $result[0]['max'];
                }
                if ($newversion == 1) {
                    $datafieldissue->setVersion($version+1);
                } elseif ($newversion == 0) {
                    $datafieldissue->setVersion($version);
                }
                $datafieldissue->setClient($chkclient);
                if($datafieldissue->getUser()){
                    $datafieldissue->setUser($datafieldissue->getUser());
                }
                if($datafieldissue->getDescription()){
                    $datafieldissue->setDescription($datafieldissue->getDescription());
                }
                // Getting backfield
                $backfield = $reffieldrepo->findOneBy(['back_field' => $datafieldissue->getFieldName()]);                
                if (!$backfield) {
                    return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid field',['timezone','client']); 
                } else {
                    $datafieldissue->setFieldId($backfield->getId());
                }
                $chkfield = $reffieldrepo->findOneBy(['id'=>$datafieldissue->getFieldId()]);
                $chktype = $typerpo->findOneBy(["id"=>$datafieldissue->getTypeId()]);
                if($chktype){
                    $datafieldissue->setType($chktype);
                }
                if($chkfield){
                    $datafieldissue->setField($chkfield);
                }
                if ($datafieldissue->getDescFieldissue() !== null) {
                    $entityManager->persist($datafieldissue);
                }
            }
        }
        $entityManager->flush();
        return new ApiResponse([$datafieldissue],200,["Content-Type"=>"application/json"],'json','success',['timezone','client']); 
    }
        
    /**
     * @Route("/api/emailtemplate/get/fieldissuev1/{client_id}", name="fieldv1",methods={"GET"})
     */
    public function getFieldv1_(Request $request,$client_id,DataFieldIssueRepository $fieldissuerepo,ClientService $clientser)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $chkfieldissue = $fieldissuerepo->findOneBy(['client'=>$client_id]);
        if(!$chkfieldissue){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','client_id not in fieldissue');   
        }
        $nullvalue = true;
        $query = "SELECT * FROM data_field_issue WHERE client_id = :clientid AND (client_correction IS NULL OR true)"; 
        $conn = $entityManager->getConnection();
        $objects = $conn->executeQuery($query, ['clientid' => $client_id])->fetchAll();
        // $objects  = $objects->fetchAllAssociative();
        
        $array=array();
        foreach($objects as $object){
            if(!$object['attorney_id']){
                $q = "SELECT MAX(version) as max FROM data_field_issue WHERE client_id = :clientid";
                $conn = $entityManager->getConnection();
                $result = $conn->executeQuery($q, ['clientid' => $client_id])->fetch();
                $version = $result['max'];
                $q ="SELECT * FROM data_field_issue WHERE client_id = :clientid AND (client_correction IS NULL OR true)"; 
                $conn = $entityManager->getConnection();
                $fieldissue = $conn->executeQuery($q, ['clientid' => $client_id])->fetchAll();
                foreach($fieldissue as $field){
                    $fieldissue_id = $field['id'];  
                    array_push($array,$fieldissue_id);
                }
            }
            else{
                $q = "SELECT MAX(version) as max FROM data_field_issue WHERE client_id = :clientid AND attorney_id= :attid";
                $conn = $entityManager->getConnection();
                $result1 = $conn->executeQuery($q, ['clientid' => (int) $client_id,'attid' => (int) $object["attorney_id"]])->fetch();
                $version = $result1['max'];
                $fieldissue ="SELECT * FROM data_field_issue WHERE client_id = :clientid AND attorney_id= :attid AND (client_correction IS NULL OR true)"; 
                $conn = $entityManager->getConnection();
                $fieldissue = $conn->executeQuery($fieldissue, ['clientid' => (int) $client_id,'attid' => (int) $object["attorney_id"]])->fetchAll();
                foreach($fieldissue as $field){
                    $fieldissue_id = $field['id'];  
                    array_push($array,$fieldissue_id);
                    }
               
            }
           $value=array();
        }
        $data=array_unique($array);  
        $ids = implode(',',$data);
        $idarrays = array();
        $query = "select a.id from data_field_issue a , ref_field b where a.field_id = b.id and a.id in ($ids) order by b.row_id asc";
        $conn = $entityManager->getConnection();
        $fieldissue = $conn->executeQuery($query)->fetchAll();
        foreach($fieldissue as $field){
            $fieldissue_id = $field['id'];  
            array_push($idarrays,$fieldissue_id);
        }
        $fieldissues = array();
        foreach($idarrays as $idarray){
            $fieldissue = $fieldissuerepo->findOneBy(array('id'=>$idarray,'client'=>$client_id,'version'=>$version));
           if($fieldissue){
            array_push($fieldissues,$fieldissue);
           }
        }
        $formation=array();
        foreach($fieldissues as $field){
            if($field->getAttorney()){
              $boxid = $field->getField()->getBoxId();
              $fieldid = $field->getField()->getFieldId();
              $shareid = $field->getAttorney()->getId();
              $name = "$boxid-$fieldid-$shareid";
              $field->setAttorneyId($shareid);
              $field->setFieldMap(str_replace(' ', '',$name));
            }
            else{
                $boxid = $field->getField()->getBoxId();
                $fieldid = $field->getField()->getFieldId();
                $name = "$boxid-$fieldid";
                $field->setFieldMap(str_replace(' ', '',$name));
            }
        }
        return new ApiResponse($fieldissues,200,["Content-Type"=>"application/json"],'json','success',['timezone','client','attorney','__initializer__','__cloner__','__isInitialized__']);             
    }

    /**
     * @Route("/api/emailtemplate/get/fieldissueold/{client_id}", name="fieldold",methods={"GET"})
     */
    public function getFieldold(Request $request,$client_id,DataFieldIssueRepository $fieldissuerepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $chkfieldissue = $fieldissuerepo->findOneBy(['client'=>$client_id]);
        if(!$chkfieldissue){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','client_id not in fieldissue');   
        }
        $nullvalue = true;
        $query = "SELECT * FROM data_field_issue WHERE client_id = :clientid AND (client_correction IS NULL OR true)"; 
        $conn = $entityManager->getConnection();
        $objects = $conn->executeQuery($query, ['clientid' => (int) $client_id])->fetchAll();
        $array=array();
        foreach($objects as $object){
            if(!$object['attorney_id']){
                $q = "SELECT MAX(version) as max FROM data_field_issue WHERE client_id = :clientid";
                $conn = $entityManager->getConnection();
                $result = $conn->executeQuery($q, ['clientid' => $client_id])->fetch();
                $version = $result[0]["max"];
                $fieldissue ="SELECT * FROM data_field_issue WHERE client_id = :clientid AND (client_correction IS NULL OR true)"; 
                $conn = $entityManager->getConnection();
                $fieldissue = $conn->executeQuery($q, ['clientid' => $client_id])->fetchAll();
                foreach($fieldissue as $field){
                    $fieldissue_id = $field['id'];  
                    array_push($array,$fieldissue_id);
                }
            }
            else{
                $q = "SELECT MAX(version) as max FROM data_field_issue WHERE client_id = :clientid AND attorney_id = :attid";
                $conn = $entityManager->getConnection();
                $result = $conn->executeQuery($q, ['clientid' => (int)  $client_id,'attid'=> (int)  $object["attorney_id"]])->fetch();
                $version = $result[0]["max"];
                $query ="SELECT * FROM data_field_issue WHERE client_id = :clientid AND attorney_id = :attid AND (client_correction IS NULL OR true)"; 
                $conn = $entityManager->getConnection();
                $fieldissue = $conn->executeQuery($query, ['clientid'=> (int)  $client_id,'attid'=> (int)  $object["attorney_id"]])->fetchAll();
                foreach($fieldissue as $field){
                    $fieldissue_id = $field['id'];  
                    array_push($array,$fieldissue_id);
                    }
               
            }
           $value=array();
        }
        $data=array_unique($array);  
        $ids = implode(',',$data);
        $idarrays = array();
        $query = "select a.id from data_field_issue a , ref_field b where a.field_id = b.id and a.id in ($ids) order by a.version, b.row_id asc";
        $conn = $entityManager->getConnection();
        $fieldissue = $conn->executeQuery($query)->fetchAll();
        foreach($fieldissue as $field){
            $fieldissue_id = $field['id'];  
            array_push($idarrays,$fieldissue_id);
        }
        $fieldissues = array();
        foreach($idarrays as $idarray){
            $fieldissue = $fieldissuerepo->findOneBy(array('id'=>$idarray,'client'=>$client_id));
           if($fieldissue){
            array_push($fieldissues,$fieldissue);
           }
        }
        $formation=array();
        foreach($fieldissues as $field){
            if($field->getAttorney()){
              $boxid = $field->getField()->getBoxId();
              $fieldid = $field->getField()->getFieldId();
              $shareid = $field->getAttorney()->getId();
              $name = "$boxid-$fieldid-$shareid";
              $field->setAttorneyId($shareid);
              $field->setFieldMap(str_replace(' ', '',$name));
            }
            else{
                $boxid = $field->getField()->getBoxId();
                $fieldid = $field->getField()->getFieldId();
                $name = "$boxid-$fieldid";
                $field->setFieldMap(str_replace(' ', '',$name));
            }
        }
        return new ApiResponse($fieldissues,200,["Content-Type"=>"application/json"],'json','success',['timezone','client','attorney','__initializer__','__cloner__','__isInitialized__']);             
    }

    /**
     * @Route("/api/datafieldissue/clientcorrection", name="dataIssueClientCorretion",methods={"POST"})
     */
    public function dataIssueClientCorretion(DataFieldIssueRepository $dataissuerepo,Request $req, ClientService $clientser, Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $req->getContent();
        $data = $serializer->deserialize($content, Status::class, 'json');
        $entityManager = $this->EM;
        $ids = $data->getId();

        if(!$clientser->checkIssueId($ids, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $dataissues = $dataissuerepo->findBy(array('id'=>$ids));
        if(!$dataissues){
        return new ApiResponse($dataissues,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        foreach($dataissues as $dataissue){
            $dataissue->setClientCorrection(true);
            $entityManager->persist($dataissue);
            $entityManager->flush();
        }
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','updated success');    
    }

    /**
     * @Route("/api/email/insert", name="insertEmail",methods={"POST"})
     */
    public function insertEmail(Request $request)
    {
    $encoders = [ new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers ,$encoders);
    $content = $request->getContent();
    $data = $serializer->deserialize($content,RefEmailid::class, 'json');
    $entityManager = $this->EM;
    $email = new RefEmailid;
    $email->setStatus("Active");
    $email->setExpediteur($data->getExpediteur());
    $email->setDescription($data->getDescription());
    $entityManager->persist($email);
    $entityManager->flush();
    return new ApiResponse([$email],200,["Content-Type"=>"application/json"],'json','insert succesfully');   
    }

     /**
     * @Route("/api/email/update", name="updateEmail",methods={"PUT"})
     */
    public function updateEmail(Request $request,RefEmailidRepository $emailrepo)
    {

    $encoders = [ new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers ,$encoders);
    $content = $request->getContent();
    $email = $serializer->deserialize($content,RefEmailid::class, 'json');
    $entityManager = $this->EM;
    $chkemail = $emailrepo->findOneBy(['id'=>$email->getId()]);
    if(!$chkemail){
     return new ApiResponse([$email->getId()],200,["Content-Type"=>"application/json"],'json','invalid id');   
    }
    $chkemail->setExpediteur($email->getExpediteur());
    $chkemail->setDescription($email->getDescription());
    $entityManager->persist($chkemail);
    $entityManager->flush();
    return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','updated succesfully');   
    }

     /**
     * @Route("/api/email/getsingle/{id}", name="getSingleEmail",methods={"GET"})
     */
    public function getSingleEmail_(Request $request,$id,RefEmailidRepository $emailrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $chkemail = $emailrepo->findOneBy(['id'=>$id]);
        if(!$chkemail){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid id');   
        }
        return new ApiResponse($chkemail,200,["Content-Type"=>"application/json"],'json','invalid id');  
    }

      /**
     * @Route("/api/emailaction/getsingle/{id}", name="getSingleEmailAction",methods={"GET"})
     */
    public function getSingleEmailAction_(Request $request,$id,EmailactionRepository $emailrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $chkemail = $emailrepo->findOneBy(['id'=>$id]);
        if(!$chkemail){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid id');   
        }
        return new ApiResponse($chkemail,200,["Content-Type"=>"application/json"],'json','invalid id');  
    }

    /**
     * @Route("/api/email/getall", name="getAllEmail",methods={"GET"})
     */
    public function getAllEmail(Request $request,RefEmailidRepository $emailrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $chkemail = $emailrepo->findBy(['status'=>['Active','Enabled','Disabled']]);
        if(!$chkemail){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid id');   
        }
        return new ApiResponse($chkemail,200,["Content-Type"=>"application/json"],'json','invalid id');  
    }

    /**
     * @Route("/api/email/filter", name="emailFilter",methods={"POST"})
     */
    public function emailFilter(Request $request,RefEmailidRepository $emailrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrstatus = array();
        $arrstatus = $status->getStatus();
        $data = $emailrepo->findBy(array('status' => $arrstatus));
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 


     /**
     * @Route("/api/email/enabled/{id}", name="emailEnabled",methods={"PUT"})
     */
    public function emailEnabled(RefEmailidRepository $emailrepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $email = $emailrepo->findOneBy(['id'=>$id]);
        if(!$email){
        return new ApiResponse($email,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $email->setStatus('Active');
        $entityManager->persist($email);
        $entityManager->flush();
        return new ApiResponse($email,200,["Content-Type"=>"application/json"],'json','updated success');    
    }

    /**
     * @Route("/api/email/disabled/{id}", name="emailDisabled",methods={"PUT"})
     */
    public function emailDisabled(RefEmailidRepository $emailrepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $email = $emailrepo->findOneBy(['id'=>$id]);
        if(!$email){
        return new ApiResponse($email,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $email->setStatus('Disabled');
        $entityManager->persist($email);
        $entityManager->flush();   
        return new ApiResponse($email,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }

    /**
     * @Route("/api/email/deleted/{id}", name="emailDeleted",methods={"PUT"})
     */
    public function emailDeleted(RefEmailidRepository $emailrepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $email = $emailrepo->findOneBy(['id'=>$id]);
        if(!$email){
        return new ApiResponse($email,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $email->setStatus('Deleted');
        $entityManager->persist($email);
        $entityManager->flush();   
        return new ApiResponse($email,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }

    /**
     * @Route("/api/checkemail", name="checkEmail",methods={"POST"})
     */
    public function checkEmail_(Request $request,EmailServiceV1 $emailservice)
    {
        try{
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $email = $serializer->deserialize($content, CheckEmail::class, 'json');
        $transport =(new Swift_SmtpTransport('smtp.gmail.com',465,'ssl'))
        ->setUsername($email->getEmailId())
        ->setPassword($email->getPassword());
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message())
        ->setFrom($email->getEmailId())
        ->setTo($email->getEmailId())
        ->setBody("checkmail")
         ;
         if(!$result){
            throw new HttpException(400, "code is not valid.");    
          }
        }
         catch(\Exception $e){
            return new ApiResponse(["MSG"=>$e->getMessage()],400,["Content-Type"=>"application/json"],'json','Incorrect password or id');   
        }
        return new ApiResponse($result,200,["Content-Type"=>"application/json"],'json','success');   
    } 

    /**
     * @Route("/api/emailaction/filter", name="emailActionFilter",methods={"POST"})
     */
    public function emailActionFilter_(Request $request,EmailActionRepository $emailactionrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $arrstatus = array();
        $isautomaticarray = array();
        $arrstatus = $status->getStatus();
        $isautomaticarray = $status->getIsAutomatic();
        $datas = $emailactionrepo->findBy(
            ['status' =>   $arrstatus,'is_automatic' =>$isautomaticarray ]
     
        );
        foreach($datas as $data){
            $emailtemplate = $emailactionrepo->findOneBy(["id"=>$data->getRoot()]);
            $data->setTemplateName($emailtemplate->getDescEmailaction());
        }
        return new ApiResponse($datas,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/emailaction/insert", name="InsertEmailAction",methods={"PUT"})
     */
    public function InsertEmailAction(Request $req,EmailActionRepository $emailactionrepo,DataTemplateRepository $datatemprepo,RefEmailidRepository $refemailrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        $values = $req->getContent();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer); 
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $serializer->deserialize($values, EmailAction::class, 'json');
        $chkactionid = new EmailAction;
        $chkactionid->setTitle($data->getTitle());
        $chkactionid->setDescEmailaction($data->getDescEmailaction());
        $chkactionid->setStatus("Active");
        $chkactionid->setIsAutomatic($data->getIsAutomatic());
        $chkactionid->setIsStatuschange($data->getIsStatuschange());
        $chkactionid->setFromStatus($data->getFromStatus());
        $chkactionid->setToStatus($data->getToStatus());
        $root=null;
        if($data->getRoot()){
          $root = $data->getRoot();
        }
        $chkactionid->setRoot($root); 
        $entityManager->persist($chkactionid);
        $entityManager->flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success!!');
        
    }

     /**
     * @Route("/api/emailaction/update", name="updateEmailAction",methods={"PUT"})
     */
    public function updateEmailAction_(Request $req,EmailActionRepository $emailactionrepo,DataTemplateRepository $datatemprepo,RefEmailidRepository $refemailrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        $values = $req->getContent();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer); 
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $serializer->deserialize($values, EmailAct::class, 'json');
        $chkactionid = $emailactionrepo->findOneBy(["id"=>$data->getId()]);
       
        if($chkactionid=="")
        {
            return new ApiResponse($data->getId(),404,["Content-Type"=>"application/json"],'json','Invalid Action_Id');
        }
        $chkactionid->setTitle($data->getTitle());
        $chkactionid->setDescEmailaction($data->getDescEmailaction());
        $chkactionid->setStatus($data->getStatus());
        $chkactionid->setIsAutomatic($data->getIsAutomatic());
        $chkactionid->setIsStatuschange($data->getIsStatuschange());
        $chkactionid->setFromStatus($data->getFromStatus());
        $chkactionid->setToStatus($data->getToStatus());
        $entityManager->persist($chkactionid);
        $entityManager->flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success!!');
        
    }


     /**
     * @Route("/api/emailaction/titlelist", name="titleList",methods={"GET"})
     */
    public function titleList(Request $request,EmailActionRepository $emailactionrepo)
    {
        $chkemail = $emailactionrepo->findBy(['is_automatic'=>'H']);
        if(!$chkemail){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');   
           }
           return new ApiResponse($chkemail,200,["Content-Type"=>"application/json"],'json','success');  
    }

     /**
     * @Route("/api/emailaction/sublist/{id}", name="subList",methods={"GET"})
     */
    public function subList($id,EmailActionRepository $emailactionrepo)
    {
        $chkemail = $emailactionrepo->findBy(['root'=>$id]);
        if (!$chkemail) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');   
        }
        return new ApiResponse($chkemail,200,["Content-Type"=>"application/json"],'json','success');  
    }
    
    /**
     * @Route("/api/getemailcontent", name="getEmailContent",methods={"GET"})
     */
    public function getEmailContent(EmailContentRepository $emailcontentrepo){
        $entityManager = $this->EM; 
        $datas = $emailcontentrepo->findBy(['status'=>'pending']);
        foreach($datas as $data){
            $data->setStatus("sended");
            $entityManager->persist($data);
            $entityManager->flush();
        }
        return new ApiResponse($datas,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client','dataRequestRequeststatuses']);        
    }

    /**
     * @Route("/api/mailsend", name="mailsend",methods={"GET"})
     */
    public function mailsend(EmailServiceV1 $emailservice)
    {
       $data = $emailservice->samplemail();
       return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client','dataRequestRequeststatuses']);        

    }

     /**
     * @Route("/api/mail/automatic", name="AutomaticMail",methods={"GET"})
     */
    public function AutomaticMail(EmailServiceV1 $emailservice, EloquaService $eloquaservice, IntegrationService $integration, ApiHelper $api, DataClientRepository $clientrepo, DataAttorneyRepository $attorneyrepo, DataUserspaceRepository $userspacerepo,  DataContactRepository $datacontrepo, OptionsTabelRepository $prodrepo, DataClientSabRepository $sabrepo, ContraliaService $contralia, DocumentService $ds, DataReq_ReqstatusService $datareqreq)
    {
       $data = $emailservice->RefEmailAutomatic($datareqreq, $eloquaservice, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $ds);
       return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client','dataRequestRequeststatuses']);        

    }
	
	/**
     * @Route("/api/mail/automatictest", name="AutomaticMail",methods={"GET"})
     */
    public function AutomaticMailTest(EmailServiceV1 $emailservice, EloquaService $eloquaservice, IntegrationService $integration, ApiHelper $api, DataClientRepository $clientrepo, DataAttorneyRepository $attorneyrepo, DataUserspaceRepository $userspacerepo,  DataContactRepository $datacontrepo, OptionsTabelRepository $prodrepo, DataClientSabRepository $sabrepo, ContraliaService $contralia, DocumentService $ds, DataReq_ReqstatusService $datareqreq)
    {
       $data = $emailservice->RefEmailAutomaticTest($datareqreq, $eloquaservice, $integration, $api, $clientrepo, $attorneyrepo, $userspacerepo,  $datacontrepo, $prodrepo, $sabrepo, $contralia, $ds);
       return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client','dataRequestRequeststatuses']);        

    }

     /**
     * @Route("api/email/resend/{email}", name="resendMail",methods={"GET"})
     */
    public function resendMail($email,DataUserspaceRepository $userRepo,RefVariableRepository $varrepo,DataTemplateRepository $datatemprepo,DataRequestRepository $datareqrepo,EmailServiceV1 $emailserv1,DataConnectionRepository $dataconnectionrepo)
    {
        
        $data = $emailserv1->ResendMail($varrepo,$datatemprepo,$email);
        if($data == "ok"){
            return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"success",['timezone','client','dataRequestRequeststatuses']);        
        }
        return new ApiResponse([$data],400,["Content-Type"=>"application/json"],'json',"ko",['timezone','client','dataRequestRequeststatuses']);        

    }
} 

