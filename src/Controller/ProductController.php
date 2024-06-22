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
use App\Service\FileUploader;


use App\Utils\ApiResponse;

use App\UtilsV3\Product;
use App\UtilsV3\ProductStatus;
use App\UtilsV3\Description;
use App\UtilsV3\Master\Status;

use App\Entity\RefProductContent;
use App\Entity\RefProduct;
use App\Entity\RefTable;
use App\Entity\RefLabel;
use App\Entity\RefLanguage;
use App\Entity\RefCdpricecalc;
use App\Entity\RefTypeclient;


use App\Repository\RefProductRepository;
use App\Repository\RefProductContentRepository;
use App\Repository\RefCdpricecalcRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RefLanguageRepository;
use App\Repository\RefLabelRepository;
use App\Repository\RefTypeclientRepository;

class ProductController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    /**
     * @Route("/api/v3/product", name="productadd",methods={"POST"})
     */
    public function addProduct_(Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $product = $serializer->deserialize($content, Product::class, 'json');
        $entityManager = $this->EM;
        $refTabelRepo=$this->EM->getRepository(RefTable::class);
        $refLabelRepo=$this->EM->getRepository(RefLabel::class);
        $reftypeclientRepo=$this->EM->getRepository(RefTypeClient::class);
        $refLangLabelRepo=$this->EM->getRepository(RefLanguage::class);
        $refReqProductRepo=$this->EM->getRepository(RefProduct::class);
        $refReqProductDescRepo=$this->EM->getRepository(RefProductContent::class);
        $chktypeclient=$reftypeclientRepo->findOneBy(['id'=>$product->getTypeclient()]);
        if(!$chktypeclient){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid typeclient');

        }
        $chkproritys=$refReqProductRepo->findBy(['typeclient'=>$product->getTypeclient(),'status'=>['Active']],array('priority'=>'ASC'));
        foreach($chkproritys as $priority){
           if($priority->getPriority()==$product->getPriority()){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','priority already exist');

           }
        }
        if($product->getCodeProduct()==""){
            $product->setCodeProduct($refTabelRepo->next('ref_product'));           
        }
        $secproduct=new RefProduct;
            
         foreach( $product->getRefLabels() as $key => $refLabel){
            $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$secproduct->getCodeProduct(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
        }
        $refLabelTemp = new RefLabel();
        $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($product->getCodeProduct());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secproduct->setName($product->getName());
        $secproduct->setCodeProduct($product->getCodeProduct());
        $secproduct->setImageurl($product->getImageurl());
        $secproduct->setPrice($product->getPrice());
        $secproduct->setStatus($product->getStatus());
        $secproduct->setBusiness($product->getBusiness());
        $secproduct->setVisa($product->getVisa());
        $secproduct->setBuisDi($product->getBuisDi());
        $secproduct->setBuisDd($product->getBuisDd());
        $secproduct->setBuisSupdi($product->getBuisSupdi());
        $secproduct->setBuisSupdd($product->getBuisSupdd());
        $secproduct->setVidaDi($product->getVidaDi());
        $secproduct->setVisaDd($product->getVisaDd());
        $secproduct->setVisaSupdi($product->getVisaSupdi());
        $secproduct->setVisaSupdd($product->getVisaSupdd());
        $secproduct->setTypeclient($chktypeclient);
        $secproduct->setCheque($product->getCheque());
        $secproduct->setTpe($product->getTpe());
        $secproduct->setCash($product->getCash());
        $secproduct->setCardLimit($product->getCardLimit());
        $secproduct->setPriority($product->getPriority());
        $secproduct->setSabcategory($product->getSabcategory());
        $secproduct->setColor($product->getColor());
        $secproduct->setHeadId($product->getHeadId());
        $secproduct->setLabelColor($product->getLabelColor());
        $entityManager->persist($secproduct);
        $entityManager->flush();  
        
        foreach($product->getDescription() as $nonproduct )
      
              {
               $productcont = $serializer->deserialize( json_encode($nonproduct,JSON_NUMERIC_CHECK ), Description::class, 'json');
               $utilproduct = new Description();
               if($productcont->getCodeProductcontent()=="")
               {
                  $productcont->setCodeProductcontent($refTabelRepo->nextv2('ref_productcontent'));           
                }
        
                  $secproductcontent=new RefProductContent();
            
              foreach( $productcont->getRefLabels() as $key => $refLabel){
                $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
                $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$secproductcontent->getCodeProductcontent(),'lang_label'=>$lang->getId()]);
                if ($chkRefLabel){
                    $chkRefLabel->setLabelLabel($refLabel);
                    continue;
                 }
            
            $refLabelTemp = new RefLabel();
            $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $refLabelTemp->setLangLabel($lang);
            $refLabelTemp->setLabelLabel($refLabel);
            $refLabelTemp->setCodeLabel($productcont->getCodeProductcontent());
            $refLabelTemp->setActiveLabel("Active");
            $entityManager->persist($refLabelTemp);


            }
            $secproductcontent->setProduct($secproduct);
            $secproductcontent->setCodeProductcontent($productcont->getCodeProductcontent());
            $secproductcontent->setDescProduct($productcont->getDescProduct());
            $entityManager->persist($secproductcontent);
            $entityManager->flush();  
            
              }
              return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','Add success');
    
    }

    // /**
    //  * @Route("/api/v3/filter", name="filter",methods={"POST"})
    //  */
    // public function getaction(Request $req,RefProductRepository $productrepo)
    // {
    //    $encoders = [ new JsonEncoder()];
    //     $normalizers = [new ObjectNormalizer()];
    //     $serializer = new Serializer($normalizers ,$encoders);
    //     $content = $req->getContent();
    //     $data = $serializer->deserialize($content, Product::class, 'json');
    //     $entityManager = $this->EM;
    //     $arraystatus=$data->getArrayStatus();
    //     $response=$productrepo->findBy(array('status' => $arraystatus));
    //     if($response){
    //           return new ApiResponse([$response],200,["Content-Type"=>"application/json"],'json','success');
    //     }
    //     return new ApiResponse([$response],404,["Content-Type"=>"application/json"],'json','invalid');
       
    // }

    /**
     * @Route("/api/product/disable/{id}", name="disable",methods={"PUT"})
     */

     public function statusDisable(Request $req,RefProductRepository $productrepo,$id){

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $chkid=$productrepo->findOneBy(['id'=>$id]);
        if($chkid=="")
        {
           return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','invalid');    
        }
           $chkid->setStatus("Disabled");
           $entityManager->persist($chkid);
           $entityManager->flush();
           return new ApiResponse([$chkid],200,["Content-Type"=>"application/json"],'json','status changed!!');
       
    }

    /**
     * @Route("/api/product/enable/{id}", name="enable",methods={"PUT"})
     */

    public function statusEnable(Request $req,RefProductRepository $productrepo,$id){

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $chkid = $productrepo->findOneBy(['id'=>$id]);
        $chkprority = $productrepo->findOneBy(['typeclient'=>$chkid->getTypeclient()->getId(),'status'=>'Active','priority'=>$chkid->getPriority()]);

        if(!$chkid)
        {
           return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','invalid');    
        }
        if($chkprority){
           return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','priority already exist');    
        }
           $chkid->setStatus("Active");
           $entityManager->persist($chkid);
           $entityManager->flush();
           return new ApiResponse([$chkid],200,["Content-Type"=>"application/json"],'json','status changed!!');
       
    }


    /**
     * @Route("/api/product/delete/{id}", name="delete",methods={"PUT"})
     */

    public function statusDelete(Request $req,RefProductRepository $productrepo,$id){

        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $entityManager = $this->EM;
        $chkid=$productrepo->findOneBy(['id'=>$id]);
        if($chkid=="")
        {
           return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','invalid');    
        }
           $chkid->setStatus("Deleted");
           $entityManager->persist($chkid);
           $entityManager->flush();
           return new ApiResponse([$chkid],200,["Content-Type"=>"application/json"],'json','status changed!!');
       
    }

    // /**
    //  * @Route("/api/v3/product/edit/{id}", name="editaction",methods={"PUT"})
    //  */
    // public function editaction(Request $req,RefProductRepository $productrepo,$id)
    // {
    //    $encoders = [ new JsonEncoder()];
    //     $normalizers = [new ObjectNormalizer()];
    //     $serializer = new Serializer($normalizers ,$encoders);
    //     $content = $req->getContent();
    //     $data = $serializer->deserialize($content, Product::class, 'json');
    //     $entityManager = $this->EM;
    //     $chkid=$productrepo->findOneBy(['id'=>$id]);
    //     if($chkid=="")
    //     {
    //        return new ApiResponse([$response],404,["Content-Type"=>"application/json"],'json','invalid');    
    //     }
          
    //        $chkid->setName($data->getName());
    //        $chkid->setImageurl($data->getImageurl());
    //        $chkid->setPrice($data->getPrice());
    //        $chkid->setStatus($data->getStatus());

    //        $entityManager->persist($chkid);
    //        $entityManager->flush();
    //        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','successfully updated!!');
       
    // }

//      /**
//      * @Route("/api/v3/productv1/{langvalue}/{id}", name="getproductv1",methods={"GET"})
//      */
//     public function getproductv($langvalue,$id,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request){
//         $encoders = [new JsonEncoder()];
//         $normalizer = new ObjectNormalizer();
//         $normalizer->setCircularReferenceLimit(2);
//         $normalizer->setCircularReferenceHandler(function ($object) {
//         return $object->getId();
//         });
//         $normalizers = array($normalizer);
//         $serializer = new Serializer($normalizers ,$encoders);
//         $content = $request->getContent();
//         $res = array();
//         $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
//         $desclang=$langRepo->findOneBy(['code_language'=>$langvalue]);
//          if(!$lang){
//          if($langvalue!="all")
//             {
//                return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','label not found');
               
//              }
//             }
                
//        $paticularproduct=$productrepo->findOneBy(['id'=>$id]);
//        $productcontent=$productcontentrepo->findBy(['product'=>$id]);
//        foreach($productcontent as $product){
//            $code_arr[]=$product->getCodeProductcontent();
//        }
//     //    return new ApiResponse([$productcontent],200,["Content-Type"=>"application/json"],'json','success');
//        $array_code=array();
//     //    return new ApiResponse($code_arr,200,["Content-Type"=>"application/json"],'json','success');
       
//        $array_code=$labelRepo->findBy(array('code_label'=>$code_arr));
//     //    return new ApiResponse([$code_arr],200,["Content-Type"=>"application/json"],'json','success');
//        foreach($array_code as $code){
//         $code_arr[]=$code->getCodeLabel();
//     }
//        $languages=$langRepo->findAll();
//        if($langvalue=="all"){
//                    $refLabels=array();
//                    $productreflable=array();
//                    foreach($languages as $language){
//                        $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularproduct->getCodeProduct()]);
//                        $productlable=$labelRepo->findBy(array("lang_label"=>$language,'code_label' => $code_arr));
//                        foreach($productlable as $lable){
//                         $lable_arr[]=$lable->getLabelLabel();
//                     }
                    
//                        if ((!$label)&&(!$productlable)){
//                            $refLabels[$language->getCodeLanguage()]="";
//                            $productreflable[$language->getCodeLanguage()]="";
//                            continue;
//                            }
                           
//                         $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                       
//                         $productreflable[$language->getCodeLanguage()]=$lable_arr;
        
                        
//                       }
                      
           
//                       $paticularproduct-> setRefLabels($refLabels);
//                       foreach($productreflable as $pro)
//                       foreach($productcontent as $procon){
//                       $procon->setRefLabels($pro);
//                       }
                    
                   
               
//                       return new ApiResponse($productcontent,200,["Content-Type"=>"application/json"],'json','success');
//            }
//            $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularproduct->getCodeProduct()]);
//            $productlable=$labelRepo->findBy(array('lang_label'=>$desclang->getId(),'code_label' => $code_arr));
//            foreach($productlable as $prolab){
//                $arraylab[]=$prolab->getLabelLabel();
//            }
//         //    return new ApiResponse([$arraylab],200,["Content-Type"=>"application/json"],'json','success');
//            if((!$label)&&(!$productlable)){
              
//                return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','label not found');
//            }
//            $paticularproduct->setRefLabel($label->getLabelLabel());
//            foreach($productcontent as $pro)
//            foreach($arraylab as $lab)
//                       {
//                       $pro-> setRefLabel($lab);
//                       }
//            return new ApiResponse($productcontent,200,["Content-Type"=>"application/json"],'json','success');
//    }
    /**
     * @Route("/api/v3/product/{langvalue}/{domain}", name="getproduct",methods={"GET"})
     */
    public function getproduct_($langvalue,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request,RefTypeclientRepository $typeclientrepo,$domain='front'){
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $res = array();
        if($domain =='front'){
            $product=$productrepo->findBy(array('status'=>['Active']),array('priority'=>'ASC'));
        }
        if($domain == 'back'){
            $product=$productrepo->findBy(array('status'=>['Active','Disabled']),array('priority'=>'ASC'));
        }
        // return new ApiResponse($product,200,["Content-Type"=>"application/json"],'json','success');

        // $listofproduct=[];
        $ids=[];
       
        foreach($product as $pro){
            array_push($ids,$pro->getId());           
        }
        $listproducts=[];
        foreach($ids as $id){
            array_push($listproducts,$this->getsingleproduct($langvalue,$id,$langRepo,$labelRepo,$productrepo,$productcontentrepo,$request,$typeclientrepo));
        }
        return new ApiResponse($listproducts,200,["Content-Type"=>"application/json"],'json','success');
    }
    // /**
    //  * @Route("/api/product/filter/{langvalue}", name="productFilter",methods={"POST"})
    //  */
    // public function productFilter($langvalue,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request,RefTypeclientRepository $typeclientrepo)
    // {
    //     $encoders      =  [ new JsonEncoder()];
    //     $normalizers   =  [new ObjectNormalizer()];
    //     $serializer    =  new Serializer($normalizers ,$encoders);
    //     $content       =  $request->getContent();
    //     $entityManager =  $this->EM;
    //     $status        =  $serializer->deserialize($content, Status::class, 'json');
    //     $arrstatus     =  array();
    //     $arrstatus     =  $status->getStatus();
    //     $product       =  $productrepo->findBy(array('status' => $arrstatus),array('priority'=>'ASC'));
    //     $ids=[];
       
    //     foreach($product as $pro){
    //         array_push($ids,$pro->getId());           
    //     }
      
    //     $listproducts=[];
    //     foreach($ids as $id){
    //         array_push($listproducts,$this->getsingleproduct($langvalue,$id,$langRepo,$labelRepo,$productrepo,$productcontentrepo,$request,$typeclientrepo));
    //     }
       
    //     return new ApiResponse($listproducts,200,["Content-Type"=>"application/json"],'json','success'); 
    // } 

    /**
     * @Route("/api/v3/getsingleproduct/{id}/{langvalue}", name="getSingleProductv",methods={"GET"})
     */
    public function getSingleProductv_($langvalue,$id,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request,RefTypeclientRepository $typeclientrepo){
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $res = array();
        $product=$productrepo->findOneBy(['id'=>$id]);
        if(!$product){
          return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invlaid product_id');
        }
        $listproducts=$this->getsingleproduct($langvalue,$id,$langRepo,$labelRepo,$productrepo,$productcontentrepo,$request,$typeclientrepo);
        return new ApiResponse($listproducts,200,["Content-Type"=>"application/json"],'json','success');
    }

    public function getsingleproduct(string $langvalue,int $id,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request,$typeclientrepo){
        $paticularproduct=$productrepo->findOneBy(['id'=>$id]);
        $typeclient=null;
        if($paticularproduct){
            $typeclient_id=$paticularproduct->getTypeclient()->getId();
            $typeclient=$this->getSingleTypeclient($typeclient_id,$langvalue);
        }
       
        // $typeclient=$typeclientrepo->findOneBy(['id'=>$paticularproduct->getTypeclient()->getId()]);
       $tempProduct=new Product();
       $tempProduct->setId($paticularproduct->getId());
       $tempProduct->setName($paticularproduct->getName());
       $tempProduct->setRefTypeclient($typeclient);
       $tempProduct->setImageurl($paticularproduct->getImageurl());
       $tempProduct->setPrice($paticularproduct->getPrice());
       $tempProduct->setCodeProduct($paticularproduct->getCodeProduct());
       $tempProduct->setStatus($paticularproduct->getStatus());
       $tempProduct->setBusiness($paticularproduct->getBusiness());
       $tempProduct->setVisa($paticularproduct->getVisa());
       $tempProduct->setBuisDi($paticularproduct->getBuisDi());
       $tempProduct->setBuisDd($paticularproduct->getBuisDd());
       $tempProduct->setBuisSupdi($paticularproduct->getBuisSupdi());
       $tempProduct->setBuisSupdd($paticularproduct->getBuisSupdd());
       $tempProduct->setVidaDi($paticularproduct->getVidaDi());
       $tempProduct->setVisaDd($paticularproduct->getVisaDd());
       $tempProduct->setVisaSupdi($paticularproduct->getVisaSupdi());
       $tempProduct->setVisaSupdd($paticularproduct->getVisaSupdd());
       $tempProduct->setCheque($paticularproduct->getCheque());
       $tempProduct->setTpe($paticularproduct->getTpe());
       $tempProduct->setCardLimit($paticularproduct->getCardLimit());
       $tempProduct->setCash($paticularproduct->getCash());
       $tempProduct->setPriority($paticularproduct->getPriority());
       $tempProduct->setSabcategory($paticularproduct->getSabcategory());
       $tempProduct->setColor($paticularproduct->getColor());
       $tempProduct->setHeadId($paticularproduct->getHeadId());
       if($paticularproduct->getHeadId()){
            $product=$productrepo->findOneBy(['id'=>$paticularproduct->getHeadId()]);
            $tempProduct->setHeadName($product->getName());
            $tempProduct->setHeadProduct($product);
       }
       $tempProduct->setLabelColor($paticularproduct->getLabelColor());
       //set entity to util
        // return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');
       $lang= $langRepo->findOneBy(['code_language'=>$langvalue]);
       if(!$lang){
       if($langvalue!="all")
          {
             return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','label not found 1');
             
           }
          }
       $languages=$langRepo->findAll();
       if($langvalue=="all"){
                 $refLabels=array();
              foreach($languages as $language){
                  $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$paticularproduct->getCodeProduct()]);
                  if (!$label){
                      $refLabels[$language->getCodeLanguage()]="";
                      continue;
                      }
                   $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                 }
                 $tempProduct-> setRefLabels($refLabels);
             
              
          
                //   return new ApiResponse([$tempProduct],200,["Content-Type"=>"application/json"],'json','success');
      }else{
      $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$paticularproduct->getCodeProduct()]);
      if(!$label){
         
          return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','label not found 2');
      }
      $tempProduct->setRefLabel($label->getLabelLabel());
    //    return new ApiResponse([$tempProduct],200,["Content-Type"=>"application/json"],'json','success');
      }
       $productdesc=$productcontentrepo->findBy(['product'=>$id]);
       //
      $descriptions=[];
       foreach($productdesc as $pdtdesc){
           $singleDescription=new Description();
           $singleDescription->setId($pdtdesc->getId());
           $singleDescription->setCodeProductcontent($pdtdesc->getCodeProductcontent());
           $singleDescription->setProduct($id);
           $singleDescription->setDescProduct($pdtdesc->getDescProduct());
           if($langvalue=="all"){
            $refLabels=array();
            foreach($languages as $language){
                $label= $labelRepo->findOneBy(["lang_label"=>$language,"code_label"=>$pdtdesc->getCodeProductcontent()]);
                // return new ApiResponse(['lang_label'=>'fr','code_label'=>$pdtdesc->getCodeProductcontent()],404,["Content-Type"=>"application/json"],'json','label not found 3');

                if (!$label){
                    $refLabels[$language->getCodeLanguage()]="";
                    continue;
                    }
                 $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
               }
               $singleDescription-> setRefLabels($refLabels);
           
            
        
            //    return new ApiResponse([$singleDescription],200,["Content-Type"=>"application/json"],'json','success');
       }else{
       $label= $labelRepo->findOneBy(['lang_label'=>$lang->getId(),'code_label'=>$pdtdesc->getCodeProductcontent()]);
       if(!$label){
          
           return new ApiResponse(['lang_label'=>$lang->getId(),'code_label'=>$pdtdesc->getCodeProductcontent(),'id'=>$pdtdesc->getId()],404,["Content-Type"=>"application/json"],'json','label not found 3');
       }

    $singleDescription->setRefLabel($label->getLabelLabel());
}
           array_push($descriptions,$singleDescription);
    
        // $code_arr[]=$pdtdesc->getCodeProductcontent();
    }
    //setDescription
    $tempProduct->setDescription($descriptions);
    return $tempProduct;


//    return new ApiResponse([$tempProduct],200,["Content-Type"=>"application/json"],'json','success');
    }

    public function  getSingleTypeclient(int $id,string $langvalue): ?RefTypeclient{
        $langRepo   = $this->EM->getRepository(RefLanguage::class);
        $lang       = $langRepo -> findOneBy(['code_language'=>$langvalue]);
          if(!$lang){
              if($langvalue   != "all"){
                return ["msg" => "lang not found"];
              }
             }
        $labelRepo     =  $this->EM->getRepository(RefLabel::class);        
        $TypeclientRepo      =  $this->EM->getRepository(RefTypeclient::class);
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


    /**
     * @Route("/api/v3/filter/{langvalue}", name="filterProduct",methods={"POST"})
     */
    public function getaction($langvalue,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request)
    {
       $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content, ProductStatus::class, 'json');
        $entityManager = $this->EM;
        $arraystatus=$data->getArrayStatus();
        $product=$productrepo->findBy(array('status' => $arraystatus));
        $ids=[];
       
        foreach($product as $pro){
            array_push($ids,$pro->getId());           
        }
      
        $listproducts=[];
        foreach($ids as $id){
            array_push($listproducts,$this->getsingleproduct($langvalue,$id,$langRepo,$labelRepo,$productrepo,$productcontentrepo,$request));
        }
       
        return new ApiResponse($listproducts,200,["Content-Type"=>"application/json"],'json','success');
    }

    
    /**
     * @Route("/api/v3/cardpricecalculation/{priority}/{cardtype}/{debittype}", name="getprice",methods={"GET"})
     */
    public function getprice_($priority,$cardtype,$debittype,RefCdpricecalcRepository $pricerepo,Request $request)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $price=$pricerepo->findOneBy(array('priority' => $priority,'cardtype' => $cardtype,'debittype' => $debittype));
        if(!$price){
            return new ApiResponse($price,404,["Content-Type"=>"application/json"],'json','type is wrong');
        }
        return new ApiResponse($price,200,["Content-Type"=>"application/json"],'json','success');
    }

    // /**
    //  * @Route("/api/product/getproductbytypeclient/{tyeclient_id}", name="getProductByTypeclient",methods={"GET"})
    //  */
    // public function getProductByTypeclient(Request $request,RefProductRepository $productrepo,$tyeclient_id,RefTypeclientRepository $typeclientrepo)
    // {
    //     $encoders = [new JsonEncoder()];
    //     $normalizer = new ObjectNormalizer();
    //     
    //     $entityManager = $this->EM;
    //     $normalizer->setCircularReferenceHandler(function ($object) {
    //     return $object->getId();
    //     });
    //     $normalizers = array($normalizer);
    //     $serializer = new Serializer($normalizers ,$encoders);
    //     $chk=$typeclientrepo->findOneBy(['id'=>$tyeclient_id]);
    //     if(!$chk){
    //         return new ApiResponse($chk,404,["Content-Type"=>"application/json"],'json','invalid typeclient_id');
    //     }
    //     $data=$productrepo->findBy(array('typeclient'=>$chk->getId()),array('priority'=>'ASC'));
    //     if(!$data){
    //         return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','data not exist');
    //     }
    //     return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');
    // }


     /**
     * @Route("/api/v3/getproductbytypeclient/{typeclient_id}/{langvalue}", name="getProductTypeclient",methods={"GET"})
     */
    public function getProductTypeclient($langvalue,RefLanguageRepository $langRepo,RefLabelRepository $labelRepo,RefProductRepository $productrepo,RefProductContentRepository $productcontentrepo,Request $request,$typeclient_id,RefTypeclientRepository $typeclientrepo){
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $res = array();
        $product=$productrepo->findBy(['typeclient'=>$typeclient_id,'status'=>['Active']],array('priority'=>'ASC'));
        // $listofproduct=[];
        $ids=[];
       
        foreach($product as $pro){
            array_push($ids,$pro->getId());           
        }
      
        $listproducts=[];
        foreach($ids as $id){
            array_push($listproducts,$this->getsingleproduct($langvalue,$id,$langRepo,$labelRepo,$productrepo,$productcontentrepo,$request,$typeclientrepo));
        }
       
        return new ApiResponse($listproducts,200,["Content-Type"=>"application/json"],'json','success-');  

    }


     /**
     * @Route("/api/v3/product/update", name="updateProduct",methods={"PUT"})
     */
    public function updateProduct_(Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $product = $serializer->deserialize($content, Product::class, 'json');
        $entityManager = $this->EM;
        $refTabelRepo=$this->EM->getRepository(RefTable::class);
        $refLabelRepo=$this->EM->getRepository(RefLabel::class);
        $reftypeclientRepo=$this->EM->getRepository(RefTypeClient::class);
        $refLangLabelRepo=$this->EM->getRepository(RefLanguage::class);
        $refReqProductRepo=$this->EM->getRepository(RefProduct::class);
        $refReqProductDescRepo=$this->EM->getRepository(RefProductContent::class);
        $chktypeclient=$reftypeclientRepo->findOneBy(['id'=>$product->getTypeclient()]);
        if(!$chktypeclient){
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid typeclient');

        }
      
        if($product->getId()){
            $secproduct=$refReqProductRepo->findOneBy(['id'=>$product->getId()]); 
            if(!$secproduct)   {
                return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','invalid product_id');

            }  
        }
        if($secproduct->getPriority()!=$product->getPriority()){
            $chkproritys=$refReqProductRepo->findBy(['typeclient'=>$product->getTypeclient(),'status'=>['Active','Disabled']],array('priority'=>'ASC'));
        foreach($chkproritys as $priority){
           if($priority->getPriority()==$product->getPriority()){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','priority already exist');

           }
        }
        }
        
            
         foreach( $product->getRefLabels() as $key => $refLabel){
            $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
            $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$secproduct->getCodeProduct(),'lang_label'=>$lang->getId()]);
            if ($chkRefLabel){
                $chkRefLabel->setLabelLabel($refLabel);
                continue;
        }
        $refLabelTemp = new RefLabel();
        $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
        $refLabelTemp->setLangLabel($lang);
        $refLabelTemp->setLabelLabel($refLabel);
        $refLabelTemp->setCodeLabel($secproduct->getCodeProduct());
        $refLabelTemp->setActiveLabel("Active");
        $entityManager->persist($refLabelTemp);
        }
        $secproduct->setName($product->getName());
        $secproduct->setCodeProduct($secproduct->getCodeProduct());
        $secproduct->setImageurl($product->getImageurl());
        $secproduct->setPrice($product->getPrice());
        $secproduct->setStatus($product->getStatus());
        $secproduct->setBusiness($product->getBusiness());
        $secproduct->setVisa($product->getVisa());
        $secproduct->setBuisDi($product->getBuisDi());
        $secproduct->setBuisDd($product->getBuisDd());
        $secproduct->setBuisSupdi($product->getBuisSupdi());
        $secproduct->setBuisSupdd($product->getBuisSupdd());
        $secproduct->setVidaDi($product->getVidaDi());
        $secproduct->setVisaDd($product->getVisaDd());
        $secproduct->setVisaSupdi($product->getVisaSupdi());
        $secproduct->setVisaSupdd($product->getVisaSupdd());
        $secproduct->setTypeclient($chktypeclient);
        $secproduct->setCheque($product->getCheque());
        $secproduct->setTpe($product->getTpe());
        $secproduct->setCardLimit($product->getCardLimit());
        $secproduct->setCash($product->getCash());
        $secproduct->setPriority($product->getPriority());
        // $secproduct->setColor($product->getColor());
        // $secproduct->setHeadId($product->getHeadId());
        // $secproduct->setLabelColor($product->getLabelColor());
        $entityManager->persist($secproduct);
        $entityManager->flush();  
        
        foreach($product->getDescription() as $nonproduct )
      
              {
               $productcont = $serializer->deserialize( json_encode($nonproduct,JSON_NUMERIC_CHECK ), Description::class, 'json');
               $utilproduct = new Description();
               if($productcont->getCodeProductcontent()=="")
               {
                  $productcont->setCodeProductcontent($refTabelRepo->nextv2('ref_productcontent'));           
                }
    
                if($productcont->getId()){
                    $secproductcontent=$refReqProductDescRepo->findOneBy(['id'=>$productcont->getId()]);
                    if(!$secproductcontent){
                        return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid productContent_id');

                    }
                }else{
                    $secproductcontent=new RefProductContent();
                    $secproductcontent->setCodeProductcontent($refTabelRepo->nextv2('ref_productcontent')); 
                }
                  
              foreach( $productcont->getRefLabels() as $key => $refLabel){
                $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
                $chkRefLabel=$refLabelRepo->findOneBy(['code_label'=>$secproductcontent->getCodeProductcontent(),'lang_label'=>$lang->getId()]);
                if ($chkRefLabel){
                    $chkRefLabel->setLabelLabel($refLabel);
                    $entityManager->persist($chkRefLabel);
                    // $entityManager->flush();  
                    continue;
                 }
            
                $refLabelTemp = new RefLabel();
                $lang=$refLangLabelRepo->findOneBy(['code_language'=>$key]);
                $refLabelTemp->setLangLabel($lang);
                $refLabelTemp->setLabelLabel($refLabel);
                $refLabelTemp->setCodeLabel($secproductcontent->getCodeProductcontent());
                $refLabelTemp->setActiveLabel("Active");
                $entityManager->persist($refLabelTemp);
            

            }
            $secproductcontent->setProduct($secproduct);
            // $secproductcontent->setCodeProductcontent($productcont->getCodeProductcontent());
            $secproductcontent->setDescProduct($productcont->getDescProduct());
            $entityManager->persist($secproductcontent);
            $entityManager->flush();  
            
              }
              return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','updated success');
    
    }

    /**
     * @Route("/api/delete/productcontent/{id}", name="filter",methods={"PUT"})
     */
    public function delete_(RefProductContentRepository $productcontentrepo,Request $request,$id)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $res = array();
        $entityManager = $this->EM;
        $productcontent=$productcontentrepo->findOneBy(['id'=>$id]);
        $entityManager->remove($productcontent);
        $entityManager->flush(); 
      
       
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','removed');
    }
    // /**
    //  * @Route("/api/product/file/upload", name="uploadProductFile",methods={"POST"})
    //  */
    // public function uploadProductFile(Request $request,FileUploader $uploader){
    //     $file    =  $request->files->get('files');
    //     $name    =  $request->get('name');
    //     // return new ApiResponse($request->get('name'),200,["Content-Type"=>"application/json"],'json','Name ');    
        
    //     if(!$name){
    //         $name=$file->getClientOriginalName();
    //         $uploader -> upload('../../frontofficekeprevos/public/img', $file, $name);
    //         return new ApiResponse('img/'.$name,200,["Content-Type"=>"application/json"],'json','success');  

    //     //    return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Name is empty');    
    //     }
    //     if (empty($file)){
    //         return new Response("No file specified",  
    //         Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
    //     }        

    //     $originalfilename =  $file->getClientOriginalName();
    //     $str_arr      =  explode (".", $originalfilename); 
    //     $filename     = $name.".".$str_arr[1];
    //     // return new ApiResponse([$filename],200,["Content-Type"=>"application/json"],'json','success');  

    //     $uploader -> upload('../../frontofficekeprevos/public/img', $file, $filename);
        
    //     return new ApiResponse('img/'.$filename,200,["Content-Type"=>"application/json"],'json','success');  

    // }
   
}

