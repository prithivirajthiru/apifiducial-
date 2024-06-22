<?php

namespace App\Service;

use App\Entity\RefTable;
use App\Entity\RefField;
use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use App\Entity\DataRequest;
use App\Entity\RefAlertstatus;
use App\Entity\RefDocument;
use App\Entity\DataField;
use App\Entity\OptionsTabel;
use App\UtilsSer\DataFieldData;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
class FieldService{

    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
    public function insert($fielddata,$entityManager){
        $refTabelRepo=$this->EM->getRepository(RefTable::class);
        $refLabelRepo=$this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo=$this->EM->getRepository(RefLanguage::class);
        $refFieldRepo=$this->EM->getRepository(RefField::class);
        $refAlertStatusRepo=$this->EM->getRepository(RefAlertstatus::class);
        $refDocumentRepo=$this->EM->getRepository(RefDocument::class);

        $checkalert=$refAlertStatusRepo->findOneBy(['id'=>$fielddata->getAlertStatus()]);
        $checkdocument=$refDocumentRepo->findOneBy(['id'=>$fielddata->getDocument()]);
        if(!$checkalert){
            return 'invalid alertstatus_id';
        }
        if(!$checkdocument){
            return 'invalid Document_id';
        }
        // $entityManager = $this->EM->getManager();
        $field=new RefField;
        if($fielddata->getCodeField()==""){
            $field->setCodeField($refTabelRepo->next('ref_field'));           
        }
        $secField= $refFieldRepo->find($fielddata->getCodeId()? $fielddata->getCodeId() : -1);
        if(!$secField){
            $secField=$field;
        }else{
            $secField->setRefLabels($field->getRefLabels());
        }      
        foreach( $fielddata->getRefLabels() as $key => $refLabel){
            $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$secField->getCodeField(),'lang_label'=>$lang->getId()]);
        if ($chkRefLabel){
            $chkRefLabel->setLabelLabel($refLabel);
            continue;
        }
        $refLabelTemp = new RefLabel();
        $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secField->getCodeField());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secField->setDescField($fielddata->getDescField());
        $secField->setLabel($fielddata->getLabel());
        $secField->setFieldId($fielddata->getFieldId());
        if ($fielddata->getBoxId() !== null) {
            $secField->setBoxId($fielddata->getBoxId());
        }
        $secField->setIsMandatory($fielddata->getIsMandatory());
        $secField->setAlertStatus($checkalert);
        $secField->setDocument($checkdocument);
        $secField->setChangeAccess($fielddata->getChangeAccess());
        $secField->setDescBack($fielddata->getDescBack());
        $secField->setActiveField("Active");
        $entityManager->persist($secField);
        $entityManager->flush();  
        return $secField; 
    }


    public function update($fielddata,$entityManager){
        $refTabelRepo=$this->EM->getRepository(RefTable::class);
        $refLabelRepo=$this->EM->getRepository(RefLabel::class);
        $refLangLabelRepo=$this->EM->getRepository(RefLanguage::class);
        $refFieldRepo=$this->EM->getRepository(RefField::class);
        $refAlertStatusRepo=$this->EM->getRepository(RefAlertstatus::class);
        $refDocumentRepo=$this->EM->getRepository(RefDocument::class);
        // $entityManager = $this->EM->getManager();
        $field=$refFieldRepo->findOneBy(['id'=>$fielddata->getId()]);
        $checkalert=$refAlertStatusRepo->findOneBy(['id'=>$fielddata->getAlertStatus()]);
        $checkdocument=$refDocumentRepo->findOneBy(['id'=>$fielddata->getDocument()]);
        $secField= $refFieldRepo->find($fielddata->getCodeId()? $fielddata->getCodeId() : -1);
        if(!$secField){
            $secField=$field;
        }else{
            $secField->setRefLabels($field->getRefLabels());
        }      
        foreach( $fielddata->getRefLabels() as $key => $refLabel){
            $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$secField->getCodeField(),'lang_label'=>$lang->getId()]);
        if ($chkRefLabel){
            $chkRefLabel->setLabelLabel($refLabel);
            $entityManager->persist($chkRefLabel);
        }
        // $refLabelTemp = new RefLabel();
        // $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
        // $refLabelTemp->setLangLabel($lang);
        // $refLabelTemp->setLabelLabel($refLabel);
        // $refLabelTemp->setCodeLabel($secField->getCodeField());
        // $refLabelTemp->setActiveLabel("Active");
     
        }
        $secField->setDescField($fielddata->getDescField());
        $secField->setLabel($fielddata->getLabel());
        $secField->setFieldId($fielddata->getFieldId());
        if ($fielddata->getBoxId() !== null) {
            $secField->setBoxId($fielddata->getBoxId());
        }
        $secField->setIsMandatory($fielddata->getIsMandatory());
        $secField->setActiveField($fielddata->getActiveField());
        $secField->setAlertStatus($checkalert);
        $secField->setDocument($checkdocument);
        $secField->setDescBack($fielddata->getDescBack());        
        $secField->setChangeAccess($fielddata->getChangeAccess());
        $entityManager->persist($secField);
        $entityManager->flush();  
        return $secField; 
    }

    public function filterFieldValidation($status){
      
        $arrstatus     =  array();
        $field         =  array();
        $arrstatus     =  $status->getStatus();
        $fieldrepo     =  $this->EM->getRepository(RefField::class);
        $field         =  $fieldrepo->findBy(array('active_field' => $arrstatus),array('id' => 'ASC'));
        return $field;
      }
      public function filterAlertStatus($status){
      
        $arrstatus     =  array();
        $alert         =  array();
        $arrstatus     =  $status->getStatus();
        $alertrepo     =  $this->EM->getRepository(RefAlertstatus::class);
        $alert         =  $alertrepo->findBy(array('active_alertstatus' => $arrstatus));
        return $alert;
      }
   
      public function filterDocument($status){
      
        $arrstatus        =  array();
        $document         =  array();
        $arrstatus        =  $status->getStatus();
        $documentrepo     =  $this->EM->getRepository(RefDocument::class);
        $document         =  $documentrepo->findBy(array('active_document' => $arrstatus));
        return $document;
      }
   

    public function getSingle($langvalue,$id){
        $langRepo=$this->EM->getRepository(RefLanguage::class);
        $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo=$this->EM->getRepository(RefLabel::class);        
        $fieldRepo=$this->EM->getRepository(RefField::class);
        $paticularfield=$fieldRepo->findOneBy(['id'=>$id]);
        $languages=$langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularfield->getCodeField()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                       }
                       $paticularfield-> setRefLabels($refLabels); 
                return $paticularfield;
            }

             $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularfield->getCodeField()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $paticularfield->setRefLabel($label->getLabelLabel());
        return $paticularfield;
    }

    public function getAll($langvalue){
        $langRepo=$this->EM->getRepository(RefLanguage::class);
        $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue != "all"){
                return ["msg"=>"lang not found"];
              }
             }
        $labelRepo=$this->EM->getRepository(RefLabel::class);        
        $fieldRepo=$this->EM->getRepository(RefField::class);
        $fields    =   $fieldRepo->findBy(['active_field'=>['Active','Disabled']]);
        $languages     =   $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($fields as  $fieldkey => $field){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $field -> getCodeField()]);
                        if (!$label){
                            $refLabels[$language -> getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
                       }
                       $fields[$fieldkey] -> setRefLabels($refLabels);
                    }
                    
                
                return $fields;
            }
        
        foreach($fields as $key => $field)
        {
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $field -> getCodeField()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $fields[$key] -> setRefLabel($label -> getLabelLabel());
        }
        return $fields;
      
    }

    public function dataFieldInsert($fielddata,$entityManager){
        // $dataFieldRepo=$this->EM->getRepository(DataField::class);

        $dataRequestRepo=$this->EM->getRepository(DataRequest::class);
        $RefFieldRepo=$this->EM->getRepository(RefField::class);
        $datarequest=$dataRequestRepo->findOneBy(['id'=>$fielddata->getDatarequest()]);
        $Reffield=$RefFieldRepo->findOneBy(['id'=>$fielddata->getRefField()]);
        if(!$datarequest){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','datarequest id invslid');    
        }
        if(!$Reffield){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Reffield id invslid');    
        }
        $datafield=new DataField;
        $datafield->setRefField($Reffield);
        $datafield->setIscorrect($fielddata->getIscorrect());
        $datafield->setComments($fielddata->getComments());
        $datafield->setUserlogin($fielddata->getUserlogin());
        $datafield->setDatarequest($datarequest);
        $datafield->setStatus($fielddata->getStatus());
        $datafield->setDate(new \DateTime());
        $datafield->setValue($fielddata->getValue());
        $entityManager->persist($datafield);
        $entityManager->flush();  
         return $datafield;
        

    }

    public function getDataField($id,$datareq,$datarepo){
        $DataFieldRepo=$this->EM->getRepository(DataField::class);
        $data=$DataFieldRepo->findOneBy(['id'=>$id]);
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize($data->getDate());
        
        if(!$data){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','dataField id invalid');    
        }
      $datafield=new DataFieldData;
      $datafield->setId($data->getId());
      $datafield->setRefField($data->getRefField());
      $datafield->setIscorrect($data->getIscorrect());
      $datafield->setComments($data->getComments());
      $datafield->setUserlogin($data->getUserlogin());
      $datafield->setDatarequest($datareq->getDataRequest($datarepo,$id));
      $datafield->setStatus($data->getStatus());
      $datafield->setValue($data->getValue());
      $datafield->setDate($dateAsString);

      return $datafield;


    }
    public function filterFieldValidationUsingClient($client_id,$status){
      
        $arrstatus     =  array();
        $field         =  array();
        $arrstatus     =  $status->getStatus();
        $fieldrepo     =  $this->EM->getRepository(RefField::class);
        $optionrepo     =  $this->EM->getRepository(OptionsTabel::class);
        $optiondata = $optionrepo->findOneBy(['client'=>$client_id]);
        if($optiondata){
            // return $optiondata->getCheque();
            if($optiondata->getCheque()==false || $optiondata->getCheque()==null){
                $fields         =  $fieldrepo->findBy(array('active_field' => $arrstatus),array('id' => 'ASC'));
                foreach($fields as $field){
                    if($field->getId()==391 || $field->getId()==358){
                        $field->setIsMandatory("no");
                    }
                }
                return $fields;
            }
            else{
                $fields         =  $fieldrepo->findBy(array('active_field' => $arrstatus),array('id' => 'ASC'));
                return $fields;
            }
           
        }
        return "ko";

      }
    }
