<?php

namespace App\Service;

use App\Entity\RefFile;
use App\Entity\RefField;
use App\Entity\RefLabel;
use App\Entity\RefTable;
use App\Entity\DataRequest;
use App\Entity\RefDocument;
use App\Entity\RefLanguage;
use App\Entity\OptionsTabel;
use App\Entity\DataClientSab;
use App\Entity\DataClient;
use Spipu\Html2Pdf\Html2Pdf;

use App\Entity\RefAlertstatus;
use App\Entity\DataRequestFile;
use App\Entity\DataTransaction;
use App\UtilsSer\DataFieldData;
use App\Entity\RefDocumentAction;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DocumentService{

    private $EM;
    private $params;
    public function __construct(EntityManagerInterface $EM, ParameterBagInterface $params)
    {
      $this->EM = $EM;
      $this->params = $params;
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
        $fieldRepo=$this->EM->getRepository(RefDocument::class);
        $paticularfield=$fieldRepo->findOneBy(['id'=>$id]);
        $languages=$langRepo->findAll();
        if($langvalue=="all"){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularfield->getCodeDocument()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                       }
                       $paticularfield-> setRefLabels($refLabels); 
                return $paticularfield;
            }

             $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularfield->getCodeDocument()]);
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
        $fieldRepo=$this->EM->getRepository(RefDocument::class);
        $fields    =   $fieldRepo->findBy(['active_document'=>['Active','Disabled']]);
        $languages     =   $langRepo->findAll();
        if($langvalue  ==  "all"){
          foreach($fields as  $fieldkey => $field){
                    $refLabels=array();
                    foreach($languages as $language){
                        $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $field -> getCodeDocument()]);
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
             $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $field -> getCodeDocument()]);
             if(!$label){
                  return ["msg"=>"label not found"];
             }
             $fields[$key] -> setRefLabel($label -> getLabelLabel());
        }
        return $fields;
      
    }

    public function filter($status){
      $langRepo     = $this->EM->getRepository(RefLanguage::class);
      $docrepo      = $this->EM->getRepository(RefDocument::class);
      $arrstatus    = array();
      $arrstatus    = $status->getStatus();
      $labelRepo    = $this->EM->getRepository(RefLabel::class);        
      $fields       = $docrepo->findBy(array('active_document' => $arrstatus));
      $languages    = $langRepo->findAll();
  
      foreach($fields as $fieldkey => $field){
        $refLabels = array();
        foreach($languages as $language){
          $label = $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $field -> getCodeDocument()]);
          if (!$label) {
              $refLabels[$language -> getCodeLanguage()] = "";
              continue;
            }
            $refLabels[$language  -> getCodeLanguage()]  = $label -> getLabelLabel();
          }
          $fields[$fieldkey] -> setRefLabels($refLabels);
        }
      return $fields;    
    }

    public function makeContractDocument($clientid, $emailservice) {      
      $listfile = array();
      $docact  = $this->EM->getRepository(RefDocumentAction::class);
      $optionRepo = $this->EM->getRepository(OptionsTabel::class);
      $product = $optionRepo->findOneBy(['client'=>$clientid]);
      $product_id = $product->getProduct()->getId();
      $files = $docact->findBy(['productId'=>$product_id]);
      foreach ($files as $file) {
        $filesystem    = new Filesystem();
        $url           = $file->getDocumentUrl();
        $path          = $filesystem->exists($url);
        
        if ($path == false) {
          return false;
        }

        $str_arr        = explode ("/", $url); 
        $filename       = $str_arr[1];
        $str_pdf        = explode (".", $filename); 
        $pdfname        = $str_pdf[0].'.pdf';
        
        // Destination folder
        $folder         = 'file/'.$clientid.'/';
                  
        // Reading the content
        $handle  = fopen($url, "r+");
        $content = fread($handle, filesize($url));
        fclose($handle);

        $finalcontent = $emailservice->documentEdit($content, $clientid);
        // return $finalcontent;
        //replace variable in $content
        // Write html in PDF document
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->writeHTML($finalcontent, 0);
        $path = $filesystem->exists($folder);
        if ($path == false) {
          $filesystem->mkdir($folder);
        }
        // $html2pdf->pdf->Output('C:/wamp64/www/apikeprevos/public/'.$folder.$pdfname, 'F');  
        $html2pdf->pdf->Output($this->params->get('app.root').'public/'.$folder.$pdfname, 'F');  

        $listfile[] = $folder.$pdfname;
      }
      return $listfile;
    }

    public function makeRecap($clientid, $emailservice) {

      $optionRepo = $this->EM->getRepository(OptionsTabel::class);
      $product    = $optionRepo->findOneBy(['client'=>$clientid]);
      $product_id = $product->getProduct()->getId();

      $folder     = 'file/'.$clientid.'/';                                      

      // Reading the content
      $url     = "./document/recap.html";
      $handle  = fopen($url, "r+");
      $content = fread($handle, filesize($url));
      fclose($handle);

      //$finalcontent = $emailservice->documentEdit($content, $clientid);

      $filesystem   = new Filesystem();

      $html2pdf = new HTML2PDF('P', 'A4', 'fr');
      $html2pdf->writeHTML($content, 0);
      $path = $filesystem->exists($folder);
      if ($path == false) {
        $filesystem->mkdir($folder);
      }

      $html2pdf->pdf->Output($this->params->get('app.root').'public/'.$folder.'recap.pdf', 'F');
      return true;
    }

    public function makeRib($clientid, $emailservice, $entityManager) {

      $folder     = '../file/'.$clientid.'/';                                      

      // Reading the content
      $url     = "./document/fidcon.html";
      $handle  = fopen($url, "r+");
      $content = fread($handle, filesize($url));
      fclose($handle);

      $finalcontent = $emailservice->documentEdit($content, $clientid);

      $filesystem   = new Filesystem();

      $html2pdf = new HTML2PDF('P', 'A4', 'fr');
      $html2pdf->writeHTML($finalcontent, 0);
      $path = $filesystem->exists($folder);
      if ($path == false) {
        $filesystem->mkdir($folder);
      }

      $html2pdf->pdf->Output($this->params->get('app.root').'public/'.$folder.'rib_'.$clientid.'.pdf', 'F');

      $datareqrepo = $this->EM->getRepository(DataRequest::class);
      $datareq = $datareqrepo->findOneBy(['id'=>$clientid]);

      $reffilerepo   = $this->EM->getRepository(RefFile::class);
      $reffile       = $reffilerepo->findOneBy(['jsonkey'=>'RIB_Fiducial']);
      $unnid         = $this->UuidGenerate($clientid);
      $datareqfile   =  new DataRequestFile();
      $datareqfile   -> setFile($reffile);
      $datareqfile   -> setRequest($datareq);
      $datareqfile   -> setPath($folder."rib_".$clientid.".pdf");
      $datareqfile   -> setFileUuid($unnid);
      $datareqfile   -> setFilename("EER Fiducial - RIB.pdf");
      
      $entityManager -> persist($datareqfile);
      $entityManager -> flush();

      return true;
    }

    public function makeAttestationDepot($clientid, $emailservice, $entityManager) {

      $folder     = '../file/'.$clientid.'/';

      // Reading the content
      $url     = "./document/fidatt.html";
      $handle  = fopen($url, "r+");
      $content = fread($handle, filesize($url));
      fclose($handle);

      $finalcontent = $emailservice->documentEdit($content, $clientid);
      //$finalcontent = $content;

      $filesystem   = new Filesystem();

      $html2pdf = new HTML2PDF('P', 'A4', 'fr');
      $html2pdf->writeHTML($finalcontent, 0);
      $path = $filesystem->exists($folder);
      if ($path == false) {
        $filesystem->mkdir($folder);
      }

      $html2pdf->pdf->Output($this->params->get('app.root').'public/'.$folder.'attestationdepot_'.$clientid.'.pdf', 'F');

      $datareqrepo = $this->EM->getRepository(DataRequest::class);
      $datareq = $datareqrepo->findOneBy(['id'=>$clientid]);

      $reffilerepo = $this->EM->getRepository(RefFile::class);
      $reffile = $reffilerepo->findOneBy(['jsonkey'=>'attestation']);
      $unnid = $this->UuidGenerate($clientid);
      $datareqfile   =  new DataRequestFile();
      $datareqfile   -> setFile($reffile);
      $datareqfile   -> setRequest($datareq);
      $datareqfile   -> setPath($folder."attestationdepot_".$clientid.".pdf");
      $datareqfile   -> setFilename("EER Fiducial - attestation.pdf");
      $datareqfile   -> setFileUuid($unnid);
      $entityManager -> persist($datareqfile);
      $entityManager -> flush();

      return true;
    }

    public function UuidGenerate($client_id) {
      $date = date('d-m-Y_H-i-s');
      $q = "SELECT MAX(id) as value FROM data_request_file";
      $conn = $this->EM->getConnection();
      $result = $conn->executeQuery($q)->fetch();
      $currecnt_value = (string)$result["value"];
      $unnid = $client_id."_".$date."_".$currecnt_value;
      return sha1($unnid);
    }

    public function documentCopy($id){
      $sabrepo = $this->EM->getRepository(DataClientSab::class);
      $sabdata = $sabrepo->findOneBy(['request'=>$id]);
      $clientrepo = $this->EM->getRepository(DataClient::class);
      $clientdata = $clientrepo->findOneBy(['id'=>$id]);
      $fsObject = new Filesystem();
      $src_dir_path = "../file/".$id;
      $dest_dir_path = 'D:/file/'.$sabdata->getNumero().'-'.$clientdata->getCompanynameClient().'/'.$id.'-Pièces justificatives souscription/';
      $fsObject->mirror($src_dir_path, $dest_dir_path);
      return "success";
    }

    public function RefuseDocument(){
      $query = "SELECT request_id from data_request_file where request_id IN( SELECT id_request_id FROM `data_request_requeststatus` WHERE `id_requeststatus_id` IN (16,35,36,100) AND DATEDIFF(CURDATE(),date_request_requeststatus)>=90) GROUP BY `data_request_file`.`request_id`";
      $conn = $this->EM->getconnection();
      $data_request_files = $conn->executeQuery($query)->fetchAll();
      foreach($data_request_files as $data_request_file){
        $deletfilepath='../file/'.$data_request_file['request_id'];
        $filesystem = new Filesystem();
        $filesystem->remove(['symlink',$deletfilepath, 'activity.log']); 
        $refusepath = "../file/common/removed.pdf";    //refuse file path
        $refuse_filename = 'removed.pdf';      //refuse filename
        $uuid = $this->UuidGenerate($data_request_file['request_id']);
        $requestid = $data_request_file['request_id'];
        $query = "UPDATE data_request_file SET path='$refusepath',file_uuid='$uuid',filename ='$refuse_filename' WHERE request_id =$requestid";
        $conn = $this->EM->getconnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
      }
      return [];
    }

    public function RefuseDocumentParticularRequest($req_id){
      $deletfilepath='../file/'.$req_id;
      $filesystem = new Filesystem();
      $filesystem->remove(['symlink',$deletfilepath, 'activity.log']); 
      $refusepath = "../file/common/removed.pdf";    //refuse file path
      $refuse_filename = 'removed.pdf';      //refuse filename
      $uuid = $this->UuidGenerate($req_id);
      $query = "UPDATE data_request_file SET path='$refusepath',file_uuid='$uuid',filename ='$refuse_filename' WHERE request_id =$req_id";
      $conn = $this->EM->getconnection();
      $stmt = $conn->prepare($query);
      $stmt->execute();
      return [];
    }
    public function RefuseClientSabRequest(){
      $query = "SELECT id_request_id   FROM `data_request_requeststatus` WHERE `id_requeststatus_id` = 100 AND DATEDIFF(CURDATE(),date_request_requeststatus)>=90";
      $conn = $this->EM->getconnection();
      $data_request_files = $conn->executeQuery($query)->fetchAll();
      $sabDatas = array();
      // return $data_request_files;
      foreach($data_request_files as $data_request_file){
        $reqid = $data_request_file['id_request_id'];
        $transactionrepo = $this->EM->getRepository(DataTransaction::class);
        $transactiondata = $transactionrepo->findOneBy(['client'=>$reqid]);
        // return $transactiondata;
        if(!$transactiondata){
          $query = "SELECT id_requeststatus_id  from (SELECT * FROM `data_request_requeststatus` WHERE `id_request_id` = $reqid ORDER BY `data_request_requeststatus`.`id`  DESC limit 2) table_alias
          order by id ASC limit 1";
          $conn = $this->EM->getconnection();
    
          $data = $conn->executeQuery($query)->fetch();       //requeststatus of before 100
          // return $data;
          $checkstatus = [99,13,31,32];  
          if (in_array($data['id_requeststatus_id'], $checkstatus))
          {
            $query = "SELECT request_id,numero FROM `data_client_sab` WHERE `request_id`=$reqid AND (`is_closed` is null OR `is_closed` = 0)";
            $conn = $this->EM->getconnection();
            $sabdata = $conn->executeQuery($query)->fetch();  
            if($sabdata){
              array_push($sabDatas,$sabdata);
            }
          }
        }
        else{
          continue;
        }
        
      }
      return $sabDatas;
    }  
    public function closeClientSab($numero,$request_id){
      $sabrepo = $this->EM->getRepository(DataClientSab::class);
      $sabdata = $sabrepo->findOneBy(['request'=>$request_id,'numero'=>$numero]);
      if($sabdata){
        $sabdata->setIsClosed(true);
        $this->EM -> persist($sabdata);
        $this->EM -> flush();
      }
      return $sabdata;
    }
}