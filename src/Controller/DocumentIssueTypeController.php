<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use App\Service\DocumentIssueTypeService;
use App\Repository\RefDocumentIssueTypeRepository;
use App\Repository\DataFieldIssueRepository;
use App\UtilsV3\Master\Status;
use App\Utils\ApiResponse;
use App\Entity\RefDocumentIssueType;
use Doctrine\ORM\EntityManagerInterface;
class DocumentIssueTypeController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

     /**
     * @Route("/api/documentissuetype/insert", name="documentTypeIssueInsert",methods={"POST"})
     */
    public function documentTypeIssueInsert_(DocumentIssueTypeService $documentissueservice,Request $request)
    {
 
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $documentissueservice->insertDocumentTypeIssue($serializer,$content,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
      
    }

      /**
     * @Route("/api/documentissuetype/update/{id}", name="documentTypeIssueUpdate",methods={"PUT"})
     */
    public function documentTypeIssueUpdate_(DocumentIssueTypeService $documentissueservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $serializer->deserialize($content, RefDocumentIssueType::class, 'json');
        $result = $documentissueservice->updateDocumentTypeIssue($serializer,$data,$entityManager,$id);
        return new ApiResponse([$result],200,["Content-Type"=>"application/json"],'json','updated success');    
    }


    /**
     * @Route("/api/documentissuetype/get/{id}/{lang}", name="getDocumentTypeIssue",methods={"GET"})
     */
    public function getDocumentTypeIssue_(DocumentIssueTypeService $documentissueservice,Request $request,$id,$lang)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $countryrepo = $this->EM->getRepository(RefDocumentIssueType::class);
        $data = $documentissueservice->getSingleDocumentTypeIssue($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   
    
    /**
     * @Route("/api/documentissuetype/getAll/{lang}", name="getAllDocumentTypeIssue",methods={"GET"})
     */
    public function getAllDocumentTypeIssue(DocumentIssueTypeService $documentissueservice,Request $request,$lang)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;                           
        $documentissuerepo = $this->EM->getRepository(RefDocumentIssueType::class);
        $data = $documentissueservice->getAllDocumentTypeIssue($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    
    
    /**
     * @Route("/api/documentissuetype/filter", name="documentIssueTypeFilter",methods={"POST"})
     */
    public function documentIssueTypeFilter_(DocumentIssueTypeService $documentissueservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $documentissueservice->filterDocumentIssueType($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

   /**
     * @Route("/api/documentissuetype/enabled/{id}", name="documentIssueTypeEnabled",methods={"PUT"})
     */
    public function documentIssueTypeEnabled(RefDocumentIssueTypeRepository $documentissuerepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $documentissue = $documentissuerepo->findOneBy(['id'=>$id]);
        if(!$documentissue){
        return new ApiResponse($documentissue,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $documentissue->setActiveDocumentissue('Active');
        $entityManager->persist($documentissue);
        $entityManager->flush();
        return new ApiResponse($documentissue,200,["Content-Type"=>"application/json"],'json','updated success');    
      
    }

     /**
     * @Route("/api/documentissuetype/disabled/{id}", name="documentIssueTypeDisabled",methods={"PUT"})
     */
    public function documentIssueTypeDisabled(RefDocumentIssueTypeRepository $documentissuerepo,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $documentissue = $documentissuerepo->findOneBy(['id'=>$id]);
        if(!$documentissue){
        return new ApiResponse($documentissue,400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
        $documentissue->setActiveDocumentissue('Disabled');
        $entityManager->persist($documentissue);
        $entityManager->flush();
        return new ApiResponse($documentissue,200,["Content-Type"=>"application/json"],'json','updated success');    
    }

}
