<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\ApiResponse;
use App\Service\OfferService;
use Doctrine\ORM\EntityManagerInterface;
class OfferController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/api/master/refoffertype", name="countryinsert",methods={"POST"})
     */
    public function addRefOffertype(Request $request,OfferService $offerservice)
    {
        $data = $offerservice->addRefOffertype($request);
        if($data=="invalid promotypeid"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid promotypeid');
        }
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);

    }
    /**
     * @Route("/api/master/getsingle/RefOffertype/{id}", name="getSingelRefOffertypev1",methods={"GET"})
     */
    public function getSingleRefOffertype($id,OfferService $offerservice)
    {
       
        $data = $offerservice->getSingleRefOffertype($id);
        if($data=="invalid promotypeid"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid promotypeid');
        }
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);
        
    }

      /**
     * @Route("/api/master/getall/RefOffertype", name="getallRefOffertype",methods={"GET"})
     */
    public function getallRefOffertype(OfferService $offerservice)
    {
        $data = $offerservice->getallRefOffertype();
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);
    }

    /**
     * @Route("/api/master/RefOffertype/{type}/{id}", name="typeRefOffertype",methods={"PUT"})
     */
    public function typeRefOffertype($type,$id,OfferService $offerservice)
    {
        $data = $offerservice->typeRefOffertype($type,$id);
        if($data=="invalid promotypeid"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid promotypeid');
        }
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);

    }

     /**
     * @Route("/api/master/delete/RefOffertype/{id}", name="getSingelRefOffertype",methods={"GET"})
     */
    public function getdeleteRefOffertype($id,OfferService $offerservice)
    {
       
        $data = $offerservice->getdeleteRefOffertype($id);
        if($data=="invalid promotypeid"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');
        }
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);
        
    }


      /**
     * @Route("/api/DataOffer", name="DataOffer",methods={"POST"})
     */
    public function addDataOffer(Request $request,OfferService $offerservice)
    {
        $data = $offerservice->addDataOffer($request);
        if($data[0]=="ko"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',$data[1]);
        }
        return new ApiResponse($data[1],200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);

    }
    /**
     * @Route("/api/getsingle/DataOffer/{id}", name="getSingelDataOfferv1",methods={"GET"})
     */
    public function getSingleDataOffer($id,OfferService $offerservice)
    {
       
        $data = $offerservice->getSingleDataOffer($id);
        if($data=="invalid id"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');
        }
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);
        
    }

      /**
     * @Route("/api/getall/DataOffer", name="getallDataOffer",methods={"GET"})
     */
    public function getallDataOffer(OfferService $offerservice)
    {
        $data = $offerservice->getallDataOffer();
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);
    }

    /**
     * @Route("/api/DataOffer/{type}/{id}", name="typeDataOffer",methods={"PUT"})
     */
    public function typeDataOffer($type,$id,OfferService $offerservice)
    {
        $data = $offerservice->typeDataOffer($type,$id);
        if($data=="invalid id"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');
        }
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);

    }
    /**
     * @Route("/api/client/offerupdate", name="clientOfferUpdate",methods={"PUT"})
     */
    public function clientOfferUpdate(Request $request ,OfferService $offerservice)
    {
        $data = $offerservice->clientOfferUpdate($request);
        if($data[0]=="ko"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',($data[1]));
        }
        return new ApiResponse( [],200,["Content-Type"=>"application/json"],'json',$data[1],["timezone","__initializer__","__cloner__","__isInitialized__","no"]);

    }
     /**
     * @Route("/api/delete/DataOffer/{id}", name="getSingelDataOffer",methods={"GET"})
     */
    public function getdeleteDataOffer($id,OfferService $offerservice)
    {
       
        $data = $offerservice->getdeleteDataOffer($id);
        if($data=="invalid promotypeid"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid promotypeid');
        }
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);
        
    }

     /**
     * @Route("/api/offer/validation", name="offervalidation",methods={"POST"})
     */
    public function offervalidation(Request $request,OfferService $offerservice)
    {
        $data = $offerservice->offervalidation($request);
        if($data[0]=="ko"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',$data[1]);
        }
        return new ApiResponse( $data[1],200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);

    }

     /**
     * @Route("/api/offer/delete/{client_id}", name="offerdelete",methods={"PUT"})
     */
    public function offerDelete($client_id,OfferService $offerservice)
    {
        $data = $offerservice->offerDelete($client_id);
        if($data=="invalid client_id"){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid client_id');
        }
        return new ApiResponse( $data,200,["Content-Type"=>"application/json"],'json','success',["timezone","__initializer__","__cloner__","__isInitialized__","no"]);

    }


     /**
     * @Route("/api/datapromo", name="datapromo",methods ={"POST"})
     */
    // public function datapromo(Request $request)
    // {
    //     $encoders = [ new JsonEncoder()];
    //     $normalizers = [new ObjectNormalizer()];
    //     $serializer = new Serializer($normalizers ,$encoders);
    //     $content = $request->getContent();
    //     $dataPromo = $serializer->deserialize($content, DataPromo::class, 'json');
    //     $entityManager = $this->EM;
    //     $RefOffertypeRepo = $this->EM->getRepository(RefOfferType::class);
    //     $promoid = $dataPromo->getPromoTypeId();
    //     if($promoid){
    //         $RefOffertype = $RefOffertypeRepo->findOneBy(["id"=>$promoid]);
    //         if(!$RefOffertype){
    //             return new ApiResponse( [],400,["Content-Type"=>"application/json"],'json','invalid promotypeid');
    //         }
    //     }
    //     else{
    //         $RefOffertype = new RefOfferType;
    //         if($promotype->getCode()==""){
    //             $RefOffertype->setCode($refTabelRepo->next('ref_promo_type'));           
    //         }
    //     }
       
    //     $RefOffertype->setValue($promotype->getValue());
    //     $RefOffertype->setIsActif(true);
    //     $entityManager->persist($RefOffertype);
    //     $entityManager->flush(); 
    //     return new ApiResponse( $RefOffertype,200,["Content-Type"=>"application/json"],'json','success');

    // }
   
}