<?php
namespace App\Service;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\RefDocumentIssueType;



use App\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use App\Entity\RefTable;

class DocumentIssueTypeService 
{

    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }

    public function insertDocumentTypeIssue($serializer,$content,$entityManager){

        $documentissue            =  $serializer->deserialize($content, RefDocumentIssueType::class, 'json');
        $documentissue            -> setActiveDocumentissue("Active");
        $refTabelRepo             =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo             =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo         =  $this->EM->getRepository(RefLanguage::class);
        $DocumentTypeIssueRepo    =  $this->EM->getRepository(RefDocumentIssueType::class);
    
        $documentissue  ->  setCodeDocumentIssue($refTabelRepo->next('ref_document_issue_type'));            
        $documentissue  ->  setDescDocumentissue($documentissue->getDescDocumentissue());
        $documentissue  ->  setName($documentissue->getName());
      
        foreach( $documentissue  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($documentissue->getCodeDocumentIssue());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($documentissue);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      public function updateDocumentTypeIssue($serializer,$data,$entityManager,$id){
       
        $refTabelRepo             =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo             =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo         =  $this->EM->getRepository(RefLanguage::class);
        $DocumentTypeIssueRepo    =  $this->EM->getRepository(RefDocumentIssueType::class);
        $documentissue            =  $DocumentTypeIssueRepo->findOneBy(['id'=>$id]);
        if(!$documentissue){
          return " id not found";
        }
      
        $documentissue  ->  setDescDocumentissue($data->getDescDocumentissue());
        $documentissue  ->  setName($data->getName());
      
        foreach($data ->  getRefLabels() as $key => $reflabel){
            $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$documentissue->getCodeDocumentIssue(),'lang_label'=>$lang->getId()]);
            if (!$chkRefLabel){
              $chkRefLabel = new RefLabel;
              $chkRefLabel    ->  setLangLabel($lang);
              $chkRefLabel    ->  setCodeLabel($documentissue->getCodeDocumentIssue());
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $chkRefLabel    ->  setActiveLabel("Active");
              $entityManager  ->  persist($chkRefLabel);
              $entityManager  ->  flush();
          }
          else{
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
              $entityManager  ->  flush();
          }
          }
       
        $entityManager -> persist($documentissue);
        $entityManager -> flush();  
        return "successfully added";   
      
      }


      public function getSingleDocumentTypeIssue(int $id,string $langvalue): ?RefDocumentIssueType{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo        =  $this  ->EM ->  getRepository(RefLabel::class);        
        $DocumentIssueTypeRepo      =  $this  ->EM ->  getRepository(RefDocumentIssueType::class);
        $paticularDocumentIssueTypeRepo =  $DocumentIssueTypeRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularDocumentIssueTypeRepo->getCodeDocumentIssue()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularDocumentIssueTypeRepo -> setRefLabels($refLabels); 
                return $paticularDocumentIssueTypeRepo;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularDocumentIssueTypeRepo -> getCodeDocumentIssue()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularDocumentIssueTypeRepo -> setRefLabel($label -> getLabelLabel());
        return $paticularDocumentIssueTypeRepo;
      }
      
  public function getAllDocumentTypeIssue(string $langvalue) {
    $langRepo      =  $this->EM->getRepository(RefLanguage::class);
    $lang          =  $langRepo-> findOneBy(['code_language'=>$langvalue]);
    if (!$lang) {
      if ($langvalue != "all") {
        return ["msg"  =>  "lang not found"];
      }
    }
      
    $labelRepo            =   $this->EM->getRepository(RefLabel::class);        
    $DocumentIssueTypeem  =   $this->EM->getRepository(RefDocumentIssueType::class);          
    $DocumentIssueTypes   =   $DocumentIssueTypeem->findBy(['active_documentissue'=>['Active']]);
    $languages            =   $langRepo->findAll();
    if ($langvalue == "all") {
      foreach($DocumentIssueTypes as  $DocumentIssueTypekey => $DocumentIssueType){
        $refLabels = array();
        foreach($languages as $language){
          $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $DocumentIssueType -> getCodeDocumentIssue()]);
          if (!$label){
            $refLabels[$language -> getCodeLanguage()]="";
            continue;
          }
          $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
        }
        $DocumentIssueTypes[$DocumentIssueTypekey] -> setRefLabels($refLabels);
      }
      return $DocumentIssueTypes;
    }
    
    foreach($DocumentIssueTypes as $key => $DocumentIssueType) {
      $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $DocumentIssueType -> getCodeDocumentIssue()]);
      if (!$label) {
        return ["msg"=>"label not found"];
      }
      $DocumentIssueTypes[$key] -> setRefLabel($label -> getLabelLabel());
    }
    return $DocumentIssueTypes;            
  }

  public function filterDocumentIssueType($status) {  
    $langRepo   = $this->EM->getRepository(RefLanguage::class);    
    $labelRepo  = $this->EM->getRepository(RefLabel::class);
    $arrstatus          =  array();
    $DocumentIssueType  =  array();
    $arrstatus          =  $status->getStatus();
    $DocumentIssueTypeRepo =  $this->EM->getRepository(RefDocumentIssueType::class);
    $DocumentIssueTypes    =  $DocumentIssueTypeRepo->findBy(array('active_documentissue' => $arrstatus));

    $languages = $langRepo->findAll();
    // if($langvalue  ==  "all"){
    foreach ($DocumentIssueTypes as  $DocumentIssueTypekey => $DocumentIssueType) {
      $refLabels=array();
      foreach($languages as $language){
        $label = $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $DocumentIssueType -> getCodeDocumentIssue()]);
        if (!$label) {
            $refLabels[$language -> getCodeLanguage()]="";
            continue;
        }
        $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
      }
      $DocumentIssueTypes[$DocumentIssueTypekey] -> setRefLabels($refLabels);
    }           
    return $DocumentIssueTypes;
    //return $DocumentIssueType;
  }
}