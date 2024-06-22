<?php

namespace App\Service;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\RefCountry;
use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use App\Entity\RefBank;
use App\Entity\RefLegalform;
use App\Entity\RefEpa;
use App\Entity\RefCompanytype;
use App\Entity\RefTypeclient;
use App\Entity\RefCivility;
use App\Entity\RefWhoClient;
use App\Entity\RefWhereclient;
use App\Entity\RefWheresupplier;
use App\Entity\RefFunction;
use App\Entity\RefTable;
use App\Entity\RefFile;
use App\Entity\RefRequeststatus;
use App\Entity\Countrycode;


use App\Utils\ApiResponse;
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

use Doctrine\ORM\EntityManagerInterface;

class MasterServiceV2 
{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }



/*****************************   MASTER COUNTRY   *******************************/



    public function insertCountry($content,$entityManager){

        $encoders          =  [ new JsonEncoder()];
        $normalizers       =  [new ObjectNormalizer()];
        $serializer        =  new Serializer($normalizers ,$encoders);
        $country           =  $serializer->deserialize($content, RefCountry::class, 'json');
        $country           ->  setActiveCountry("Active");
        $refTabelRepo      =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo      =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo  =  $this->EM->getRepository(RefLanguage::class);
        $refCountryRepo    =  $this->EM->getRepository(RefCountry::class);
    
        $country  ->  setCodeCountry($refTabelRepo->next('ref_country'));            
        $country  ->  setDescCountry($country->getDescCountry());
        $country  ->  setCountryCode($country->getCountryCode());
        $country  ->  setCountryCodeLabel($country->getCountryCodeLabel());
        $country  ->  setIsEuropean($country->getIsEuropean());
                   
        foreach( $country  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($country->getCodeCountry());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($country);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      // public function insertCountryCode($content,$entityManager){
      //   $encoders          =  [ new JsonEncoder()];
      //   $normalizers       =  [new ObjectNormalizer()];
      //   $serializer        =  new Serializer($normalizers ,$encoders);
      //   $codes             =  $serializer->deserialize($content, Countrycode::class, 'json');
      //   foreach($codes->getCode() as $key => $value){
      //     $Countrycode=new Countrycode;
      //     $Countrycode->setKey($key);
      //     $Countrycode->setValue($value);
      //     $entityManager -> persist($Countrycode);
      //     $entityManager -> flush(); 
      //   }
      //   return "success";
      // }
      // public function getCountryCode(){
       
      //   $countrycoderepo   =  $this->EM->getRepository(Countrycode::class);
      //   $codes=$countrycoderepo->findAll();
      //   $reflable  =array();
      //   foreach($codes as $code){
      //     $reflable[$code->getKey()] = $code->getValue();
      //   }
      //   $codecountry=new Countrycode;
      //   $codecountry->setCode($reflable);
      //   return $codecountry->getCode();
      // }
      public function updateCountry($content,$entityManager,$id){
      
        $encoders         =  [ new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefCountry::class, 'json');

        $refCountryRepo   =  $this->EM->getRepository(RefCountry::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $country          =  $refCountryRepo->findOneBy(['id'=>$id]);
        if(!$country){
          return "Country id not found";
        }
        $country      ->  setDescCountry($data->getDescCountry());
        $country      ->  setActiveCountry($data->getActiveCountry());
        $country      ->  setCountryCode($data->getCountryCode());
        $country      ->  setCountryCodeLabel($data->getCountryCodeLabel());
        $country      ->  setIsEuropean($data->getIsEuropean());
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$country->getCodeCountry(),'lang_label'=>$lang->getId()]);
          if ($chkRefLabel){
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
             
          }
        }
        $entityManager  ->  persist($country);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }
    
      public function getSingleCountry(int $id,string $langvalue): ?RefCountry{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo        =  $this  ->EM ->  getRepository(RefLabel::class);        
        $CountryRepo      =  $this  ->EM ->  getRepository(RefCountry::class);
        $paticularcountry =  $CountryRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularcountry->getCodeCountry()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularcountry -> setRefLabels($refLabels); 
                return $paticularcountry;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularcountry -> getCodeCountry()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularcountry -> setRefLabel($label -> getLabelLabel());
        return $paticularcountry;
      }
      
      public function getAllCountries(string $langvalue,$domain){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"  =>  "lang not found"];
              }
             }
         
        $labelRepo     =   $this ->EM -> getRepository(RefLabel::class);        
        $countryem     =   $this ->EM -> getRepository(RefCountry::class);
        if($domain == 'front'){
            $countries     =   $countryem->findBy(array('active_country'=>['Active']),array('desc_country'=>'ASC'));
        }
        if($domain == 'back'){
            $countries     =   $countryem->findBy(array('active_country'=>['Active','Disabled']),array('desc_country'=>'ASC'));
        }
        $languages     =   $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($countries as  $countrykey => $country){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $country -> getCodeCountry()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $countries[$countrykey] -> setRefLabels($refLabels);
                    }
                    
                
                return $countries;
            }
        
        foreach($countries as $key => $country)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $country -> getCodeCountry()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $countries[$key] -> setRefLabel($label -> getLabelLabel());
        }
        return $countries;
      
      
      }

      public function getAllCountries1(string $langvalue,$domain){     
        $countryem     =   $this ->EM -> getRepository(RefCountry::class);
        if($domain == 'front'){
            $countries     =   $countryem->findBy(array('active_country'=>['Active']),array('desc_country'=>'ASC'));
        }
        if($domain == 'back'){
            $countries     =   $countryem->findBy(array('active_country'=>['Active','Disabled']),array('desc_country'=>'ASC'));
        }
        return $countries;
      }
      public function countryStatusChange($id,$status,$entityManager){
        $encoders      =   [ new JsonEncoder()];
        $normalizers   =   [new ObjectNormalizer()];
        $serializer    =   new Serializer($normalizers ,$encoders);
        $countryrepo   =   $this  ->EM   ->  getRepository(RefCountry::class);
        $country       =   $countryrepo  ->findOneBy(['id'=>$id]);
        $country       ->  setActiveCountry($status);
        $entityManager ->  persist($country);
        $entityManager ->  flush();  
      
      
      }

      // public function deleteCountry($id,$entityManager){
      //   $encoders      =   [ new JsonEncoder()];
      //   $normalizers   =   [new ObjectNormalizer()];
      //   $serializer    =   new Serializer($normalizers ,$encoders);
      //   $countryrepo   =   $this  ->EM   ->  getRepository(RefCountry::class);
      //   $country       =   $countryrepo  ->findOneBy(['id'=>$id]);
      //   $country       ->  setActiveCountry('deleted');
      //   $entityManager ->  persist($country);
      //   $entityManager ->  flush();  
        
      
      // }
      
      public function filterCountry($status){
      
        $arrstatus   =  array();
        $country     =  array();
        $arrstatus   =  $status->getStatus();
        $countryrepo =  $this->EM->getRepository(RefCountry::class);
        $country     =  $countryrepo->findBy(array('active_country' => $arrstatus));
        return $country;
      }


      public function getExceptEuropean($langvalue){
        
        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"  =>  "lang not found"];
              }
             }
         
        $labelRepo     =   $this ->EM -> getRepository(RefLabel::class);        
        $countryem     =   $this ->EM -> getRepository(RefCountry::class);
           
        $countries     =   $countryem->findBy(['active_country'=>['Active'],'is_european'=>'NO'],array('desc_country'=>'ASC'));
        $languages     =   $langRepo->findAll();
        //if($langvalue  ==  "all"){
          foreach($countries as  $countrykey => $country){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $country -> getCodeCountry()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $countries[$countrykey] -> setRefLabels($refLabels);
                    }
                    
                
                return $countries;
           // }
        
        /*foreach($countries as $key => $country)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $country -> getCodeCountry()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $countries[$key] -> setRefLabel($label -> getLabelLabel());
        }*/
        return $countries;
      

      }
      public function getExceptEuropean1($langvalue){        
        $countryem     =   $this ->EM -> getRepository(RefCountry::class);
        $countries     =   $countryem->findBy(['active_country'=>['Active'],'is_european'=>'NO'],array('desc_country'=>'ASC'));        
        return $countries;
      }
      /*****************************   MASTER BANK   *******************************/

      public function insertBank($content,$entityManager){

        $encoders          =  [ new JsonEncoder()];
        $normalizers       =  [new ObjectNormalizer()];
        $serializer        =  new Serializer($normalizers ,$encoders);
        $bank              =  $serializer->deserialize($content, RefBank::class, 'json');
        $refTabelRepo      =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo      =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo  =  $this->EM->getRepository(RefLanguage::class);
        $refCountryRepo    =  $this->EM->getRepository(RefBank::class);
        $bank  ->  setActiveBank("Active");
        $bank  ->  setCodeBank($refTabelRepo->next('ref_bank'));            
        $bank  ->  setDescBank($bank->getDescBank());
                    
        foreach($bank  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($bank->getCodeBank());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($bank);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      public function updateBank($content,$entityManager,$id){
      
        $encoders         =  [ new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefBank::class, 'json');

        $refBankRepo      =  $this->EM->getRepository(RefBank::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $bank             =  $refBankRepo->findOneBy(['id'=>$id]);
        if(!$bank){
          return "bank id not found";
        }
        $bank      ->  setDescBank($data->getDescBank());
        $bank      ->  setActiveBank("Active");
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$bank->getCodeBank(),'lang_label'=>$lang->getId()]);
          if ($chkRefLabel){
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
             
          }
        }
        $entityManager  ->  persist($bank);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }

      public function  getSingleBank(int $id,string $langvalue): ?RefBank{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo     =  $this  ->EM ->  getRepository(RefLabel::class);        
        $BankRepo      =  $this  ->EM ->  getRepository(RefBank::class);
        $paticularbank =  $BankRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularbank->getCodeBank()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularbank -> setRefLabels($refLabels); 
                return $paticularbank;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularbank -> getCodeBank()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularbank -> setRefLabel($label -> getLabelLabel());
        return $paticularbank;
      }

      public function getAllBanks(string $langvalue){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"  =>  "lang not found"];
              }
             }
      
        $labelRepo  = $this ->EM -> getRepository(RefLabel::class);        
        $bankem     = $this ->EM -> getRepository(RefBank::class);
        
        $banks      = $bankem->findBy(['active_bank'=>['Active']]);
        $languages  = $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($banks as  $bankkey => $bank){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $bank -> getCodeBank()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $banks[$bankkey] -> setRefLabels($refLabels);
                    }
                    
                
                return $banks;
            }
        
        foreach($banks as $key => $bank)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $bank -> getCodeBank()]);
            //  return $label;
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $banks[$key] -> setRefLabel($label -> getLabelLabel());
        }
        return $banks;
      
      
      }

      public function bankStatusChange($id,$status,$entityManager){
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $bankrepo      = $this  ->EM   ->  getRepository(RefBank::class);
        $bank          = $bankrepo  ->findOneBy(['id'=>$id]);
        $bank          ->setActiveBank($status);
        $entityManager ->persist($bank);
        $entityManager ->flush();  
      
      
      }

      public function deleteBank($id,$entityManager){
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $bankrepo      = $this  ->EM   ->  getRepository(RefBank::class);
        $bank          = $bankrepo  ->findOneBy(['id'=>$id]);
        $bank          ->setActiveBank('deleted');
        $entityManager ->persist($bank);
        $entityManager ->flush();  
      
      
      }
      
      public function filterBank($status){
      
        $arrstatus   =  array();
        $bank        =  array();
        $arrstatus   =  $status->getStatus();
        $bankrepo    =  $this->EM->getRepository(RefBank::class);
        $bank        =  $bankrepo->findBy(array('active_bank' => $arrstatus));
        return $bank;
      }

       /*****************************   MASTER FILE   *******************************/

       public function insertFile($content,$entityManager){

        $encoders          =  [ new JsonEncoder()];
        $normalizers       =  [new ObjectNormalizer()];
        $serializer        =  new Serializer($normalizers ,$encoders);
        $file              =  $serializer->deserialize($content, RefFile::class, 'json');
        $refTabelRepo      =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo      =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo  =  $this->EM->getRepository(RefLanguage::class);
        $reffileRepo       =  $this->EM->getRepository(RefFile::class);
        $file  ->  setActiveFile("Active");
        $file  ->  setCodeFile($refTabelRepo->next('ref_file'));            
        $file  ->  setDescFile($file->getDescFile());
        $file  ->  setMandatoryFile($file->getMandatoryFile());
        $file  ->  setFilenameFile($file->getFilenameFile());
        $file  ->  setJsonkey($file->getFilenameFile());
                    
        foreach($file  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($file->getCodeFile());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($file);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      public function updateFile($content,$entityManager,$id){
      
        $encoders         =  [ new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefFile::class, 'json');

        $refFileRepo      =  $this->EM->getRepository(RefFile::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $file             =  $refFileRepo->findOneBy(['id'=>$id]);
        if(!$file){
          return "file id not found";
        }
        $file      ->  setDescFile($data->getDescFile());
        $file  ->  setMandatoryFile($data->getMandatoryFile());
        $file  ->  setFilenameFile($data->getFilenameFile());
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$file->getCodeFile(),'lang_label'=>$lang->getId()]);
          if ($chkRefLabel){
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
             
          }
          else{
             $refLabelTemp = new RefLabel();
             $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
             $refLabelTemp->setLangLabel($lang);
             $refLabelTemp->setLabelLabel($reflabel);
             $refLabelTemp->setCodeLabel($file->getCodeFile());
             $refLabelTemp->setActiveLabel("Active");
             $entityManager  ->  persist($refLabelTemp);
          }
        }
        $entityManager  ->  persist($file);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }

      public function  getSingleFile(int $id,string $langvalue) {
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo     =  $this  ->EM ->  getRepository(RefLabel::class);        
        $FileRepo      =  $this  ->EM ->  getRepository(RefFile::class);
        $paticularfile =  $FileRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularfile->getCodeFile()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularfile -> setRefLabels($refLabels); 
                return $paticularfile;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularfile -> getCodeFile()]);
             $lablevalue="null";
             if(!$label){
              $paticularfile -> setRefLabel($lablevalue);
             }
             else{
              $paticularfile -> setRefLabel($label -> getLabelLabel());
             }
            
        return $paticularfile;
      }

      public function getAllFiles(string $langvalue){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"  =>  "lang not found"];
              }
             }
      
        $labelRepo  = $this ->EM -> getRepository(RefLabel::class);        
        $fileem     = $this ->EM -> getRepository(RefFile::class);
        
        $files      = $fileem->findBy(['active_file'=>['Active']]);
        $languages  = $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($files as  $filekey => $file){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $file -> getCodeFile()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $files[$filekey] -> setRefLabels($refLabels);
                    }
                    
                
                return $files;
            }
        
        foreach($files as $key => $file)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $file -> getCodeFile()]);
              // return  $label;
             $labelvalue="null"; 
             if(!$label){
              $files[$key] -> setRefLabel($labelvalue);
             }
             else{
              $files[$key] -> setRefLabel($label -> getLabelLabel());
             }
             
        }
        return $files;
      
      
      }

      public function fileStatusChange($id,$status,$entityManager){
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $filerepo      = $this  ->EM   ->  getRepository(RefFile::class);
        $file          = $filerepo  ->findOneBy(['id'=>$id]);
        $file          ->setActiveFile($status);
        $entityManager ->persist($file);
        $entityManager ->flush();  
      
      
      }

      // public function deleteFile($id,$entityManager){
      //   $encoders      = [ new JsonEncoder()];
      //   $normalizers   = [new ObjectNormalizer()];
      //   $serializer    = new Serializer($normalizers ,$encoders);
      //   $filerepo      = $this  ->EM   ->  getRepository(RefFile::class);
      //   $file          = $filerepo  ->findOneBy(['id'=>$id]);
      //   $file          ->setActiveFile('deleted');
      //   $entityManager ->persist($file);
      //   $entityManager ->flush();  
      
      
      // }
      
      // public function filterFile($status){
      
      //   $arrstatus   =  array();
      //   $file        =  array();
      //   $arrstatus   =  $status->getStatus();
      //   $filerepo    =  $this->EM->getRepository(RefFile::class);
      //   $file        =  $filerepo->findBy(array('active_file' => $arrstatus));
      //   return $file;
      // }

       /*****************************   MASTER COMPANYTYPE   *******************************/

       public function insertCompanyType($content,$entityManager){

        $encoders              =  [ new JsonEncoder()];
        $normalizers           =  [new ObjectNormalizer()];
        $serializer            =  new Serializer($normalizers ,$encoders);
        $companytype           =  $serializer->deserialize($content, RefCompanytype::class, 'json');
        $refTabelRepo          =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo          =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo      =  $this->EM->getRepository(RefLanguage::class);
        $refCompanytypeRepo    =  $this->EM->getRepository(RefCompanytype::class);
        $companytype  ->  setActiveCompanytype("Active");
        $companytype  ->  setCodeCompanytype($refTabelRepo->next('ref_companytype'));            
        $companytype  ->  setDescCompanytype($companytype->getDescCompanytype());
                    
        foreach($companytype  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($companytype->getCodeCompanytype());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($companytype);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      public function updateCompanytype($content,$entityManager,$id){
      
        $encoders         =  [new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefCompanytype::class,'json');

        $refCompanytypeRepo      =  $this->EM->getRepository(RefCompanytype::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $companytype      =  $refCompanytypeRepo->findOneBy(['id'=>$id]);
        if(!$companytype){
          return "companytype id not found";
        }
        $companytype      ->  setDescCompanytype($data->getDescCompanytype());
        $companytype      ->  setActiveCompanytype("Active");
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$companytype->getCodeCompanytype(),'lang_label'=>$lang->getId()]);
          if ($chkRefLabel){
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
             
          }
        }
        $entityManager  ->  persist($companytype);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }

      public function  getSingleCompanytype(int $id,string $langvalue): ?RefCompanytype{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo            =  $this  ->EM ->  getRepository(RefLabel::class);        
        $CompanytypeRepo      =  $this  ->EM ->  getRepository(RefCompanytype::class);
        $paticularcompanytype =  $CompanytypeRepo->  findOneBy(['id'=>$id]);
        $languages            =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularcompanytype->getCodeCompanytype()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularcompanytype -> setRefLabels($refLabels); 
                return $paticularcompanytype;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularcompanytype -> getCodeCompanytype()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularcompanytype -> setRefLabel($label -> getLabelLabel());
        return $paticularcompanytype;
      }

      public function getAllCompanytypes(string $langvalue){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"  =>  "lang not found"];
              }
             }
      
        $labelRepo         = $this ->EM -> getRepository(RefLabel::class);        
        $companytypeem     = $this ->EM -> getRepository(RefCompanytype::class);
        
        $companytypes      = $companytypeem->findBy(['active_companytype'=>['Active']]);
        $languages  = $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($companytypes as  $companytypekey => $companytype){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $companytype -> getCodeCompanytype()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $companytypes[$companytypekey] -> setRefLabels($refLabels);
                    }
                    
                
                return $companytypes;
            }
        
        foreach($companytypes as $key => $companytype)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $companytype -> getCodeCompanytype()]);
            //  return $label;
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $companytypes[$key] -> setRefLabel($label -> getLabelLabel());
        }
        return $companytypes;
      
      
      }

      public function companytypeStatusChange($id,$status,$entityManager){
        $encoders        = [ new JsonEncoder()];
        $normalizers     = [new ObjectNormalizer()];
        $serializer      = new Serializer($normalizers ,$encoders);
        $companytyperepo = $this  ->EM   ->  getRepository(RefCompanytype::class);
        $companytype     = $companytyperepo  ->findOneBy(['id'=>$id]);
        $companytype ->setActiveCompanytype($status);
        $entityManager ->persist($companytype);
        $entityManager ->flush();  
      
      
      }

      public function deleteCompanytype($id,$entityManager){
        $encoders             = [ new JsonEncoder()];
        $normalizers          = [new ObjectNormalizer()];
        $serializer           = new Serializer($normalizers ,$encoders);
        $companytyperepo      = $this  ->EM   ->  getRepository(RefCompanytype::class);
        $companytype          = $companytyperepo  ->findOneBy(['id'=>$id]);
        $companytype          ->setActiveCompanytype('deleted');
        $entityManager ->persist($companytype);
        $entityManager ->flush();  
      
      
      }
      
      public function filterCompanytype($status){
             
        $arrstatus          =  array();
        $companytype        =  array();
        $arrstatus          =  $status->getStatus();
        $companytyperepo    =  $this->EM->getRepository(RefCompanytype::class);
        $companytype        =  $companytyperepo->findBy(array('active_companytype' => $arrstatus));
        return $companytype;
      }


       /*****************************   MASTER LEGALFORM   *******************************/

       public function insertLegalform($content,$entityManager){
        // return "hai";
        $encoders          =  [ new JsonEncoder()];
        $normalizers       =  [new ObjectNormalizer()];
        $serializer        =  new Serializer($normalizers ,$encoders);
        $legalform         =  $serializer->deserialize($content, RefLegalform::class, 'json');
        $refTabelRepo      =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo      =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo  =  $this->EM->getRepository(RefLanguage::class);
        $reflegalformRepo  =  $this->EM->getRepository(RefLegalform::class);
        $refcompanyRepo    =  $this->EM->getRepository(RefCompanytype::class);
       
        $chkcompany=$refcompanyRepo->findOneBy(['id'=>$legalform->getCompany()]);
        $chkcode=$reflegalformRepo->findOneBy(['legalform_code'=>$legalform->getLegalformCode()]);
        
        if($chkcode){
          throw new HttpException(400, "code is already exist.");  
        }
        if(!$chkcompany){
          return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','invalid company_id');    
        }
        $legalform  ->  setActiveLegalform("Active");
        if ($legalform->getCodeLegalform()=="" ){
        $legalform  ->  setCodeLegalform($refTabelRepo->next('ref_legalform'));  
        }          
        // $legalform  ->  setDescLegalform($legalform->getDescLegalform());
        $legalform  ->  setIdCompany($chkcompany);
       
                    
        foreach($legalform  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($legalform->getCodeLegalform());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($legalform);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      public function updateLegalform($content,$entityManager,$id){
      
        $encoders         =  [ new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefLegalform::class, 'json');

        $refLegalformRepo =  $this->EM->getRepository(RefLegalform::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $legalform        =  $refLegalformRepo->findOneBy(['id'=>$id]);
        $refcompanyRepo   =  $this->EM->getRepository(RefCompanytype::class);
        $chkcode=$refLegalformRepo->findOneBy(['legalform_code'=>$data->getLegalformCode()]);
        
        if($chkcode){
          if($chkcode->getId()!=$id){
            throw new HttpException(400, "code is not valid.");    
          }
        
        }
        if(!$legalform){
          return "legalform id not found";
        }
        $chkcompany=$refcompanyRepo->findOneBy(['id'=>$data->getCompany()]);
        // return $data->getCompany();
        if(!$chkcompany){
          return "company_id invalid";
        }
        $legalform  ->  setDescLegalform($data->getDescLegalform());
        $legalform  ->  setLegalformCode($data->getLegalformCode());
        $legalform  ->  setIdCompany($chkcompany);
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$legalform->getCodeLegalform(),'lang_label'=>$lang->getId()]);
          if ($chkRefLabel){
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
             
          }
        }
        $entityManager  ->  persist($legalform);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }

      public function  getSingleLegalform(int $id,string $langvalue): ?RefLegalform{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return "lang not found";
              }
             }
        $labelRepo          =  $this  ->EM ->  getRepository(RefLabel::class);        
        $LegalformRepo      =  $this  ->EM ->  getRepository(RefLegalform::class);
        $paticularlegalform =  $LegalformRepo->  findOneBy(['id'=>$id]);
        $languages          =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularlegalform->getCodeLegalform()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularlegalform -> setRefLabels($refLabels); 
                return $paticularlegalform;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularlegalform -> getCodeLegalform()]);
             if(!$label){
                  return "label not found";
             }
             $paticularlegalform -> setRefLabel($label -> getLabelLabel());
        return $paticularlegalform;
      }

      public function getAllLegalforms(string $langvalue,$domain){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return "lang not found";
              }
             }
      
        $labelRepo       = $this ->EM -> getRepository(RefLabel::class);        
        $legalformem     = $this ->EM -> getRepository(RefLegalform::class);
        if($domain == 'front'){
            $legalforms      = $legalformem->findBy(['active_legalform'=>['Active']],array('desc_legalform'=>'ASC'));
        }
        if($domain == 'back'){
            $legalforms      = $legalformem->findBy(['active_legalform'=>['Active','Disabled']],array('desc_legalform'=>'ASC'));
        }
        $languages       = $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($legalforms as  $legalformkey => $legalform){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $legalform -> getCodeLegalform()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $legalforms[$legalformkey] -> setRefLabels($refLabels);
                   
                        $legalform->setIdCompany($this->getSingleCompanytype($legalform->getIdCompany()->getId(),$langvalue));
                       
                      
                    }
                    
                
                return $legalforms;
            }
        
        foreach($legalforms as $key => $legalform)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $legalform -> getCodeLegalform()]);
              // return  $label;
             if(!$label){
                  return "lable not found";
             }
             $legalforms[$key] -> setRefLabel($label -> getLabelLabel());
        }
        return $legalforms;
      
      
      }


      public function getAllLegalforms1(string $langvalue,$domain){    
        $legalformem     = $this ->EM -> getRepository(RefLegalform::class);
        if($domain == 'front'){
            $legalforms      = $legalformem->findBy(['active_legalform'=>['Active']],array('desc_legalform'=>'ASC'));
        }
        if($domain == 'back'){
            $legalforms      = $legalformem->findBy(['active_legalform'=>['Active','Disabled']],array('desc_legalform'=>'ASC'));
        }
        return $legalforms;      
      }


      public function legalformStatusChanges($id,$status,$entityManager){
        $encoders           = [ new JsonEncoder()];
        $normalizers        = [new ObjectNormalizer()];
        $serializer         = new Serializer($normalizers ,$encoders);
        $legalformrepo      = $this  ->EM   ->  getRepository(RefLegalform::class);
        $legalform          = $legalformrepo  ->findOneBy(['id'=>$id]);
        $legalform          ->setActiveLegalform($status);
        $entityManager ->persist($legalform);
        $entityManager ->flush();  
      
      
      }

      public function deleteLegalforms($id,$entityManager){
        $encoders           = [ new JsonEncoder()];
        $normalizers        = [new ObjectNormalizer()];
        $serializer         = new Serializer($normalizers ,$encoders);
        $legalformrepo      = $this  ->EM   ->  getRepository(RefLegalform::class);
        $legalform          = $legalformrepo  ->findOneBy(['id'=>$id]);
        $legalform          ->setActiveLegalform('deleted');
        $entityManager ->persist($legalform);
        $entityManager ->flush();  
      
      
      }
      
      public function filterLegalforms($status){
      
        $arrstatus     =  array();
        $legalform     =  array();
        $arrstatus     =  $status->getStatus();
        $Legalformrepo =  $this->EM->getRepository(RefLegalform::class);
        $legalform     =  $Legalformrepo->findBy(array('active_legalform' => $arrstatus));
        return $legalform;
      }

      
      
      /*****************************   MASTER EPA   *******************************/

      public function insertEpa($content,$entityManager){

        $encoders          =  [ new JsonEncoder()];
        $normalizers       =  [new ObjectNormalizer()];
        $serializer        =  new Serializer($normalizers ,$encoders);
        $epa               =  $serializer->deserialize($content, RefEpa::class, 'json');
        $refTabelRepo      =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo      =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo  =  $this->EM->getRepository(RefLanguage::class);
        $refEpaRepo        =  $this->EM->getRepository(RefEpa::class);
        $chkcode=$refEpaRepo->findOneBy(['epa_code'=>$epa->getepaCode()]);
        
        if($chkcode){
          throw new HttpException(400, "code is not valid.");    
        }
        $epa  ->  setActiveEpa("Active");
        if ($epa->getCodeEpa()=="" ){
          $epa  ->  setCodeEpa($refTabelRepo->next('ref_epa'));            
        }
            
        foreach($epa  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($epa->getCodeEpa());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($epa);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      public function updateEpa($content,$entityManager,$id){
      
        $encoders         =  [ new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefEpa::class, 'json');

        $refEpaRepo       =  $this->EM->getRepository(RefEpa::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $epa              =  $refEpaRepo->findOneBy(['id'=>$id]);
        if(!$epa){
          return "epa id not found";
        }
        $chkcode=$refEpaRepo->findOneBy(['epa_code'=>$data->getepaCode()]);
        // return $chkcode;
        if($chkcode){
          if($chkcode->getId()!=$id){
            throw new HttpException(400, "code is not valid.");    
          }
         
        }
       
        if ($epa->getCodeEpa()=="" ){
          $epa  ->  setCodeEpa($refTabelRepo->next('ref_epa'));            
        }
        $epa      ->  setDescEpa($data->getDescEpa());
        $epa      ->  setEpaCode($data->getEpaCode());
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$epa->getCodeEpa(),'lang_label'=>$lang->getId()]);
          if ($chkRefLabel){
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
             
          }
        }
        $entityManager  ->  persist($epa);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }

      public function  getSingleEpa(int $id,string $langvalue): ?RefEpa{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo     =  $this  ->EM ->  getRepository(RefLabel::class);        
        $EpaRepo      =  $this  ->EM ->  getRepository(RefEpa::class);
        $paticularepa =  $EpaRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularepa->getCodeEpa()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularepa -> setRefLabels($refLabels); 
                return $paticularepa;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularepa -> getCodeEpa()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularepa -> setRefLabel($label -> getLabelLabel());
        return $paticularepa;
      }

      public function getAllEpas(string $langvalue,$domain){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return "lang not found";
              }
             }
      
        $labelRepo  = $this ->EM -> getRepository(RefLabel::class);        
        $epaem     = $this ->EM -> getRepository(RefEpa::class);
        if($domain == 'front'){
            $epas = $epaem->findBy(['active_epa'=>['Active']],array('desc_epa'=>'ASC'));
        }
        if($domain == 'back'){
            $epas = $epaem->findBy(['active_epa'=>['Active','Disabled']],array('desc_epa'=>'ASC'));
        }
        $languages  = $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($epas as  $epakey => $epa){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $epa -> getCodeEpa()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $epas[$epakey] -> setRefLabels($refLabels);
                    }
                    
                
                return $epas;
            }
        
        foreach($epas as $key => $epa)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $epa -> getCodeEpa()]);
            //  return $label;
             if(!$label){
                  return ["msg"=>"label not found","epa"=>$epa];
             }
             $epas[$key] -> setRefLabel($label -> getLabelLabel());
        }
        return $epas;
      
      
      }

      public function getAllEpas1(string $langvalue,$domain){        
        $epaem     = $this ->EM -> getRepository(RefEpa::class);
        if($domain == 'front'){
            $epas = $epaem->findBy(['active_epa'=>['Active']],array('desc_epa'=>'ASC'));
        }
        if($domain == 'back'){
            $epas = $epaem->findBy(['active_epa'=>['Active','Disabled']],array('desc_epa'=>'ASC'));
        }
        return $epas;
      }

      public function epaStatusChange($id,$status,$entityManager){
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $eparepo      = $this  ->EM   ->  getRepository(RefEpa::class);
        $epa          = $eparepo  ->findOneBy(['id'=>$id]);
        $epa          ->setActiveEpa($status);
        $entityManager ->persist($epa);
        $entityManager ->flush();  
      
      
      }

      public function deleteEpa($id,$entityManager){
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $eparepo      = $this  ->EM   ->  getRepository(RefEpa::class);
        $epa          = $eparepo  ->findOneBy(['id'=>$id]);
        $epa          ->setActiveEpa('deleted');
        $entityManager ->persist($epa);
        $entityManager ->flush();  
      
      
      }
      
      public function filterEpa($status){
      
        $arrstatus   =  array();
        $epa        =  array();
        $arrstatus   =  $status->getStatus();
        $eparepo    =  $this->EM->getRepository(RefEpa::class);
        $epa        =  $eparepo->findBy(array('active_epa' => $arrstatus));
        return $epa;
      }

       /*****************************   MASTER TYPECLIENT   *******************************/

       public function insertTypeclient($content,$entityManager){

        $encoders          =  [ new JsonEncoder()];
        $normalizers       =  [new ObjectNormalizer()];
        $serializer        =  new Serializer($normalizers ,$encoders);
        $typeclient              =  $serializer->deserialize($content, RefTypeclient::class, 'json');
        $refTabelRepo      =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo      =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo  =  $this->EM->getRepository(RefLanguage::class);
        $refTypeclientRepo    =  $this->EM->getRepository(RefTypeclient::class);
        $typeclient  ->  setActiveTypeclient("Active");
        $typeclient  ->  SetDescTypeclient( $typeclient  ->  getDescTypeclient());
        $typeclient->setLabelColor($typeclient->getLabelColor());
        $typeclient  ->  setCodeTypeclient($refTabelRepo->next('ref_typeclient'));            
    
                    
        foreach($typeclient  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($typeclient->getCodeTypeclient());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($typeclient);
        $entityManager -> flush();  
        return "successfully added";   
      
      }

      public function updateTypeclient($content,$entityManager,$id){
      
        $encoders         =  [ new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefTypeclient::class, 'json');

        $refTypeclientRepo=  $this->EM->getRepository(RefTypeclient::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $typeclient       =  $refTypeclientRepo->findOneBy(['id'=>$id]);
        if(!$typeclient){
          return "typeclient id not found";
        }
        $typeclient  ->  setActiveTypeclient("Active");
        $typeclient  ->  SetDescTypeclient( $data  ->  getDescTypeclient());
        $typeclient->setLabelColor($data->getLabelColor());
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$typeclient->getCodeTypeclient(),'lang_label'=>$lang->getId()]);
          if ($chkRefLabel){
              $chkRefLabel    ->  setLabelLabel($reflabel);
              $entityManager  ->  persist($chkRefLabel);
             
          }
        }
        $entityManager  ->  persist($typeclient);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }

      public function  getSingleTypeclient(int $id,string $langvalue): ?RefTypeclient{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo     =  $this  ->EM ->  getRepository(RefLabel::class);        
        $TypeclientRepo      =  $this  ->EM ->  getRepository(RefTypeclient::class);
        $paticulartypeclient =  $TypeclientRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticulartypeclient->getCodeTypeclient()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticulartypeclient -> setRefLabels($refLabels); 
                return $paticulartypeclient;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticulartypeclient -> getCodeTypeclient()]);
             $lablevalue="null";
             if(!$label){
              $paticulartypeclient -> setRefLabel($lablevalue);
             }
             else{
              $paticulartypeclient -> setRefLabel($label -> getLabelLabel());
             }
        return $paticulartypeclient;
      }

      public function getAllTypeclients(string $langvalue){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"  =>  "lang not found"];
              }
             }
      
        $labelRepo  = $this ->EM -> getRepository(RefLabel::class);        
        $typeclientem     = $this ->EM -> getRepository(RefTypeclient::class);
        
        $typeclients      = $typeclientem->findBy(['active_typeclient'=>['Active']]);
        $languages  = $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($typeclients as  $typeclientkey => $typeclient){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $typeclient -> getCodeTypeclient()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $typeclients[$typeclientkey] -> setRefLabels($refLabels);
                    }
                    
                
                return $typeclients;
            }
        
        foreach($typeclients as $key => $typeclient)
        
          {
            $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $typeclient -> getCodeTypeclient()]);
             // return  $label;
            $labelvalue="null"; 
            if(!$label){
             $typeclients[$key] -> setRefLabel($labelvalue);
            }
            else{
             $typeclients[$key] -> setRefLabel($label -> getLabelLabel());
            }
            
       }
       return $typeclients;
      
      
      }

      public function typeclientStatusChange($id,$status,$entityManager){
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $typeclientrepo      = $this  ->EM   ->  getRepository(RefTypeclient::class);
        $typeclient          = $typeclientrepo  ->findOneBy(['id'=>$id]);
        $typeclient          ->setActiveTypeclient($status);
        $entityManager ->persist($typeclient);
        $entityManager ->flush();  
      
      
      }

      // public function deleteTypeclient($id,$entityManager){
      //   $encoders      = [ new JsonEncoder()];
      //   $normalizers   = [new ObjectNormalizer()];
      //   $serializer    = new Serializer($normalizers ,$encoders);
      //   $typeclientrepo      = $this  ->EM   ->  getRepository(RefTypeclient::class);
      //   $typeclient          = $typeclientrepo  ->findOneBy(['id'=>$id]);
      //   $typeclient          ->setActiveTypeclient('deleted');
      //   $entityManager ->persist($typeclient);
      //   $entityManager ->flush();  
      
      
      // }
      
      public function filterTypeclient($status){
      
        $arrstatus   =  array();
        $typeclient        =  array();
        $arrstatus   =  $status->getStatus();
        $typeclientrepo    =  $this->EM->getRepository(RefTypeclient::class);
        $typeclient        =  $typeclientrepo->findBy(array('active_typeclient' => $arrstatus));
        return $typeclient;
      }


      /*****************************   MASTER FUNCTION   *******************************/

      public function insertFunction($content,$entityManager){
     
        $encoders          =  [ new JsonEncoder()];
        $normalizers       =  [new ObjectNormalizer()];
        $serializer        =  new Serializer($normalizers ,$encoders);
        $function              =  $serializer->deserialize($content, RefFunction::class, 'json');
        $refTabelRepo      =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo      =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo  =  $this->EM->getRepository(RefLanguage::class);
        $refFunctionRepo   =  $this->EM->getRepository(RefFunction::class);
        if(!$function->getDescFunction()){
          return "desc_function is empty";
        }
        $desc = $function->getDescFunction();
        $desc_query ="SELECT id FROM ref_function WHERE LOWER(desc_function)=LOWER(:desc)";
        $conn =  $this->EM->getconnection();
        $desc_funcion_chk = $conn->executeQuery($desc_query, ['desc' => $desc])->fetch();
        if($desc_funcion_chk){
          return "desc_function already exist";
        }
        $function  ->  setActiveFunction("Active");
        $function  ->  setDescFunction($function->getDescFunction());
        $function  ->  setCodeFunction($refTabelRepo->next('ref_function')); 
                  
    
     if($function  ->  getRefLabels()==null){
       return "lable is null";
     }     
        foreach($function  ->  getRefLabels() as $key => $refLabel){
             $lang     =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
             $lable_query = "SELECT id FROM ref_label WHERE LOWER(label_label)=LOWER(:refLabel) AND code_label LIKE 'FUNCTION%'";
             $conn =  $this->EM->getconnection();
             $chk_lable = $conn->executeQuery($lable_query, ['refLabel' => $refLabel])->fetch();
             if($chk_lable){
               return 'lable already exist';
             }
             if(!$lang){
                return["lang"=>$lang];
                continue;
            }
        
        $refLabelTemp  =  new RefLabel();
        $lang          =  $refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp  -> setLangLabel($lang);
        $refLabelTemp  -> setLabelLabel($refLabel);
        $refLabelTemp  -> setCodeLabel($function->getCodeFunction());
        $refLabelTemp  -> setActiveLabel("Active");
        $entityManager -> persist($refLabelTemp);
        }
       
        $entityManager -> persist($function);
        $entityManager -> flush();  
        return "ok";   
      
      }

      public function updateFunction($content,$entityManager,$id){
      
        $encoders         =  [ new JsonEncoder()];
        $normalizers      =  [new ObjectNormalizer()];
        $serializer       =  new Serializer($normalizers ,$encoders);
        $data             =  $serializer->deserialize($content, RefFunction::class, 'json');

        $refFunctionRepo      =  $this->EM->getRepository(RefFunction::class);
        $refTabelRepo     =  $this->EM->getRepository(RefTable::class);
        $refLabelRepo     =  $this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo =  $this->EM->getRepository(RefLanguage::class);
        $function             =  $refFunctionRepo->findOneBy(['id'=>$id]);
        if(!$function){
          return "function id not found";
        }
       
        $function  ->  SetDescFunction( $data  ->  getDescFunction());
       
        foreach($data ->  getRefLabels() as $key => $reflabel){
          $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
          $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$function->getCodeFunction(),'lang_label'=>$lang->getId()]);
          if (!$chkRefLabel){
              $chkRefLabel = new RefLabel;
              $chkRefLabel    ->  setLangLabel($lang);
              $chkRefLabel    ->  setCodeLabel($function->getCodeFunction());
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
        $entityManager  ->  persist($function);
        $entityManager  ->  flush();  
        return "successfully updated";
        
      
      }

      public function  getSingleFunction(int $id,string $langvalue): ?RefFunction{
        $langRepo   = $this->EM -> getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo     =  $this  ->EM ->  getRepository(RefLabel::class);        
        $FunctionRepo      =  $this  ->EM ->  getRepository(RefFunction::class);
        $paticularfunction =  $FunctionRepo->  findOneBy(['id'=>$id]);
        $languages        =  $langRepo->findAll();
        if($langvalue == "all"){
                    $refLabels  =  array();
                    foreach($languages as $language){
                        $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularfunction->getCodeFunction()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
                       }
                       $paticularfunction -> setRefLabels($refLabels); 
                return $paticularfunction;
            }
      
             $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularfunction -> getCodeFunction()]);
             $lablevalue="null";
             if(!$label){
              $paticularfunction -> setRefLabel($lablevalue);
             }
             else{
              $paticularfunction -> setRefLabel($label -> getLabelLabel());
             }
        return $paticularfunction;
      }

      public function getAllFunctions(string $langvalue,$domain){

        $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
        $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"  =>  "lang not found"];
              }
             }
      
        $labelRepo  = $this ->EM -> getRepository(RefLabel::class);        
        $functionem     = $this ->EM -> getRepository(RefFunction::class);
        if($domain == 'front'){
          $functions      = $functionem->findBy(['active_function'=>['Active']]);
        }
        if($domain == 'back'){
          $functions      = $functionem->findBy(['active_function'=>['Active','Disabled']]);
        }
        $languages  = $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($functions as  $functionkey => $function){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $function -> getCodeFunction()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $function -> setRefLabels($refLabels);
                    }
                    
                
                return $functions;
            }
        
        foreach($functions as $key => $function)
        
          {
            $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $function -> getCodeFunction()]);
             // return  $label;
            $labelvalue="null"; 
            if(!$label){
             $functions[$key] -> setRefLabel($labelvalue);
            }
            else{
             $functions[$key] -> setRefLabel($label -> getLabelLabel());
            }
            
       }
       return $functions;
      
      
      }

      public function functionStatusChange($id,$status,$entityManager){
        $encoders      = [ new JsonEncoder()];
        $normalizers   = [new ObjectNormalizer()];
        $serializer    = new Serializer($normalizers ,$encoders);
        $functionrepo      = $this  ->EM   ->  getRepository(RefFunction::class);
        $function          = $functionrepo  ->findOneBy(['id'=>$id]);
        $function          ->setActiveFunction($status);
        $entityManager ->persist($function);
        $entityManager ->flush();  
      
      
      }

    //   public function deleteFunction($id,$entityManager){
    //     $encoders      = [ new JsonEncoder()];
    //     $normalizers   = [new ObjectNormalizer()];
    //     $serializer    = new Serializer($normalizers ,$encoders);
    //     $functionrepo      = $this  ->EM   ->  getRepository(RefFunction::class);
    //     $function          = $functionrepo  ->findOneBy(['id'=>$id]);
    //     $function          ->setActiveFunction('deleted');
    //     $entityManager ->persist($function);
    //     $entityManager ->flush();  
      
      
    //   }
      
    //   public function filterFunction($status){
      
    //     $arrstatus   =  array();
    //     $function        =  array();
    //     $arrstatus   =  $status->getStatus();
    //     $functionrepo    =  $this->EM->getRepository(RefFunction::class);
    //     $function        =  $functionrepo->findBy(array('active_function' => $arrstatus));
    //     return $function;
    //   }



    //   public function getAllReqStatus(string $langvalue){

    //     $langRepo      =  $this ->EM ->getRepository(RefLanguage::class);
    //     $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
    //       if(!$lang){
    //           if($langvalue != "all"){
    //             return ["msg"  =>  "lang not found"];
    //           }
    //          }
         
    //     $labelRepo     =   $this ->EM -> getRepository(RefLabel::class);        
    //     $countryem     =   $this ->EM -> getRepository(RefRequeststatus::class);
           
    //     $countries     =   $countryem->findBy(['active_requeststatus'=>['Active','Disabled']]);
    //     $languages     =   $langRepo->findAll();
    //     if($langvalue  ==  "all"){
    //       foreach($countries as  $countrykey => $country){
    //                 $refLabels=array();
    //                 foreach($languages as $language){
    //                     $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $country -> getCodeRequeststatus()]);
    //                     if (!$label){
    //                         $refLabels[$language -> getCodeLanguage()]="";
    //                         continue;
    //                         }
    //                      $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
    //                    }
    //                    $countries[$countrykey] -> setRefLabels($refLabels);
    //                 }
                    
                
    //             return $countries;
    //         }
        
    //     foreach($countries as $key => $country)
    //     {
    //          $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $country -> getCodeRequeststatus()]);
    //          if(!$label){
    //               return ["msg"=>"label not found"];
    //          }
    //          $countries[$key] -> setRefLabel($label -> getLabelLabel());
    //     }
    //     return $countries;
      
      
    //   }
    }      
       
