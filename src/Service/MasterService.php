<?php

namespace App\Service;

use App\Entity\RefCountry;
use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use App\Entity\RefBank;
use App\Entity\RefLegalform;
use App\Entity\RefEpa;
use App\Entity\RefCompanyType;
use App\Entity\RefTypeClient;
use App\Entity\RefCivility;
use App\Entity\RefWhoClient;
use App\Entity\RefWhereclient;
use App\Entity\RefWheresupplier;
use App\Entity\RefFunction;
use App\Entity\RefTable;

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

class MasterService 
{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
       

    public function getSingleCivility(string $langvalue,int $id): ?RefCivility{
        $langRepo=$this->EM->getRepository(RefLanguage::class);
       $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
         if(!$lang){
             if($langvalue != "all"){
               return ["msg"=>"lang not found"];
             }
            }
       $labelRepo=$this->EM->getRepository(RefLabel::class);        
       $civilityRepo=$this->EM->getRepository(RefCivility::class);
       $particularcivility=$civilityRepo->findOneBy(['id'=>$id]);
       $languages=$langRepo->findAll();
       if($langvalue=="all"){
                   $refLabels=array();
                   foreach($languages as $language){
                       $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularcivility->getCodeCivility()]);
                       if (!$label){
                           $refLabels[$language->getCodeLanguage()]="";
                           continue;
                           }
                        $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                      }
                      $particularcivility-> setRefLabels($refLabels);
               return $particularcivility;
           }
            $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularcivility->getCodeCivility()]);
            if(!$label){
                 return ["msg"=>"label not found"];
            }
            $particularcivility->setRefLabel($label->getLabelLabel());
        return $particularcivility;
       
   }

   public function getSingleFunction(string $langvalue,int $id): ?RefFunction{
    $langRepo=$this->EM->getRepository(RefLanguage::class);
   $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
     if(!$lang){
         if($langvalue != "all"){
           return ["msg"=>"lang not found"];
         }
        }
   $labelRepo=$this->EM->getRepository(RefLabel::class);        
   $functionRepo=$this->EM->getRepository(RefFunction::class);
   $particularfunction=$functionRepo->findOneBy(['id'=>$id]);
   $languages=$langRepo->findAll();
   if($langvalue=="all"){
               $refLabels=array();
               foreach($languages as $language){
                   $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularfunction->getCodeFunction()]);
                   if (!$label){
                       $refLabels[$language->getCodeLanguage()]="";
                       continue;
                       }
                    $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                  $particularfunction-> setRefLabels($refLabels);
               }
               
           
           return $particularfunction;
       }
        $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularfunction->getCodeFunction()]);
        if(!$label){
             return ["msg"=>"label not found"];
        }
        $particularfunction->setRefLabel($label->getLabelLabel());
    return $particularfunction;
   
}
public function getSingleWhoclient(string $langvalue,int $id): ?RefWhoclient{
   $langRepo=$this->EM->getRepository(RefLanguage::class);
   $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
     if(!$lang){
         if($langvalue != "all"){
           return ["msg"=>"lang not found"];
         }
        }
   $labelRepo=$this->EM->getRepository(RefLabel::class);        
   $whoclientRepo=$this->EM->getRepository(RefWhoclient::class);
   $particularwhoclient=$whoclientRepo->findOneBy(['id'=>$id]);
   $languages=$langRepo->findAll();
   if($langvalue=="all"){
               $refLabels=array();
               foreach($languages as $language){
                   $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularwhoclient->getCodeWhoclient()]);
                   if (!$label){
                       $refLabels[$language->getCodeLanguage()]="";
                       continue;
                       }
                    $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                  }
                  $particularwhoclient-> setRefLabels($refLabels);
           return $particularwhoclient;
       }
        $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularwhoclient->getCodeWhoclient()]);
        if(!$label){
             return ["msg"=>"label not found"];
        }
        $particularwhoclient->setRefLabel($label->getLabelLabel());
    return $particularwhoclient;
   
}
public function getSingleLegalForm(string $langvalue,int $id): ?RefLegalform{
    $langRepo=$this->EM->getRepository(RefLanguage::class);
   $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
     if(!$lang){
         if($langvalue != "all"){
           return ["msg"=>"lang not found"];
         }
        }
   $labelRepo=$this->EM->getRepository(RefLabel::class);        
   $legalformRepo=$this->EM->getRepository(RefLegalform::class);
   $paticularlegalform=$legalformRepo->findOneBy(['id'=>$id]);
  //  $languages=$langRepo->findAll();
  //  if($langvalue=="all"){
  //              $refLabels=array();
  //              foreach($languages as $language){
  //                  $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularlegalform->getCodeLegalform()]);
  //                  if (!$label){
  //                      $refLabels[$language->getCodeLanguage()]="";
  //                      continue;
  //                      }
  //                   $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
  //                 $paticularlegalform-> setRefLabels($refLabels);
  //              }
               
           
  //          return $paticularlegalform;
  //      }
  //       $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularlegalform->getCodeLegalform()]);
  //       if(!$label){
  //            return ["msg"=>"label not found"];
  //       }
  //       $paticularlegalform->setRefLabel($label->getLabelLabel());
   return $paticularlegalform;
}

public function getSingleEpa(string $langvalue,int $id): ?RefEpa{
    $langRepo=$this->EM->getRepository(RefLanguage::class);
    $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
      if(!$lang){
          if($langvalue != "all"){
            return ["msg"=>"lang not found"];
          }
         }
    $labelRepo=$this->EM->getRepository(RefLabel::class);        
    $epaem=$this->EM->getRepository(RefEpa::class);
    $particularepa=$epaem->findOneBy(['id'=>$id]);
    // $languages=$langRepo->findAll();
    // if($langvalue=="all"){
    //             $refLabels=array();
    //             foreach($languages as $language){
    //                 $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularepa->getCodeEpa()]);
    //                 if (!$label){
    //                     $refLabels[$language->getCodeLanguage()]="";
    //                     continue;
    //                     }
    //                  $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
    //                }
    //                $particularepa-> setRefLabels($refLabels);
    //         return $particularepa;
    //     }
    //      $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularepa->getCodeEpa()]);
    //      if(!$label){
    //           return ["msg"=>"label not found"];
    //      }
    //      $particularepa->setRefLabel($label->getLabelLabel());
    return $particularepa;
}
public function getSingleBank(string $langvalue,int $id): ?RefBank{
    $langRepo=$this->EM->getRepository(RefLanguage::class);
   $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
     if(!$lang){
         if($langvalue != "all"){
           return ["msg"=>"lang not found"];
         }
        }
   $labelRepo=$this->EM->getRepository(RefLabel::class);        
   $bankem=$this->EM->getRepository(RefBank::class);
   $paticularbank=$bankem->findBy(['id'=>$id]);
   $languages=$langRepo->findAll();
   if($langvalue=="all"){
               $refLabels=array();
               foreach($languages as $language){
                   $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularbank->getCodeBank()]);
                   if (!$label){
                       $refLabels[$language->getCodeLanguage()]="";
                       continue;
                       }
                    $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                  }
                  $paticularbank[$bankkey]-> setRefLabels($refLabels);
           return $paticularbank;
       }
        $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularbank->getCodeBank()]);
        if(!$label){
           return ["msg"=>"label not found"];
        }
        $paticularbank[$key]->setRefLabel($label->getLabelLabel());
   return $paticularbank;
}
public function getSingleTypeClient(string $langvalue,int $id): ?RefTypeclient{
    $langRepo=$this->EM->getRepository(RefLanguage::class);
    $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
      if(!$lang){
          if($langvalue != "all"){
            return ["msg"=>"lang not found"];
          }
         }
    $labelRepo=$this->EM->getRepository(RefLabel::class);        
    $typeclientRepo=$this->EM->getRepository(RefTypeclient::class);
    $paticulartypeclient=$typeclientRepo->findOneBy(['id'=>$id]);
    $languages=$langRepo->findAll();
    if($langvalue=="all"){
                $refLabels=array();
                foreach($languages as $language){
                    $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticulartypeclient->getCodeTypeclient()]);
                    if (!$label){
                        $refLabels[$language->getCodeLanguage()]="";
                        continue;
                        }
                     $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                   }
                   $paticulartypeclient-> setRefLabels($refLabels);
               
                
            
            return $paticulartypeclient;
        }
         $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticulartypeclient->getCodeTypeclient()]);
         if(!$label){
              return ["msg"=>"label not found"];
         }
         $paticulartypeclient->setRefLabel($label->getLabelLabel());
    return $paticulartypeclient;
}
public function getSingleCompanyType(string $langvalue,int $id): ?RefCompanytype{
    $langRepo=$this->EM->getRepository(RefLanguage::class);
   $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
     if(!$lang){
         if($langvalue != "all"){
           return ["msg"=>"lang not found"];
         }
        }
   $labelRepo=$this->EM->getRepository(RefLabel::class);        
   $companytyperepo=$this->EM->getRepository(RefCompanytype::class);
   $paticularcompanytype=$companytyperepo->findOneBy(['id'=>$id]);
   $languages=$langRepo->findAll();
   if($langvalue=="all"){
               $refLabels=array();
               foreach($languages as $language){
                   $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularcompanytype->getCodeCompanytype()]);
                   if (!$label){
                       $refLabels[$language->getCodeLanguage()]="";
                       continue;
                       }
                    $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                  }
                  $paticularcompanytype-> setRefLabels($refLabels);
              
               
           
           return $paticularcompanytype;
       }
       $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularcompanytype->getCodeCompanytype()]);
       if(!$label){
           return ["msg"=>"label not found"];
       }
       $paticularcompanytype->setRefLabel($label->getLabelLabel());
   return $paticularcompanytype;
}

public function getSingleWhereclient(string $langvalue,int $id): ?RefWhereclient{
  $langRepo=$this->EM->getRepository(RefLanguage::class);
  $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
    if(!$lang){
        if($langvalue != "all"){
          return ["msg"=>"lang not found"];
        }
       }
  $labelRepo=$this->EM->getRepository(RefLabel::class);        
  $whereclientRepo=$this->EM->getRepository(RefWhereclient::class);
  $paticularwhereclient=$whereclientRepo->findOneBy(['id'=>$id]);
   $languages=$langRepo->findAll();
  if($langvalue=="all"){
              $refLabels=array();
              foreach($languages as $language){
                  $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularwhereclient->getCodeWhereclient()]);
                  if (!$label){
                      $refLabels[$language->getCodeLanguage()]="";
                      continue;
                      }
                   $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                 }
                 $paticularwhereclient-> setRefLabels($refLabels);

          return $paticularwhereclient;
      }

       $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularwhereclient->getCodeWhereclient()]);
       if(!$label){
            return ["msg"=>"label not found"];
       }
       $paticularwhereclient->setRefLabel($label->getLabelLabel());
  return $paticularwhereclient;
}




public function getSingleWheresupplier(string $langvalue,int $id): ?RefWheresupplier{
  $langRepo=$this->EM->getRepository(RefLanguage::class);
  $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
    if(!$lang){
        if($langvalue != "all"){
          return ["msg"=>"lang not found"];
        }
       }
  $labelRepo=$this->EM->getRepository(RefLabel::class);        
  $wheresupplierRepo=$this->EM->getRepository(RefWheresupplier::class);
  $particularwheresupplier=$wheresupplierRepo->findOneBy(['id'=>$id]);
  $languages=$langRepo->findAll();
  if($langvalue=="all"){
              $refLabels=array();
              foreach($languages as $language){
                  $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$particularwheresupplier->getCodeWheresupplier()]);
                  if (!$label){
                      $refLabels[$language->getCodeLanguage()]="";
                      continue;
                      }
                   $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                 }
                 $particularwheresupplier-> setRefLabels($refLabels);
          return $particularwheresupplier;
      }
       $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$particularwheresupplier->getCodeWheresupplier()]);
       if(!$label){
            return ["msg"=>"label not found"];
       }
       $particularwheresupplier->setRefLabel($label->getLabelLabel());
  return $particularwheresupplier;
}


public function insertCountry($content,$entityManager){

  $encoders = [ new JsonEncoder()];
  $normalizers = [new ObjectNormalizer()];
  $serializer = new Serializer($normalizers ,$encoders);
  $country = $serializer->deserialize($content, RefCountry::class, 'json');
  // $entityManager = $this->EM->getManager();
  $country->setActiveCountry("Active");
  $refTabelRepo=$this->EM->getRepository(RefTable::class);
  $refLabelRepo=$this->EM->getRepository(RefLabel::class);
  $refLangLabelRepo=$this->EM->getRepository(RefLanguage::class);
  $refCountryRepo=$this->EM->getRepository(RefCountry::class);
  

      $country->setCodeCountry($refTabelRepo->next('ref_country'));            
      $country->setDescCountry($country->getDescCountry());
              
  foreach( $country->getRefLabels() as $key => $refLabel){
      $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
       if(!$lang){
          return["lang"=>$lang];
          continue;
      }
  
  $refLabelTemp = new RefLabel();
  $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
  $refLabelTemp->setLangLabel($lang);
  $refLabelTemp->setLabelLabel($refLabel);
  $refLabelTemp->setCodeLabel($country->getCodeCountry());
  $refLabelTemp->setActiveLabel("Active");
  $entityManager->persist($refLabelTemp);
  }
 
  $entityManager->persist($country);
  $entityManager->flush();  
  return "successfully added";   

}
public function updateCountry($content,$entityManager,$id){

  $encoders = [ new JsonEncoder()];
  $normalizers = [new ObjectNormalizer()];
  $serializer = new Serializer($normalizers ,$encoders);
  $data = $serializer->deserialize($content, RefCountry::class, 'json');
  // $entityManager = $this->EM->getManager();

  $refCountryRepo=$this->EM->getRepository(RefCountry::class);
  $refTabelRepo=$this->EM->getRepository(RefTable::class);
  $refLabelRepo=$this->EM->getRepository(RefLabel::class);
  $refLangLabelRepo=$this->EM->getRepository(RefLanguage::class);
  $country=$refCountryRepo->findOneBy(['id'=>$id]);
  if(!$country){
    return "Country id not found";
  }
  $country->setDescCountry($data->getDescCountry());
  $country->setActiveCountry("Active");
  foreach($data->getRefLabels() as $key => $reflabel){
    $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
    $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$country->getCodeCountry(),'lang_label'=>$lang->getId()]);
    if ($chkRefLabel){
        $chkRefLabel->setLabelLabel($reflabel);
        $entityManager->persist($chkRefLabel);
       
    }
  }
  $entityManager->persist($country);
  $entityManager->flush();  
  return "successfully updated";
  

}

public function getSingleCountry(string $langvalue,int $id){
  $langRepo=$this->EM->getRepository(RefLanguage::class);
  $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
    if(!$lang){
        if($langvalue != "all"){
          return ["msg"=>"lang not found"];
        }
       }
  $labelRepo=$this->EM->getRepository(RefLabel::class);        
  $CountryRepo=$this->EM->getRepository(RefCountry::class);
  $paticularcountry=$CountryRepo->findOneBy(['id'=>$id]);
  // $languages=$langRepo->findAll();
  // if($langvalue=="all"){
  //             $refLabels=array();
  //             foreach($languages as $language){
  //                 $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularcountry->getCodeCountry()]);
  //                 if (!$label){
  //                     $refLabels[$language->getCodeLanguage()]="";
  //                     continue;
  //                     }
  //                  $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
  //                }
  //                $paticularcountry-> setRefLabels($refLabels); 
  //         return $paticularcountry;
  //     }

  //      $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularcountry->getCodeCountry()]);
  //      if(!$label){
  //           return null;
  //      }
  //      $paticularcountry->setRefLabel($label->getLabelLabel());
  return $paticularcountry;
}

public function getAllCountries(string $langvalue): ?array{
  $langRepo=$this->EM->getRepository(RefLanguage::class);
  $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
    if(!$lang){
        if($langvalue != "all"){
          return ["msg"=>"lang not found"];
        }
       }

  $labelRepo=$this->EM->getRepository(RefLabel::class);        
  $countryem=$this->EM->getRepository(RefCountry::class);
  
  $countries=$countryem->findAll();
  $languages=$langRepo->findAll();
  if($langvalue=="all"){
    foreach($countries as  $countrykey => $country){
              $refLabels=array();
              foreach($languages as $language){
                  $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$country->getCodeCountry()]);
                  if (!$label){
                      $refLabels[$language->getCodeLanguage()]="";
                      continue;
                      }
                   $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                 }
                 $countries[$countrykey]-> setRefLabels($refLabels);
              }
              
          
          return $countries;
      }
  
  foreach($countries as $key => $country)
  {
       $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$country->getCodeCountry()]);
       if(!$label){
            return ["msg"=>"label not found"];
       }
       $countries[$key]->setRefLabel($label->getLabelLabel());
  }
  return $countries;


}
public function statusChange($id,$status,$entityManager){
  $encoders = [ new JsonEncoder()];
  $normalizers = [new ObjectNormalizer()];
  $serializer = new Serializer($normalizers ,$encoders);
  // $country = $serializer->deserialize($content, RefCountry::class, 'json');
  // $entityManager = $this->EM->getManager();
  // $country->setActiveCountry("Active");
  $countryrepo=$this->EM->getRepository(RefCountry::class);
  $country=$countryrepo->findOneBy(['id'=>$id]);
  $country->setActiveCountry($status);
  $entityManager->persist($country);
  $entityManager->flush();  


}

public function filter($status){

  $arrstatus=array();
  $country=array();
  $arrstatus=$status->getStatus();
  $countryrepo=$this->EM->getRepository(RefCountry::class);
  $country=$countryrepo->findBy(array('active_country' => $arrstatus));
  return $country;
}
public function getSingleCountryv1(string $langvalue,int $id): ?RefCountry{
  $langRepo   = $this->EM -> getRepository(RefLanguage::class);
  $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
    if(!$lang){
        if($langvalue   != "all"){
          return ["msg" => "lang not found"];
        }
       }
  $labelRepo        =  $this  ->em ->  getRepository(RefLabel::class);        
  $CountryRepo      =  $this  ->em ->  getRepository(RefCountry::class);
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

} 