<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use App\UtilsV3\Master\Status;
use App\Repository\RefFieldRepository;
use App\Repository\DataRequestRepository;
use App\UtilsV3\Validation\Field;
use App\UtilsV3\Validation\DataField;
use App\Service\FieldService;
use App\Service\DataRequestService;
use App\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
class FieldValidationController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/field/validation", name="field_validation")
     */
    public function index()
    {
        return $this->render('field_validation/index.html.twig', [
            'controller_name' => 'FieldValidationController',
        ]);
    }

     /**
     * @Route("/api/fieldvalidation/insert", name="fieldcreate",methods={"POST"})
     */
    public function fieldCreate_(Request $request,FieldService $fieldservice)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $fielddata = $serializer->deserialize($content,Field::class, 'json');
        $data = $fieldservice->insert($fielddata,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','inserted successfully');    
    }
     /**
     * @Route("/api/fieldvalidation/update", name="fieldupdate",methods={"PUT"})
     */
    public function fieldUpdate(Request $request,FieldService $fieldservice)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $fielddata = $serializer->deserialize($content,Field::class, 'json');
        $data = $fieldservice->update($fielddata,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','updated successfully');    
    }

    /**
     * @Route("/api/fieldvalidation/getsingle/{id}/{langvalue}", name="getref_field")
     */
    public function getRef_Field_(Request $request,$id,$langvalue,FieldService $fieldservice)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $fieldservice->getSingle($langvalue,$id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');     
    }

     /**
     * @Route("/api/fieldvalidation/getAll/{langvalue}", name="getAllref_field")
     */
    public function getAllRef_Field_(Request $request,$langvalue,FieldService $fieldservice)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $fieldservice->getAll($langvalue);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');     
    }

     /**
     * @Route("/api/fieldvalidation/filter", name="fieldValidationFilter",methods={"POST"})
     */
    public function fieldValidationFilter_(FieldService $fieldservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $fieldservice->filterFieldValidation($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 
    /**
     * @Route("/api/fieldvalidation/enabled/{id}", name="fieldValidationEnabled",methods={"PUT"})
     */
    public function fieldValidationEnabled(RefFieldRepository $fieldrepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $fieldvalidation = $fieldrepo->findOneBy(['id'=>$id]);
        if(!$fieldvalidation){
        return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $fieldvalidation->setActiveField('Active');
        $entityManager->persist($fieldvalidation);
        $entityManager->flush();
        return new ApiResponse($fieldvalidation,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }
   /**
     * @Route("/api/fieldvalidation/deleted/{id}", name="fieldValidationDeleted",methods={"PUT"})
     */
    public function fieldValidationDeleted(RefFieldRepository $fieldrepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $fieldvalidation = $fieldrepo->findOneBy(['id'=>$id]);
        if(!$fieldvalidation){
        return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $fieldvalidation->setActiveField('Deleted');
        $entityManager->persist($fieldvalidation);
        $entityManager->flush();
        return new ApiResponse($fieldvalidation,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }
    /**
     * @Route("/api/fieldvalidation/disabled/{id}", name="fieldValidationDisabled",methods={"PUT"})
     */
    public function fieldValidationDisabled(RefFieldRepository $fieldrepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $fieldvalidation = $fieldrepo->findOneBy(['id'=>$id]);
        if(!$fieldvalidation){
        return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $fieldvalidation->setActiveField('Disabled');
        $entityManager->persist($fieldvalidation);
        $entityManager->flush();
       
        return new ApiResponse($fieldvalidation,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }
    /**
     * @Route("/api/validaton/Datafield/create", name="dataFieldCreate",methods={"POST"})
     */
    public function dataFieldCreate(Request $request,FieldService $fieldservice)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $fielddata = $serializer->deserialize($content,DataField::class, 'json');
        $data = $fieldservice->dataFieldInsert($fielddata,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
    }

    /**
     * @Route("/api/validaton/getDatafield/{id}", name="getDatafield",methods={"GET"})
     */
    public function getDatafield(Request $request,FieldService $fieldservice,DataRequestService $datareq,DataRequestRepository $datarepo,$id)
    {
        $data = $fieldservice->getDataField($id,$datareq,$datarepo);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');    
    }

    /**
     * @Route("/api/alertstatus/filter", name="alertstatusFilter",methods={"POST"})
     */
    public function alertStatusFilter_(FieldService $fieldservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $fieldservice->filterAlertStatus($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 


    /**
     * @Route("/api/document/filter", name="documentFilter",methods={"POST"})
     */
    public function documentFilter_(FieldService $fieldservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $fieldservice->filterDocument($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 
    /**
     * @Route("/api/fieldvalidation/filter/{Client_id}", name="fieldValidationFilterUsingClient",methods={"POST"})
     */
    public function fieldValidationFilterUsingClient_($Client_id,FieldService $fieldservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $fieldservice->filterFieldValidationUsingClient($Client_id,$status);
        if($data=="ko"){
            return new ApiResponse($data,400,["Content-Type"=>"application/json"],'json','Invalid Clientid');   
        }
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

}
