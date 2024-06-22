<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\RefLabel;
use App\Utils\ApiHelper;
use App\UtilsV3\DocTemp;
use App\UtilsV3\Document;
use App\Utils\ApiResponse;
use App\Entity\RefDocument;
use App\Entity\DataDocument;
use App\UtilsV3\DocTempPage;
use Spipu\Html2Pdf\Html2Pdf;
use App\Service\FileUploader;
use App\UtilsV3\DocumentType;
use App\UtilsV3\Master\Status;
use App\Service\EmailServiceV1;
use App\UtilsV3\DocumentAction;
use PhpOffice\PhpWord\Settings;
use App\Entity\DataDocumenttype;
use App\Entity\DocumentTemplate;
use App\Service\DocumentService;
use \PhpOffice\PhpWord\IOFactory;
use App\Entity\DataDocumentaction;
use App\UtilsV3\DocumentSignature;
use App\Entity\DocumentTemplatePage;
use App\Entity\RefDocumentSignature;
use Symfony\Component\Finder\Finder;
use App\Repository\RefLabelRepository;
use App\Repository\RefTableRepository;
use \PhpOffice\PhpWord\TemplateProcessor;
use App\Repository\RefDocumentRepository;
use App\Repository\RefLanguageRepository;
use App\Repository\RefVariableRepository;
use App\Repository\DataDocumentRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DataDocumenttypeRepository;
use App\Repository\DocumentTemplateRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RefDocumentActionRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DataDocumentactionRepository;
use App\Repository\DocumentTemplatepageRepository;
use App\Repository\RefDocumentSignatureRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
class DocumentController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/document", name="document")
     */
    public function index()
    {
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }

    /**
     * @Route("/api/alldocumenttype", name="alldocumenttype",methods={"GET"})
     */
    public function getalldocumenttype(Request $req,DataDocumenttypeRepository $doctyperepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        $content = $req->getContent();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $doctype = $doctyperepo->findAll();
        return new ApiResponse($doctype,200,["Content-Type"=>"application/json"],'json','success');
    }

    /**
     * @Route("/api/document/createdocument", name="createdocument",methods={"POST"})
     */
    public function createdocument(Request $request,DataDocumenttypeRepository $doctyperepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $datadoc = $serializer->deserialize($content, Document::class, 'json');
        $datatype = $doctyperepo->findOneBy(['id'=>$datadoc->getDataDocumenttype()]);
        if(!$datatype){
            return new ApiResponse($datatype,401,["Content-Type"=>"application/json"],'json','invalid doctype id');
        }
        $doc = new DataDocument; 
        $doc->setStatus('active');
        $doc->setDocumentName($datadoc->getDocumentName());
        $doc->setDataDocumenttype($datatype);
        $doc->setHtml($datadoc->getHtml());
        $doc->setCss($datadoc->getCss());
        $doc->setCreatedOn(new \DateTime());
        $entityManager->persist($doc);
        $entityManager->flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');

    }

     /**
     * @Route("/api/getdocuments/{id}", name="getdocuments",methods={"GET"})
     */
    public function getdocuments(Request $req,DataDocumentRepository $docrepo,$id)
    {

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        $content = $req->getContent();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $doc = $docrepo->findBy(['data_documenttype'=>$id]);
        if(!$doc){
            return new ApiResponse($doc,401,["Content-Type"=>"application/json"],'json','invalid id');
        }
        return new ApiResponse($doc,200,["Content-Type"=>"application/json"],'json','success');
    }
   
    /**
     * @Route("/api/document/getsingle/{id}/{langvalue}", name="documentGetSingle",methods={"GET"})
     */
    public function documentGetSingle(Request $req,RefDocumentRepository $docrepo,$id,DocumentService $docservice,$langvalue)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $req->getContent();
        $data = $docservice->getSingle($langvalue,$id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');
    }

     /**
     * @Route("/api/document/filter", name="RefDocumentFilter",methods={"POST"})
     */
    public function refDocumentFilter(RefDocumentRepository $docrepo,Request $request,DocumentService $docservice)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $docservice->filter($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

    /**
     * @Route("/api/document/insert", name="documentInsert",methods={"POST"})
     */
    public function documentInsert(Request $request,RefDocumentRepository $docrepo,RefLanguageRepository $refLangLabelRepo ,RefLabelRepository $refLabelRepo,RefTableRepository $refTabelRepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $document = $serializer->deserialize($content, RefDocument::class, 'json');
        $entityManager = $this->EM;
        $document->setActiveDocument("Active");
        if($document->getCodeDocument()==""){
            $document->setCodeDocument($refTabelRepo->next('ref_document'));           
        }
        $secdocument = $docrepo->find($document->getCodeId()? $document->getCodeId() : -1);
        if(!$secdocument){
            $secdocument = $document;
        }else{
            $secdocument->setRefLabels($document->getRefLabels());
        }      
        foreach( $secdocument->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secdocument->getCodeDocument(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
        }
        $refLabelTemp = new RefLabel();
        $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secdocument->getCodeDocument());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secdocument->setMandatoryDocument($document->getMandatoryDocument());
        $secdocument->setDescDocument($document->getDescDocument());
        $entityManager->persist($secdocument);
        $entityManager->flush();  
        return new ApiResponse($secdocument,200,["Content-Type"=>"application/json"],'json','Added successfully');    

    }


     /**
     * @Route("/api/document/update", name="documentUpdate",methods={"POST"})
     */
    public function documentUpdate(Request $request,RefDocumentRepository $docrepo,RefLanguageRepository $refLangLabelRepo ,RefLabelRepository $refLabelRepo,RefTableRepository $refTabelRepo)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $document = $serializer->deserialize($content, RefDocument::class, 'json');
        $data = $docrepo->findOneBy(['id'=>$document->getId()]);
        $entityManager = $this->EM;
        $data->setActiveDocument($document->getActiveDocument());
        foreach( $document->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$data->getCodeDocument(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
        }
        $refLabelTemp = new RefLabel();
        $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($data->getCodeDocument());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $data->setMandatoryDocument($document->getMandatoryDocument());
        $data->setDescDocument($document->getDescDocument());        
        $entityManager->persist($data);
        $entityManager->flush();  
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','updated successfully');    

    }


     /**
     * @Route("/api/documentsignature/update", name="documentSignatureUpdate",methods={"PUT"})
     */
    public function documentSignatureUpdate(Request $request,RefDocumentRepository $docrepo,RefDocumentSignatureRepository $docsigrepo,RefLanguageRepository $refLangLabelRepo ,RefLabelRepository $refLabelRepo,RefTableRepository $refTabelRepo,DocumentService $docservice)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $document = $serializer->deserialize($content, DocumentSignature::class, 'json');
        $entityManager = $this->EM;
        $date = new \DateTime();
        $url=$document->getUrlDocument();
        $date = date('d-m-Y_H-i-s');
        $finalurl = $url.'_'.$date;
        if(!$document->getId()){
            $chkdocumentsignature = new RefDocumentSignature();
        }
        else{
            $chkdocumentsignature = $docsigrepo->findOneBy(['id'=>$document->getId()]);
        }
        $chkdocument = $docrepo->findOneBy(['id'=>$document->getDocument()]);
        $langvalue = 'all';
        if(!$chkdocumentsignature){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid document_signature_id');    
        }
        if(!$chkdocument){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid document_id');    
        }
        $data = $docservice->getSingle($langvalue,$chkdocument->getId());
        $chkdocumentsignature->setUrlDocument($finalurl);
        $chkdocumentsignature->setDocument($data);
        $entityManager->persist($chkdocumentsignature);
        $entityManager->flush(); 
        if($document->getId()){
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','successfully updated');    
        }
        else{
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','successfully added');    
        }

    }

     /**
     * @Route("/api/documentsignature/getall/{langvalue}", name="documentSignatureGetAll",methods={"GET"})
     */
    public function documentSignatureGetAll(Request $req,RefDocumentRepository $docrepo,DocumentService $docservice,$langvalue,RefDocumentSignatureRepository $docsigrepo)
    {

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $req->getContent();
        $documents = $docrepo->findBy(['active_document'=>['Active','Disabled']]);
        $arraydoc = array();
        foreach($documents as $document){
        $data = $docservice->getSingle($langvalue,$document->getId());
        $documentsognature = $docsigrepo->findBy(['document'=>$data->getId()]);
        $data->setDocumentSignature($documentsognature);   
        array_push($arraydoc,$data);
        }
        return new ApiResponse($arraydoc,200,["Content-Type"=>"application/json"],'json','success');    

    }

     /**
     * @Route("/api/documentsignature/getsingle/{id}/{langvalue}", name="documentGetSinglee",methods={"GET"})
     */
    public function documentSignatureGetSinglee(Request $req,RefDocumentRepository $docrepo,DocumentService $docservice,$langvalue,RefDocumentSignatureRepository $docsigrepo,$id)
    {

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $req->getContent();
        $document = $docrepo->findOneBy(['id'=>$id]);
        $arraydoc = array();
        $data = $docservice->getSingle($langvalue,$document->getId());
        $documentsognature = $docsigrepo->findBy(['document'=>$data->getId()]);
        $data->setDocumentSignature($documentsognature);   
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');    
    }

    /**
     * @Route("/api/documentnames/{client_id}", name="documentNames",methods={"GET"})
     */
    public function documentNames(Request $req, RefDocumentActionRepository $docact, RefVariableRepository $varrepo,EmailServiceV1 $emailservice,$client_id,FileUploader $uploader, DocumentService $documentMaker, ApiHelper $api)
    {   
        print_r($documentMaker->makeContractDocument($client_id, $emailservice));       

        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');          
    }
     /**
     * @Route("/api/samplepdfchk/{client_id}", name="samplepdfchk",methods={"GET"})
     */
    public function samplepdfchk(EmailServiceV1 $emailservice,$client_id)
    {   
     $emailservice->makesampleDocument($client_id);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');          
    }
}
