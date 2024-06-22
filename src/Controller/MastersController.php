<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\DataClient;
use App\Entity\RefTable;
use App\Entity\RefCountry;
use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use App\Entity\RefRequeststatus;
use App\Entity\RefBank;
use App\Entity\RefCompanytype;
use App\Entity\RefTypeclient;
use App\Entity\RefEpa;
use App\Entity\RefEpafile;
use App\Entity\RefLegalform;
use App\Entity\RefLegalformfile;
use App\Entity\RefTypealert;
use App\Entity\RefWhereclient;
use App\Entity\RefWheresupplier;
use App\Entity\RefDocument;
use App\Entity\RefFile;
use App\Entity\RefFilestatus;
use App\Entity\RefTypecontact;
use App\Entity\RefFunction;
use App\Entity\RefWhoclient;
use App\Entity\RefCivility;
use App\Entity\DataClientWhereclient;
use App\Entity\DataClientWheresupplier;
use App\Entity\DataCountryWhereclient;
use App\Entity\DataCountryWheresupplier;
use App\Entity\RefPromoType;
use App\UtilsV2\Scenario1;
use App\UtilsV2\Attorney;
use App\Service\FileUploader;
use App\Service\ClientService;
use App\Repository\DataClientRepository;
use App\Repository\DataAttorneyRepository;
use App\Repository\RefCountryRepository;
use App\Repository\DataClientWhereclientRepository;
use App\Repository\DataClientWheresupplierRepository;
use App\Repository\DataCountryWhereclientRepository;
use App\Repository\DataCountryWheresupplierRepository;
use App\Repository\DataRequestRepository;
use App\Utils\Code;
use App\UtilsV3\Master\EpaFileData;
use App\UtilsV3\Master\LegalformFileData;
use Doctrine\ORM\QueryBuilder;
use App\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
class MastersController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/api/master/{type}", name="master",methods ={"POST"})
     */
    public function addMasters($type,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        switch ($type) {
        case "country":
             $res = $this->addCountry($content);
            break;
        case "bank":
             $res = $this->addBank($content);
            break;
        case "companytype":
            $res = $this->addCompanyType($content);
            break;
        case "typeclient":
            $res = $this->addTypeClient($content);
            break;
        case "epa":
            $res = $this->addEpa($content);
            break;
        case "legalform":
            $res = $this->addLegalForm($content);
            break;
        case "function":
            $res = $this->addFunction($content);
            break;
        default:
                return  ["msg"=>"type not found"];
        }
        $jsonContent = $serializer->serialize(["msg"=>$res], 'json');
        return new Response( $jsonContent);
    }

    /**
     * @Route("/api/getMaster/{type}/{lang}", name="getMasters")
     */
    public function getMasters($type,$lang,Request $request)
    { 
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $res = array();
        switch ($type) {
            case "country":
                $res = $this->getCountries($lang);
                break;
            case "bank":
                $res = $this->getBanks($lang);
                break;
            case "companytype":
                $res = $this->getCompanytypes($lang);
                break;
            case "typeclient":
                $res = $this->getTypeClients($lang);
                break;
            case "epa":
                $res = $this->getEpas($lang);
                break;
            case "legalform":
                $res = $this->getLegalForms($lang);
                break;
            case "function":
                $res = $this->getFunctions($lang);
                break;
            default:
                return  ["msg"=>"type not found"];
        }
        $jsonContent = $serializer->serialize($res, 'json');
        return new Response( $jsonContent,200,["Content-Type"=>"application/json; charset=UTF-64"]);
    }


    public function addCountry($content):?array
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $country = $serializer->deserialize($content, RefCountry::class, 'json');
        $entityManager = $this->EM;
        $country->setActiveCountry("Active");
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $refLabelRepo = $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        $refCountryRepo = $this->EM->getRepository(RefCountry::class);
 
        if($country->getCodeCountry()==""){
            $country->setCodeCountry($refTabelRepo->next('ref_country'));            
        }
        $secCountry = $refCountryRepo->find($country->getCodeId()? $country->getCodeId() : -1);
        if(!$secCountry){
            $secCountry = $country;
        }else{
            $secCountry->setRefLabels($country->getRefLabels());
        }               
        foreach($secCountry->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secCountry->getCodeCountry(),'lang_label'=>$lang->getId()]);
        if ($chkRefLabel){
            $chkRefLabel->setLabelLabel($refLabel);
            continue;
        }
        $refLabelTemp = new RefLabel();
        $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secCountry->getCodeCountry());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secCountry->setDescCountry($country->getDescCountry());
        $entityManager->persist($secCountry);
        $entityManager->flush();  
        return ["code"=>"success"];   
    }

    public function addBank($content):?array
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $bank = $serializer->deserialize($content, RefBank::class, 'json');
        $entityManager = $this->EM;
        $bank->setActiveBank("Active");
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $refLabelRepo = $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        $refBankRepo = $this->EM->getRepository(RefBank::class);
        if($bank->getCodeBank()==""){
            $bank->setCodeBank($refTabelRepo->next('ref_bank'));           // 
        }
        $secBank = $refBankRepo->find($bank->getCodeId()? $bank->getCodeId() : -1);
        if(!$secBank){
            $secBank = $bank;
        }else{
            $secBank->setRefLabels($bank->getRefLabels());
        }      
         foreach($secBank->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secBank->getCodeBank(),'lang_label'=>$lang->getId()]);
        if ($chkRefLabel){
            $chkRefLabel->setLabelLabel($refLabel);
            continue;
        }
        $refLabelTemp = new RefLabel();
        $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secBank->getCodeBank());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secBank->setDescBank($bank->getDescBank());
        $entityManager->persist($secBank);
        $entityManager->flush();  
        return ["code"=>"success"]; 
    }

     public function addCompanyType($content):?array
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $companytype = $serializer->deserialize($content, RefCompanytype::class, 'json');
        $entityManager = $this->EM;
        $companytype->setActiveCompanytype("Active");
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $refLabelRepo = $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        $refCompanytypeRepo = $this->EM->getRepository(RefCompanytype::class);
        if($companytype->getCodeCompanytype()==""){
            $companytype->setCodeCompanytype($refTabelRepo->next('ref_companytype'));           // 
        }
        $secCompanytype = $refCompanytypeRepo->find($companytype->getCodeId()? $companytype->getCodeId() : -1);
        if(!$secCompanytype){
            $secCompanytype = $companytype;
        }else{
            $secCompanytype->setRefLabels($companytype->getRefLabels());
        }      
        foreach($secCompanytype->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secCompanytype->getCodeCompanytype(),'lang_label'=>$lang->getId()]);
        if ($chkRefLabel){
            $chkRefLabel->setLabelLabel($refLabel);
            continue;
        }
        $refLabelTemp = new RefLabel();
        $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secCompanytype->getCodeCompanytype());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secCompanytype->setDescCompanytype($companytype->getDescCompanytype());
        $entityManager->persist($secCompanytype);
        $entityManager->flush();  
        return ["code"=>"success"]; 
    }
     public function addTypeClient($content):?array
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $typeclient = $serializer->deserialize($content, RefTypeclient::class, 'json');
        $entityManager = $this->EM;
        $typeclient->setActiveTypeclient("Active");
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $refLabelRepo = $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        $refTypeclientRepo = $this->EM->getRepository(RefTypeclient::class);
        if($typeclient->getCodeTypeclient()==""){
           $typeclient->setCodeTypeclient($refTabelRepo->next('ref_typeclient'));            
        }
        $secTypeclient = $refTypeclientRepo->find($typeclient->getCodeId()? $typeclient->getCodeId() : -1);
        if(!$secTypeclient){
            $secTypeclient = $typeclient;
        }else{
            $secTypeclient->setRefLabels($typeclient->getRefLabels());
        }      
          
         foreach( $secTypeclient->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            if(!$lang){
                return["lang"=>$lang];
                continue;
            }
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secTypeclient->getCodeTypeclient(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
            }
        $refLabelTemp = new RefLabel();
        $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secTypeclient->getCodeTypeclient());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $entityManager->persist($secTypeclient);
        $entityManager->flush();  
        return ["code"=>"success"]; 
    }


     public function addEpa($content):?array
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $epa = $serializer->deserialize($content, RefEpa::class, 'json');
        $entityManager = $this->EM;
        $epa->setActiveEpa("Active");
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $refLabelRepo = $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        $refEpaRepo = $this->EM->getRepository(RefEpa::class);
        
        if($epa->getCodeEpa()==""){
           $epa->setCodeEpa($refTabelRepo->next('ref_epa'));            
        }
        $secEpa = $refEpaRepo->find($epa->getCodeId()? $epa->getCodeId() : -1);
        if(!$secEpa){
            $secEpa = $epa;
        }else{
            $secEpa->setRefLabels($epa->getRefLabels());
        }               
        foreach($secEpa->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            if(!$lang){
                return["lang"=>$lang];
                continue;
            }
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secEpa->getCodeEpa(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
            }
            $refLabelTemp = new RefLabel();
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $refLabelTemp->setLangLabel($lang);
            $refLabelTemp->setLabelLabel($refLabel);
            $refLabelTemp->setCodeLabel($secEpa->getCodeEpa());
            $refLabelTemp->setActiveLabel("Active");
            $entityManager->persist($refLabelTemp);
        }
        $secEpa->setDescEpa($epa->getDescEpa());
        $entityManager->persist($secEpa);
        $entityManager->flush();  
        return ["code"=>"success"];   
    }


     public function addLegalForm($content):?array
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $legalform = $serializer->deserialize($content, RefLegalform::class, 'json');
        $entityManager = $this->EM;
        $legalform->setActiveLegalform("Active");
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $refLabelRepo = $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        $refcompanyRepo = $this->EM->getRepository(RefCompanytype::class);
        $refLegalformRepo = $this->EM->getRepository(RefLegalform::class);
        $refcompanyid = $refcompanyRepo->findOneBy(['id'=>$legalform->getRefCompanyId()]);
        if(!$refcompanyid){
            return ["msg"=>"company id  not found"];
        }
        $legalform->setIdCompany($refcompanyid);
        if($legalform->getCodeLegalform()==""){
           $legalform->setCodeLegalform($refTabelRepo->next('ref_legalform'));         
        }
        $secLegalform = $refLegalformRepo->find($legalform->getCodeId()? $legalform->getCodeId() : -1);
        if(!$secLegalform){
            $secLegalform = $legalform;
        }else{
            $secLegalform->setRefLabels($legalform->getRefLabels());
        }      
        foreach( $secLegalform->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secLegalform->getCodeLegalform(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
            }
            $refLabelTemp = new RefLabel();
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $refLabelTemp->setLangLabel($lang);
            $refLabelTemp->setLabelLabel($refLabel);
            $refLabelTemp->setCodeLabel($secLegalform->getCodeLegalform());
            $refLabelTemp->setActiveLabel("Active");
            $entityManager->persist($refLabelTemp);
        }
        $secLegalform->setDescLegalform($legalform->getDescLegalform());
        $entityManager->persist($secLegalform);
        $entityManager->flush();  
        return ["code"=>"success"]; 
    }

    
     public function addFunction($content):?array
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $function = $serializer->deserialize($content,RefFunction::class, 'json');
        $entityManager = $this->EM;
        $function->setActiveFunction("Active");
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $refLabelRepo = $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo = $this->EM->getRepository(RefLanguage::class);
        $refFunctionRepo = $this->EM->getRepository(RefFunction::class);
        if($function->getCodeFunction()==""){
           $function->setCodeFunction($refTabelRepo->next('ref_function'));           
        }
        $secFunction= $refFunctionRepo->find($function->getCodeId()? $function->getCodeId() : -1);
        if(!$secFunction){
            $secFunction = $function;
        }else{
            $secFunction->setRefLabels($function->getRefLabels());
        }      
         foreach( $secFunction->getRefLabels() as $key => $refLabel){
            $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel = $refLabelRepo->findOneBy(['code_label'=>$secFunction->getCodeFunction(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
        }
        $refLabelTemp = new RefLabel();
        $lang = $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secFunction->getCodeFunction());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secFunction->setDescFunction($function->getDescFunction());
        $entityManager->persist($secFunction);
        $entityManager->flush();  
        return ["code"=>"success"]; 
    }

    
    public function getCountries(string $langvalue): ?array{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }

        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $countryem = $this->EM->getRepository(RefCountry::class);
        
        $countries = $countryem->findAll();
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
          foreach($countries as  $countrykey => $country){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$country->getCodeCountry()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $countries[$countrykey]->setRefLabels($refLabels);
                    }
                    
                
                return $countries;
            }
        
        foreach($countries as $key => $country)
        {
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$country->getCodeCountry()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $countries[$key]->setRefLabel($label->getLabelLabel());
        }
        return $countries;
    
   
    }



    public function getBanks(string $langvalue): ?array{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $bankem = $this->EM->getRepository(RefBank::class);
        $banks = $bankem->findAll();
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
          foreach($banks as  $bankkey => $bank){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$bank->getCodeBank()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $banks[$bankkey]->setRefLabels($refLabels);
                    }
                return $banks;
            }
        foreach($banks as $key => $bank)
        {
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$bank->getCodeBank()]);
             if(!$label){
                return ["msg"=>"label not found"];
             }
             $banks[$key]->setRefLabel($label->getLabelLabel());
        }
        return $banks;
    }



    public function getCompanytypes(string $langvalue): ?array{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $companytypeem = $this->EM->getRepository(RefCompanytype::class);
        $companytypes = $companytypeem->findAll();
        $languages = $langRepo->findAll();
        if($langvalue == "all"){
          foreach($companytypes as  $companytypekey => $companytype){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$companytype->getCodeCompanytype()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $companytypes[$companytypekey]->setRefLabels($refLabels);
                    }
                return $companytypes;
            }
        foreach($companytypes as $key => $companytype)
        {
            $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$companytype->getCodeCompanytype()]);
            if(!$label){
                return ["msg"=>"label not found"];
            }
            $companytypes[$key]->setRefLabel($label->getLabelLabel());
        }
        return $companytypes;
    }



    public function getCompanytypesV2(string $lang): ?array{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$lang]);
        if(!$lang){
            return ["msg"=>"lang not found"];
        }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $companytypeem = $this->EM->getRepository(RefCompanytype::class);
        $companytypes = $companytypeem->findAll();
        foreach($companytypes as $key => $companytype)
        {
            $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$companytype->getCodeCompanytype()]);
            if(!$label){
                return ["msg"=>"label not found"];
            }
            $companytypes[$key]->setRefLabel($label->getLabelLabel());
            $legalforms = $companytype->getRefLegalforms();
            foreach($legalforms as $key => $legalform){
                $legalformLabel = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$legalform->getCodeLegalform()]);
                $legalforms[$key]->setRefLabel($legalformLabel->getLabelLabel());
            }
            $companytype->setRefLegalforms($legalforms);
        }
        return $companytypes;
    }

     public function getTypeClients(string $langvalue): ?array{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $typeclientem = $this->EM->getRepository(RefTypeclient::class);
        $typeclients = $typeclientem->findAll();
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
          foreach($typeclients as  $typeclientkey => $typeclient){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$typeclient->getCodeTypeclient()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $typeclients[$typeclientkey]->setRefLabels($refLabels);
                    }
                return $typeclients;
            }
        foreach($typeclients as $key => $typeclient)
        {
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$typeclient->getCodeTypeclient()]);
             if(!$label){
                  return ["msg"=>"label not "];
             }
             $typeclients[$key]->setRefLabel($label->getLabelLabel());
        }
        return $typeclients;
    }

    public function getEpas(string $langvalue): ?array{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $epaem = $this->EM->getRepository(RefEpa::class);
        $epas = $epaem->findAll();
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
          foreach($epas as  $epakey => $epa){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$epa->getCodeEpa()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $epas[$epakey]->setRefLabels($refLabels);
                    }
                    
                
                return $epas;
            }


        foreach($epas as $key => $epa)
        {
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$epa->getCodeEpa()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $epas[$key]->setRefLabel($label->getLabelLabel());
        }
        return $epas;
    }

    public function getLegalForms(string $langvalue): ?array{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $legalformem = $this->EM->getRepository(RefLegalform::class);
        $legalforms = $legalformem->findAll();
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
          foreach($legalforms as  $legalformkey => $legalform){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$legalform->getCodeLegalform()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $legalforms[$legalformkey]->setRefLabels($refLabels);
                    }
                    
                
                return $legalforms;
            }
        foreach($legalforms as $key => $legalform)
        {
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$legalform->getCodeLegalform()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $legalforms[$key]->setRefLabel($label->getLabelLabel());
        }
        return $legalforms;
    }


    public function getFunctions(string $langvalue): ?array{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $functionem = $this->EM->getRepository(RefFunction::class);
        $functions = $functionem->findAll();
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
          foreach($functions as  $functionkey => $function){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$function->getCodeFunction()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $functions[$functionkey]->setRefLabels($refLabels);
                    }
                    
                
                return $functions;
            }
        foreach($functions as $key => $function)
        {
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$function->getCodeFunction()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $functions[$key]->setRefLabel($label->getLabelLabel());
        }
         return $functions;
        
    }

   
     /**
     * @Route("/api/getMaster/country", name="getcountry")
     */
      public function getCountry(){
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $countryRepo = $this->EM->getRepository(RefCountry::class);
        $lists = $countryRepo->findAll();
        $name = [];
        $data = [];
         foreach($lists as $list)
        {
            $codecountry[] = $list->getCodeCountry();
        }
         
          return new ApiResponse( ["name"=>$name],200,["Content-Type"=>"application/json"],'json','success');
     
    }
    /**
     * @Route("/api/getMaster/bank", name="getBank")
     */
      public function getbank(){
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $bankRepo = $this->EM->getRepository(RefBank::class);
        $lists = $bankRepo->findAll();
        $bank = [];
         foreach($lists as $list)
        {
             $bank[] = $list;
        }
         
          return new ApiResponse( ["Bank"=>$bank],200,["Content-Type"=>"application/json"],'json','success');
     
    }
    /**
     * @Route("/api/getMaster/legalformassociatedwithcompany/{langtype}/{id}", name="legalformassociatedwithcompany")
     */
      public function getLegalform($id,$langtype){


        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langtype]);
          if(!$lang){
              if($langtype != "all"){
                // return ["msg"=>"lang not found"];
                return new ApiResponse(["data"=>$langtype],400,["Content-Type"=>"application/json"],'json','worng type!!!!');
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $legalformrepo = $this->EM->getRepository(RefLegalform::class);
        $companyrepo = $this->EM->getRepository(RefCompanytype::class);
        $company = $companyrepo->findBy(['id'=>$id]);
    
        if(!$company){
            return new ApiResponse(["data"=>$company],400,["Content-Type"=>"application/json"],'json','worng companytype!!!!');
        }
        $legalforms = $legalformrepo->findBy(['id_company'=>$company]);
        $languages = $langRepo->findAll();
        if($langtype=="all"){
          foreach($legalforms as  $legalformkey => $legalform){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$legalform->getCodeLegalform()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $legalforms[$legalformkey]->setRefLabels($refLabels);
                    }
                   return new ApiResponse($legalforms,200,["Content-Type"=>"application/json"],'json','success');
            }
        foreach($legalforms as $key => $legalform)
        {
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$legalform->getCodeLegalform()]);
             if(!$label){
                   return new ApiResponse(['label'=>$label],400,["Content-Type"=>"application/json"],'json','label not found');
             }
             $legalforms[$key]->setRefLabel($label->getLabelLabel());
        }
        
           return new ApiResponse(["legalform"=>$legalforms],200,["Content-Type"=>"application/json"],'json','success');
    }

    

    /**
     * @Route("/api/getsingleMaster/{type}/{lang}/{id}", name="getsingleMasters")
     */
    public function getsingleMaster($type,$lang,$id,Request $request)
    { 
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $res = array();
        switch ($type) {
            case "country":
                $res = $this->getSingleCountry($lang,$id);
                break;
            case "companytype":
                $res = $this->getSingleCompanyType($lang,$id);
                break;
            case "typeclient":
                $res = $this->getSingleTypeclient($lang,$id);
                break;
            case "bank":
                $res = $this->getSingleBank($lang,$id);
                break;
            case "epa":
                $res = $this->getSingleEpa($lang,$id);
                break;
            case "legalform":
                $res = $this->getSingleLegalForm($lang,$id);
                break;
            case "function":
                $res = $this->getSingleFunction($lang,$id);
                break;
            case "whoclient":
                $res = $this->getSingleWhoclient($lang,$id);
                break;
            case "civility":
                $res = $this->getSingleCivility($lang,$id);
                break;
            default:
                return  ["msg"=>"type not found"];
        }
        $jsonContent = $serializer->serialize($res, 'json');
        return new Response( $jsonContent,200,["Content-Type"=>"application/json; charset=UTF-64"]);
    }
    public function getSingleCompanyType(string $langvalue,int $id): ?RefCompanytype{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $companytyperepo = $this->EM->getRepository(RefCompanytype::class);
        $paticularcompanytype = $companytyperepo->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularcompanytype->getCodeCompanytype()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $paticularcompanytype->setRefLabels($refLabels);
                   
                    
                
                return $paticularcompanytype;
            }
            $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularcompanytype->getCodeCompanytype()]);
            if(!$label){
                return ["msg"=>"label not found"];
            }
            $paticularcompanytype->setRefLabel($label->getLabelLabel());
        return $paticularcompanytype;
    }



    public function getSingleTypeClient(string $langvalue,int $id): ?RefTypeclient{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $typeclientRepo = $this->EM->getRepository(RefTypeclient::class);
        $paticulartypeclient = $typeclientRepo->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticulartypeclient->getCodeTypeclient()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $paticulartypeclient->setRefLabels($refLabels);
                   
                    
                
                return $paticulartypeclient;
            }
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticulartypeclient->getCodeTypeclient()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticulartypeclient->setRefLabel($label->getLabelLabel());
        return $paticulartypeclient;
    }
     
    
    
    
    public function getSingleCountry(string $langvalue,int $id): ?RefCountry{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $CountryRepo = $this->EM->getRepository(RefCountry::class);
        $paticularcountry = $CountryRepo->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularcountry->getCodeCountry()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $paticularcountry->setRefLabels($refLabels); 
                return $paticularcountry;
            }

             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularcountry->getCodeCountry()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularcountry->setRefLabel($label->getLabelLabel());
        return $paticularcountry;
    }


     public function getSingleBank(string $langvalue,int $id): ?RefBank{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $bankem = $this->EM->getRepository(RefBank::class);
        $paticularbank = $bankem->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularbank->getCodeBank()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $paticularbank->setRefLabels($refLabels);
                return $paticularbank;
            }
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularbank->getCodeBank()]);
             if(!$label){
                return ["msg"=>"label not found"];
             }
             $paticularbank->setRefLabel($label->getLabelLabel());
        return $paticularbank;
    }


    public function getSingleEpa(string $langvalue,int $id): ?RefEpa{
        $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $epaem = $this->EM->getRepository(RefEpa::class);
        $particularepa = $epaem->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularepa->getCodeEpa()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $particularepa->setRefLabels($refLabels);
                return $particularepa;
            }
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularepa->getCodeEpa()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $particularepa->setRefLabel($label->getLabelLabel());
        return $particularepa;
    }
     public function getSingleLegalForm(string $langvalue,int $id): ?RefLegalform{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $legalformRepo = $this->EM->getRepository(RefLegalform::class);
        $paticularlegalform = $legalformRepo->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularlegalform->getCodeLegalform()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       $paticularlegalform->setRefLabels($refLabels);
                    }
                    
                
                return $paticularlegalform;
            }
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularlegalform->getCodeLegalform()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularlegalform->setRefLabel($label->getLabelLabel());
        return $paticularlegalform;
    }

    public function getSingleFunction(string $langvalue,int $id): ?RefFunction{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $functionRepo = $this->EM->getRepository(RefFunction::class);
        $particularfunction = $functionRepo->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularfunction->getCodeFunction()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       $particularfunction->setRefLabels($refLabels);
                    }
                    
                
                return $particularfunction;
            }
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularfunction->getCodeFunction()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $particularfunction->setRefLabel($label->getLabelLabel());
         return $particularfunction;
        
    }



    public function getSingleWhoclient(string $langvalue,int $id): ?RefWhoclient{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $whoclientRepo = $this->EM->getRepository(RefWhoclient::class);
        $particularwhoclient = $whoclientRepo->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularwhoclient->getCodeWhoclient()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $particularwhoclient->setRefLabels($refLabels);
                return $particularwhoclient;
            }
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularwhoclient->getCodeWhoclient()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $particularwhoclient->setRefLabel($label->getLabelLabel());
         return $particularwhoclient;
        
    }

    public function getSingleCivility(string $langvalue,int $id): ?RefCivility{
         $langRepo = $this->EM->getRepository(RefLanguage::class);
        $lang = $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo = $this->EM->getRepository(RefLabel::class);        
        $civilityRepo = $this->EM->getRepository(RefCivility::class);
        $particularcivility = $civilityRepo->findOneBy(['id'=>$id]);
        $languages = $langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels = array();
                    foreach($languages as $language){
                        $label = $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularcivility->getCodeCivility()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()] = $label->getLabelLabel();
                       }
                       $particularcivility->setRefLabels($refLabels);
                return $particularcivility;
            }
             $label = $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularcivility->getCodeCivility()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $particularcivility->setRefLabel($label->getLabelLabel());
         return $particularcivility;   
    }
   
}


 


