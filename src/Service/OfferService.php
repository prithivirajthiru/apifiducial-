<?php

namespace App\Service;
use App\Entity\RefOfferType;
use App\Entity\DataOffer;
use App\Entity\DataProductOffer;
use App\Entity\RefProduct;
use App\Entity\RefTable;
use App\Entity\DataClientOffer;
use App\UtilsV3\StepV1;
use App\Entity\DataClient;
use App\Utils\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


use Doctrine\ORM\EntityManagerInterface;

class OfferService 
{

    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
    public function addRefOfferType($request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $promotype = $serializer->deserialize($content, RefOfferType::class, 'json');
        $entityManager = $this->EM;
        $refTabelRepo = $this->EM->getRepository(RefTable::class);
        $RefOfferTypeRepo = $this->EM->getRepository(RefOfferType::class);
        $promoid = $promotype->getId();
        if($promoid){
            $RefOfferType = $RefOfferTypeRepo->findOneBy(["id"=>$promoid]);
            if(!$RefOfferType){
                return 'invalid offettype id';
            }
        }
        else{
            $RefOfferType = new RefOfferType;
            if($promotype->getCode()==""){
                $RefOfferType->setCode($refTabelRepo->next('ref_offer_type'));           
            }
        }
       
        $RefOfferType->setValue($promotype->getValue());
        $RefOfferType->setIsActive(true);
        $entityManager->persist($RefOfferType);
        $entityManager->flush(); 
        return $RefOfferType;

    }
    public function getSingleRefOffertype($id)
    {
        $RefOfferTypeRepo = $this->EM->getRepository(RefOfferType::class);
        $RefOfferType = $RefOfferTypeRepo->findOneBy(["id"=>$id]);
        if(!$RefOfferType){
            return 'invalid promotypeid';
        }
        return $RefOfferType;
        
    }
    public function getdeleteRefOfferType($id)
    {
        $RefOfferTypeRepo = $this->EM->getRepository(RefOfferType::class);
        $RefOfferType = $RefOfferTypeRepo->findOneBy(["id"=>$id]);
        if(!$RefOfferType){
            return 'invalid promotypeid';
        }
        $this->EM->persist($RefOfferType);
        $this->EM->flush(); 
        return [];
        
    }
    public function getallRefOffertype()
    {
        $RefOfferTypeRepo = $this->EM->getRepository(RefOfferType::class);
        $RefOfferType = $RefOfferTypeRepo->findAll();
        return $RefOfferType;
    }
    public function typeRefOfferType($type,$id)
    {
        $entityManager = $this->EM;
        $RefOfferTypeRepo = $this->EM->getRepository(RefOfferType::class);
        $RefOfferType = $RefOfferTypeRepo->findOneBy(["id"=>$id]);
        if(!$RefOfferType){
            return'invalid promotypeid';
        }
        if($type=="enable"){
            $RefOfferType->setIsActive(true);
        }
        if($type=="disable"){
            $RefOfferType->setIsActive(false);
        }
        $entityManager->persist($RefOfferType);
        $entityManager->flush(); 
        return $RefOfferType;

    }


    public function addDataOffer($request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content, DataOffer::class, 'json');
        $entityManager = $this->EM;
        $DataProductOfferRepo = $this->EM->getRepository(DataProductOffer::class);
        $productRepo = $this->EM->getRepository(RefProduct::class);
        $typeRepo = $this->EM->getRepository(RefOfferType::class);
        $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
        if($data->getProductIds()){
            foreach($data->getProductIds() as $productid){
                $product = $productRepo->findOneBy(["id"=>$productid]);
                if(!$product){
                    return ["ko","invalid product id"];
                }
            }
        }
        $type = $typeRepo->findOneBy(["id"=>$data->getTypeId()]);
        if(!$type){
            return ["ko","Invalid type id"];
        }
        if($data->getTypeId()==3){
            $data->setValue(null);
        }
        if( $data->getId()){
            
            $dataoffer = $DataOfferRepo->findOneBy(["id"=>$data->getId()]);
            if(!$dataoffer){
                return ["ko","Invalid id"];
            }
        }
        else{
            $dataoffer = new DataOffer;
        }
        $checkoffercode = $DataOfferRepo->findOneBy(["offerCode"=>$data->getOfferCode()]);
        // return ["ok", $checkoffercode ];s
        if($checkoffercode){
            if( $data->getId()){
                if($checkoffercode->getId()!=$data->getId()){
                    return ["ko","OfferCode already exist"];
                }
            }
            else{
                return ["ko","OfferCode already exist"];
            }
        }
        $dataoffer->setRefInternal($data->getRefInternal());
        $dataoffer->setDescInternal($data->getDescInternal());
        $dataoffer->setOfferCode($data->getOfferCode());
        $dataoffer->setValue($data->getValue());
        $dataoffer->setFromDate(new \DateTime($data->getFromDateString()));
        $dataoffer->setToDate(new \DateTime($data->getToDateString()));
        $dataoffer->setMsgClient($data->getMsgClient());
        $dataoffer->setMsgExpiration($data->getMsgExpiration());
        $dataoffer->setIsActive(true);
        $dataoffer->setMaxQuantity($data->getMaxQuantity());
        $dataoffer->setType($type);
        $entityManager->persist($dataoffer);
        $entityManager->flush(); 
        $dataproductoffercheck = $DataProductOfferRepo->findBy(["offer"=>$data->getId()]);
        if($dataproductoffercheck){
            foreach($dataproductoffercheck as $check){
                if(!in_array($check->getProduct()->getId(),$data->getProductIds())){
                    $entityManager->remove($check);
                    $entityManager->flush(); 
                }
            }
        }
        foreach($data->getProductIds() as $productid){
            if($data->getId()){
                
                $dataproductoffer = $DataProductOfferRepo->findOneBy(["offer"=>$data->getId(),"product"=>$productid]);
                if(!$dataproductoffer){
                    $dataproductoffer = new DataProductOffer;
                }
                else{
                    continue;                
                }
            }
            else{
                $dataproductoffer = new DataProductOffer;
            }
            $product = $productRepo->findOneBy(["id"=>$productid]);
            $dataproductoffer->setOffer($dataoffer);
            $dataproductoffer->setProduct($product);
            $entityManager->persist($dataproductoffer);
            $entityManager->flush(); 
        }
        return ["ok",$dataoffer];
    }
    public function getSingleDataOffer($id)
    {
        $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
        $DataProductOfferRepo = $this->EM->getRepository(DataProductOffer::class);
        $DataOffer = $DataOfferRepo->findOneBy(["id"=>$id]);
        if(!$DataOffer){
            return 'invalid id';
        }
        $productoffer = $DataProductOfferRepo->findBy(["offer"=>$id]);
        $DataOffer->setProductOffers($productoffer);
        return $DataOffer;
        
    }
    public function getdeleteDataOffer($id)
    {
        $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
        $DataProductOfferRepo = $this->EM->getRepository(DataProductOffer::class);
        $DataOffer = $DataOfferRepo->findOneBy(["id"=>$id]);
        if(!$DataOffer){
            return 'invalid id';
        }
        $productoffers = $DataProductOfferRepo->findBy(["offer"=>$id]);
        if($productoffers){
            foreach($productoffers as $productoffer){
                $this->EM->remove($productoffer);
                $this->EM->flush(); 
            }
        }
        $this->EM->remove($DataOffer);
        $this->EM->flush(); 
        return [];
        
    }
    public function getallDataOffer()
    {
        $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
        $DataOffers = $DataOfferRepo->findAll();
        $alldataoffer = [];
        foreach($DataOffers as $DataOffer){
            $data = $this->getSingleDataOffer($DataOffer->getId());
            array_push($alldataoffer,$data);
        }
        return $alldataoffer;
    }
    public function typeDataOffer($type,$id)
    {
        $entityManager = $this->EM;
        $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
        $DataOffer = $DataOfferRepo->findOneBy(["id"=>$id]);
        if(!$DataOffer){
            return'invalid id';
        }
        if($type=="enable"){
            $DataOffer->setIsActive(true);
        }
        if($type=="disable"){
            $DataOffer->setIsActive(false);
        }
        $entityManager->persist($DataOffer);
        $entityManager->flush(); 
        return $DataOffer;

    }

    public function clientOfferUpdate($request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content, StepV1::class, 'json');
        $entityManager = $this->EM;
        $DataClientRepo = $this->EM->getRepository(DataClient::class);
        $DataClient = $DataClientRepo->findOneBy(["id"=>$data->getClientId()]);
        if($data->getOfferId()==null){
            $DataclientOfferRepo = $this->EM->getRepository(DataClientOffer::class);
            $dataclientoffer = $DataclientOfferRepo->findOneBy(["client"=>$data->getClientId()]);
            if($dataclientoffer){ 
                $entityManager->remove($dataclientoffer);
                $entityManager->flush();
                return ["ok","Remove Successfully"];
            } 
        }
        $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
        $DataOffer = $DataOfferRepo->findOneBy(["id"=>$data->getOfferId()]);
        if(!$DataClient){
            return ["ko","Invalid Client_id"];
        }
        if(!$DataOffer){
            return ["ko","Invalid Offer_id"];
        }
        $DataclientOfferRepo = $this->EM->getRepository(DataClientOffer::class);
        $dataclientoffer = $DataclientOfferRepo->findOneBy(["client"=>$data->getClientId()]);
        if(!$dataclientoffer){
            $dataclientoffer = new DataClientOffer;
        }
        $dataclientoffer->setOffer($DataOffer);
        $dataclientoffer->setClient($DataClient);
        $entityManager->persist($dataclientoffer);
        $entityManager->flush();
        return ["ok","updated successfully"];

    }

    public function offervalidation($request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content, DataProductOffer::class, 'json');
        $productid = $data->getProductId();
        $offercode = $data->getOfferCode();
        $DataOfferRepo = $this->EM->getRepository(DataOffer::class);
        $dataoffer = $DataOfferRepo->findOneBy(["offerCode"=>$offercode]);
        if(!$dataoffer){
            return ["ko","Code invalide."];
        }
        if($dataoffer->getIsActive()==false){
            return ["ko","Le code renseigné n'est plus valable."];
        }
        $DataProductOfferRepo = $this->EM->getRepository(DataProductOffer::class);
        // return[$productid,$offerid];
        $dataprodoffer = $DataProductOfferRepo->findOneBy(["offer"=>$dataoffer,"product"=>$productid]);

        // $DataClientOfferRepo = $this->EM->getRepository(DataClientOffer::class);
        // // return[$productid,$offerid];
        // $dataclientoffer = $DataClientOfferRepo->findBy(["offer"=>$dataoffer]);
        // if($dataclientoffer){
        //     if($dataoffer->getMaxQuantity()){
        //         $offercount = count($dataclientoffer)+1;
        //         // return["ok",[$dataoffer->getMaxQuantity(),$offercount]];
        //         if($dataoffer->getMaxQuantity()<$offercount){
        //             return ["ko","Exceed the limit of offer user"];
        //         }
        //     }
        // }
        if($dataprodoffer){
            if ($dataprodoffer->getOffer()->getFromDate() && $dataprodoffer->getOffer()->getToDate() ) {
                $startDate = $dataprodoffer->getOffer()->getFromDate();
                $FromDate = $startDate->format('Y-m-d');
                $endDate = $dataprodoffer->getOffer()->getToDate();
                $ToDate = $endDate->format('Y-m-d');
                $currentDate = date("Y-m-d");
                if($currentDate>=$FromDate&&$currentDate<=$ToDate){
                    return ["ok",$dataprodoffer->getOffer()];
                }
                else{
                    return ["ko",$dataprodoffer->getOffer()->getMsgExpiration()];
                }
            }
        
        }
        else{
            return ["ko","Le code renseigné ne s’applique pas à l’offre sélectionnée."];
        }

    }

    public function offerDelete($client_id)
    {
        $DataClientOfferRepo = $this->EM->getRepository(DataClientOffer::class);
        // return[$productid,$offerid];
        $dataprodoffer = $DataClientOfferRepo->findOneBy(["client"=>$client_id]);
        if($dataprodoffer){
            $this->EM->remove($dataprodoffer);
            $this->EM->flush(); 
            return "ok";
        }
        else{
            return "invalid client_id";
        }

    }
// public function getAllOffers(string $langvalue){

// $langRepo      =  $this ->em ->getRepository(RefLanguage::class);
// $lang          =  $langRepo -> findOneBy(['code_language'=>$langvalue]);
//   if(!$lang){
//       if($langvalue != "all"){
//         return ["msg"  =>  "lang not found"];
//       }
//      }
 
// $labelRepo     =   $this ->em -> getRepository(RefLabel::class);        
// $offerem       =   $this ->em -> getRepository(RefOffer::class);
   
// $offers        =   $offerem->findBy(['active_offer'=>'Active']);
// $languages     =   $langRepo->findAll();
// if($langvalue  ==  "all"){
//   foreach($offers as  $offerkey => $offer){
//             $refLabels=array();
//             foreach($languages as $language){
//                 $label  =   $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $offer -> getCodeOffer()]);
//                 if (!$label){
//                     $refLabels[$language -> getCodeLanguage()]="";
//                     continue;
//                     }
//                  $refLabels[$language  -> getCodeLanguage()]  =  $label -> getLabelLabel();
//                }
//                $offers[$offerkey] -> setRefLabels($refLabels);
//             }
            
        
//         return $offers;
//     }

// foreach($offers as $key => $offer)
// {
//      $label    = $labelRepo -> findOneBy(['lang_label' => $lang ->getId(),'code_label' => $offer -> getCodeOffer()]);
//      if(!$label){
//           return ["msg"=>"label not found"];
//      }
//      $offers[$key] -> setRefLabel($label -> getLabelLabel());
// }
// return $offers;

// }


// public function getSingleOffer(int $id,string $langvalue): ?RefOffer{
//     $langRepo   = $this->EM -> getRepository(RefLanguage::class);
//     $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
//       if(!$lang){
//           if($langvalue   != "all"){
//             return ["msg" => "lang not found"];
//           }
//          }
//     $labelRepo        =  $this  ->em ->  getRepository(RefLabel::class);        
//     $OfferRepo        =  $this  ->em ->  getRepository(RefOffer::class);
//     $paticularoffer   =  $OfferRepo->  findOneBy(['id'=>$id]);
//     $languages        =  $langRepo->findAll();
//     if($langvalue == "all"){
//                 $refLabels  =  array();
//                 foreach($languages as $language){
//                     $label  =  $labelRepo->findOneBy(["lang_label" => $language,"code_label" => $paticularoffer->getCodeOffer()]);
//                     if (!$label){
//                         $refLabels[$language -> getCodeLanguage()]="";
//                         continue;
//                         }
//                      $refLabels[$language -> getCodeLanguage()]=$label -> getLabelLabel();
//                    }
//                    $paticularoffer -> setRefLabels($refLabels); 
//             return $paticularoffer;
//         }
  
//          $label      =   $labelRepo  ->  findOneBy(['lang_label' => $lang -> getId(),'code_label' => $paticularoffer -> getCodeOffer()]);
//          if(!$label){
//               return ["msg"=>"label not found"];
//          }
//          $paticularoffer -> setRefLabel($label -> getLabelLabel());
//     return $paticularoffer;
//   }
}