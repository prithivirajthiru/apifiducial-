<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use App\Repository\DataDocumentactionRepository;
use App\Repository\DocumentTemplateRepository;
use App\Repository\DocumentTemplatepageRepository;
use App\Repository\RefVariableRepository;
use App\Repository\DataDocumenttypeRepository;
use App\Repository\DataDocumentRepository;
use App\Repository\RefDocumentActionRepository;
use App\UtilsV3\DocTemp;
use App\UtilsV3\DocumentAction;
use App\UtilsV3\DocTempPage;
use App\UtilsV3\DocumentType;
use App\UtilsV3\Document;
use App\Utils\ApiResponse;
use App\Entity\DataDocumentaction;
use App\Entity\DocumentTemplate;
use App\Entity\DocumentTemplatePage;
use App\Entity\DataDocumenttype;
use App\Entity\DataDocument;
use App\Service\EmailServiceV1;
use PhpOffice\PhpWord\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;

class DocumentActionController extends AbstractController
{

    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/document/action", name="document_action")
     */
    public function index()
    {
        return $this->render('document_action/index.html.twig', [
            'controller_name' => 'DocumentActionController',
        ]);
    }
    
     /**
     * @Route("/api/documentaction/getall", name="getDocumentAction",methods={"GET"})
     */
    public function getDocumentAction_(Request $req,RefDocumentActionRepository $documentactionrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        $content = $req->getContent();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $documentactionrepo->findBy(['status'=>['Active','Disabled']]);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');       
    }

    /**
     * @Route("/api/documentaction/getsingle/{id}", name="getSingleDocumentAction",methods={"GET"})
     */
    public function getSingleDocumentAction_(Request $req,RefDocumentActionRepository $documentactionrepo,$id)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        $content = $req->getContent();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $documentactionrepo->findOneBy(['id'=>$id]);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }

     /**
     * @Route("/api/documentaction/update", name="updateDocumentAction",methods={"PUT"})
     */
    public function updateDocumentAction_(Request $req,RefDocumentActionRepository $documentactionrepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $req->getContent();
        $entityManager = $this->EM;
        $datadoc = $serializer->deserialize($content, DocumentAction::class, 'json');

       $documentaction=$documentactionrepo->findOneBy(['id'=>$datadoc->getId()]);
       if(!$documentaction){
        return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');
       }
       $documentaction->setName($datadoc->getName());
       $documentaction->setDescDocumentAction($datadoc->getDescDocumentAction());
       $documentaction->setDocumentUrl($datadoc->getDocumentUrl());
       $entityManager->persist($documentaction);
       $entityManager->flush();
       return new ApiResponse($documentaction,200,["Content-Type"=>"application/json"],'json','update successfully');

    }
}
