<?php

namespace App\Controller;

use App\Entity\DataPromo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use App\Service\MasterServiceV2;
use App\Entity\RefCountry;
use App\Entity\RefBank;
use App\Entity\RefFile;
use App\Entity\RefCompanytype;
use App\Entity\RefTypeclient;
use App\Entity\RefRequeststatus;
use App\UtilsV3\Master\Status;
use App\Entity\RefTable;
use App\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
class MasterControllerV1Controller extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/master/controller/v1", name="master_controller_v1")
     */
    public function index()
    {
        return $this->render('master_controller_v1/index.html.twig', [
            'controller_name' => 'MasterControllerV1Controller',
        ]);
    }

    /*********************************  COUNTRY  ********************************** */

     /**
     * @Route("/api/master/country/insert", name="countryinsert",methods={"POST"})
     */
    public function countryInsert_(MasterServiceV2 $masterservice,Request $request)
    {
 
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertCountry($content,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
      
    }
      /**
     * @Route("/api/master/country/code/insert", name="countrycodeinsert",methods={"POST"})
     */
    public function countryCoodeInsert(MasterServiceV2 $masterservice,Request $request)
    {
 
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertCountryCode($content,$entityManager);
        return new ApiResponse([$data],200,["Content-Type"=>"application/json"],'json','success');    
      
    }
    /**
     * @Route("/api/master/country/code/get", name="getcountrycodev1",methods={"GET"})
     */
    public function getCountryCodev1(MasterServiceV2 $masterservice)
    {   
        $data = $masterservice->getCountryCode();
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }

    /**
     * @Route("/api/master/country/update/{id}", name="countryupdate",methods={"PUT"})
     */
    public function countryUpdate_(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updateCountry($content,$entityManager,$id);
        return new ApiResponse([$data],200,["Content-Type"=>"application/json"],'json','success');    
    }


    /**
     * @Route("/api/master/country/get/{id}/{lang}", name="getcountryv1",methods={"GET"})
     */
    public function getCountryv1(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleCountry($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }

     /**
     * @Route("/api/master/country/getAll/{lang}/{domain}", name="getAllCountries",methods={"GET"})
     */
    public function getAllCountry(MasterServiceV2 $masterservice,Request $request,$lang,$domain="front")
    {
        $data = $masterservice->getAllCountries($lang,$domain);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success-');   
    }    

     /**
     * @Route("/api/master/country1/getAll/{lang}/{domain}", name="getAllCountries1",methods={"GET"})
     */
    public function getAllCountry1(MasterServiceV2 $masterservice,Request $request,$lang,$domain="front")
    {
        $data = $masterservice->getAllCountries1($lang,$domain);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success-');   
    }
    
     /**
     * @Route("/api/master/country/statuschange/{id}/{status}", name="statusChange",methods={"PUT"})
     */
    public function statusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->countryStatusChange($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/country/delete/{id}", name="deleteCountry",methods={"PUT"})
     */
    public function deleteCountry(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->deleteCountry($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/country/filter", name="countryFilter",methods={"POST"})
     */
    public function countryFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterCountry($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

   /**
     * @Route("/api/master/excepteuropeancountry/getAll/{lang}", name="getAllExceptEuropeanCountry",methods={"GET"})
     */
    public function getAllExceptEuropeanCountry_(MasterServiceV2 $masterservice,Request $request,$lang)
    {
        $data = $masterservice->getExceptEuropean($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

    /**
     * @Route("/api/master/excepteuropeancountry1/getAll/{lang}", name="getAllExceptEuropeanCountry1",methods={"GET"})
     */
    public function getAllExceptEuropeanCountry1(MasterServiceV2 $masterservice,Request $request,$lang)
    {
        $data = $masterservice->getExceptEuropean1($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }  
    /*********************************  BANK  ********************************** */

     /**
     * @Route("/api/master/bank/insert", name="bankinsert",methods={"POST"})
     */
    public function bankInsert_(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertBank($content,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
    }

    /**
     * @Route("/api/master/bank/update/{id}", name="bankupdate",methods={"PUT"})
     */
    public function bankUpdate(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updateBank($content,$entityManager,$id);
        return new ApiResponse([$data],200,["Content-Type"=>"application/json"],'json','success');    
    }

     /**
     * @Route("/api/master/bank/get/{id}/{lang}", name="getbankv1",methods={"GET"})
     */
    public function getBankv1(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleBank($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   

     /**
     * @Route("/api/master/bank/getAll/{lang}", name="getAllbanks",methods={"GET"})
     */
    public function getAllBanks_(MasterServiceV2 $masterservice,Request $request,$lang)
    {
        $data = $masterservice->getAllBanks($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

      /**
     * @Route("/api/master/bank/statuschange/{id}/{status}", name="bankstatusChange",methods={"PUT"})
     */
    public function bankStatusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->bankStatusChange($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/bank/delete/{id}", name="deleteBank",methods={"PUT"})
     */
    public function DeleteBank(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->deleteBank($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/bank/filter", name="bankFilter",methods={"POST"})
     */
    public function bankFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterBank($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 


/****************************   FILE  *******************************/


  /**
     * @Route("/api/master/file/insert", name="fileinsert",methods={"POST"})
     */
    public function fileInsert_(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertFile($content,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
    }

      /**
     * @Route("/api/master/file/update/{id}", name="fileupdate",methods={"PUT"})
     */
    public function fileUpdate_(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updatefile($content,$entityManager,$id);
        return new ApiResponse([$data],200,["Content-Type"=>"application/json"],'json','success');    
    }

     /**
     * @Route("/api/master/file/get/{id}/{lang}", name="getfilev1",methods={"GET"})
     */
    public function getFileV1_(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleFile($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   

     /**
     * @Route("/api/master/file/getAll/{lang}", name="getAllFiles",methods={"GET"})
     */
    public function getAllFiles_(MasterServiceV2 $masterservice,Request $request,$lang)
    {
        $data = $masterservice->getAllFiles($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

      /**
     * @Route("/api/master/file/statuschange/{id}/{status}", name="filestatusChange",methods={"PUT"})
     */
    public function fileStatusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->fileStatusChange($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/file/delete/{id}", name="filedeleteFile",methods={"PUT"})
     */
    public function DeleteFile(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->deleteFile($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/file/filter", name="fileFilter",methods={"POST"})
     */
    public function fileFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterFile($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 


/*********************************  COMPANYTYPE  ********************************** */

     /**
     * @Route("/api/master/companytype/insert", name="companytypeinsert",methods={"POST"})
     */
    public function companytypeInsert(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertCompanytype($content,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
    }

      /**
     * @Route("/api/master/companytype/update/{id}", name="companytype",methods={"PUT"})
     */
    public function companytypeUpdate_(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updateCompanytype($content,$entityManager,$id);
        return new ApiResponse([$data],200,["Content-Type"=>"application/json"],'json','success');    
    }

     /**
     * @Route("/api/master/companytype/get/{id}/{lang}", name="getcompanytypev1",methods={"GET"})
     */
    public function getCompanytypev1(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleCompanytype($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   

     /**
     * @Route("/api/master/companytype/getAll/{lang}", name="getAllcompanytypes",methods={"GET"})
     */
    public function getAllCompanytypes_(MasterServiceV2 $masterservice,Request $request,$lang)
    {
        $data = $masterservice->getAllCompanytypes($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

      /**
     * @Route("/api/master/companytype/statuschange/{id}/{status}", name="companytypestatusChange",methods={"PUT"})
     */
    public function companytypeStatusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $entityManager = $this->EM;
        $data = $masterservice->companytypeStatusChange($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/companytype/delete/{id}", name="companytypedeleteCompanytype",methods={"PUT"})
     */
    public function DeleteCompanytype(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $entityManager = $this->EM;
        $data = $masterservice->deleteCompanytype($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/companytype/filter", name="companytypeFilter",methods={"POST"})
     */
    public function companytypeFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterCompanytype($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

   
    /*********************************  LEGALFORM  ********************************** */

     /**
     * @Route("/api/master/legalform/insert", name="legalforminsert",methods={"POST"})
     */
    public function legalformInsert_(MasterServiceV2 $masterservice,Request $request)
    {
        try{
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertLegalform($content,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success!!!!!');    
    }
    catch(\Exception $e){
        return new ApiResponse(["MSG"=>$e->getMessage()],400,["Content-Type"=>"application/json"],'json','code already exist');   
    }
    }

      /**
     * @Route("/api/master/legalform/update/{id}", name="legalform",methods={"PUT"})
     */
    public function legalformUpdate_(MasterServiceV2 $masterservice,Request $request,$id)
    {
        try{
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updateLegalform($content,$entityManager,$id);
        return new ApiResponse([$data],200,["Content-Type"=>"application/json"],'json','success');    
    }
    catch(\Exception $e){
        return new ApiResponse(["MSG"=>$e->getMessage()],400,["Content-Type"=>"application/json"],'json','code already exist');   
    }
    }

     /**
     * @Route("/api/master/legalform/get/{id}/{lang}", name="getLegalformv1",methods={"GET"})
     */
    public function getLegalformV1_(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleLegalform($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   

     /**
     * @Route("/api/master/legalform/getAll/{lang}/{domain}", name="getAllLegalforms",methods={"GET"})
     */
    public function getAllLegalforms_(MasterServiceV2 $masterservice,Request $request,$lang,$domain="front")
    {
        $data = $masterservice->getAllLegalforms($lang,$domain);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 
    
     /**
     * @Route("/api/master/legalform1/getAll/{lang}/{domain}", name="getAllLegalforms1",methods={"GET"})
     */
    public function getAllLegalforms1(MasterServiceV2 $masterservice,Request $request,$lang,$domain="front")
    {
        $data = $masterservice->getAllLegalforms1($lang,$domain);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }

      /**
     * @Route("/api/master/legalform/statuschange/{id}/{status}", name="legalformstatusChange",methods={"PUT"})
     */
    public function legalformStatusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $entityManager = $this->EM;
        $data = $masterservice->legalformStatusChanges($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/legalform/delete/{id}", name="deleteLegalform",methods={"PUT"})
     */
    public function DeleteLegalform(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $entityManager = $this->EM;
        $data = $masterservice->deleteLegalforms($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/legalform/filter", name="legalformFilter",methods={"POST"})
     */
    public function legalformFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterLegalforms($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

/*********************************  EPA  ********************************** */

     /**
     * @Route("/api/master/epa/insert", name="epainsert",methods={"POST"})
     */
    public function epaInsert_(MasterServiceV2 $masterservice,Request $request)
    {  
        try{
            
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertEpa($content,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success'); 

        }
        catch(\Exception $e){
            return new ApiResponse(["MSG"=>$e->getMessage()],400,["Content-Type"=>"application/json"],'json','code already exist');   
        }   
    }

      /**
     * @Route("/api/master/epa/update/{id}", name="epaupdate",methods={"PUT"})
     */
    public function epaUpdate_(MasterServiceV2 $masterservice,Request $request,$id)
    {
        try{
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updateEpa($content,$entityManager,$id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');    
    }
    catch(\Exception $e){
        return new ApiResponse(["MSG"=>$e->getMessage()],400,["Content-Type"=>"application/json"],'json','code already exist');   
    }   
}

     /**
     * @Route("/api/master/epa/get/{id}/{lang}", name="getepav1",methods={"GET"})
     */
    public function getEpaV1(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleEpa($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   

     /**
     * @Route("/api/master/epa/getAll/{lang}/{domain}", name="getAllEpas",methods={"GET"})
     */
    public function getAllEpas_(MasterServiceV2 $masterservice,Request $request,$lang,$domain="front")
    {
        $data = $masterservice->getAllEpas($lang,$domain);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

     /**
     * @Route("/api/master/epa1/getAll/{lang}/{domain}", name="getAllEpas1",methods={"GET"})
     */
    public function getAllEpas1(MasterServiceV2 $masterservice,Request $request,$lang,$domain="front")
    {
        $data = $masterservice->getAllEpas1($lang,$domain);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }

      /**
     * @Route("/api/master/epa/statuschange/{id}/{status}", name="epastatusChange",methods={"PUT"})
     */
    public function epaStatusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $entityManager = $this->EM;
        $data = $masterservice->epaStatusChange($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/epa/delete/{id}", name="deleteEpa",methods={"PUT"})
     */
    public function DeleteEpa(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $entityManager = $this->EM;
        $data = $masterservice->deleteEpa($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/epa/filter", name="epaFilter",methods={"POST"})
     */
    public function epaFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterEpa($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

/*********************************  TYPECLIENT  ********************************** */

     /**
     * @Route("/api/master/typeclient/insert", name="typeclientinsert",methods={"POST"})
     */
    public function typeclientInsert_(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertTypeclient($content,$entityManager);
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','success');    
    }

      /**
     * @Route("/api/master/typeclient/update/{id}", name="typeclientupdate",methods={"PUT"})
     */
    public function typeclientUpdate_(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updateTypeclient($content,$entityManager,$id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');    
    }

     /**
     * @Route("/api/master/typeclient/get/{id}/{lang}", name="getTypeclientv1",methods={"GET"})
     */
    public function getTypeclientV1(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleTypeclient($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   

     /**
     * @Route("/api/master/typeclient/getAll/{lang}", name="getAllTypeclients",methods={"GET"})
     */
    public function getAllTypeclients_(MasterServiceV2 $masterservice,Request $request,$lang)
    {
        $data = $masterservice->getAllTypeclients($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

      /**
     * @Route("/api/master/typeclient/statuschange/{id}/{status}", name="typeclientstatusChange",methods={"PUT"})
     */
    public function typeclientStatusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $entityManager = $this->EM;
        $data = $masterservice->typeclientStatusChange($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/typeclient/delete/{id}", name="deleteTypeclient",methods={"PUT"})
     */
    public function DeleteTypeclient(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $entityManager = $this->EM;
        $data = $masterservice->deleteTypeclient($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/typeclient/filter", name="typeclientFilter",methods={"POST"})
     */
    public function typeclientFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterTypeclient($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 
    

    /*********************************  FUNCTION  ********************************** */

     /**
     * @Route("/api/master/function/insert", name="functioninsert",methods={"POST"})
     */
    public function functionInsert_(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->insertFunction($content,$entityManager);
        if($data!='ok'){
             return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',$data);    
        }
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','successs');    
    }

      /**
     * @Route("/api/master/function/update/{id}", name="functionupdate",methods={"PUT"})
     */
    public function functionUpdate_(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $data = $masterservice->updateFunction($content,$entityManager,$id);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');    
    }

     /**
     * @Route("/api/master/function/get/{id}/{lang}", name="getFunctionv1",methods={"GET"})
     */
    public function getFunctionV1(MasterServiceV2 $masterservice,Request $request,$id,$lang)
    {
        $data = $masterservice->getSingleFunction($id,$lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }   

     /**
     * @Route("/api/master/function/getAll/{lang}/{domain}", name="getAllFunctions",methods={"GET"})
     */
    public function getAllFunctions_(MasterServiceV2 $masterservice,Request $request,$lang,$domain="front")
    {
        $data = $masterservice->getAllFunctions($lang,$domain);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }    

      /**
     * @Route("/api/master/function/statuschange/{id}/{status}", name="functionstatusChange",methods={"PUT"})
     */
    public function functionStatusChange(MasterServiceV2 $masterservice,Request $request,$id,$status)
    {
        $entityManager = $this->EM;
        $data = $masterservice->functionStatusChange($id,$status,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/function/delete/{id}", name="deleteFunction",methods={"PUT"})
     */
    public function DeleteFunction(MasterServiceV2 $masterservice,Request $request,$id)
    {
        $entityManager = $this->EM;
        $data = $masterservice->deleteFunction($id,$entityManager);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 

     /**
     * @Route("/api/master/function/filter", name="functionFilter",methods={"POST"})
     */
    public function functionFilter(MasterServiceV2 $masterservice,Request $request)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $entityManager = $this->EM;
        $status = $serializer->deserialize($content, Status::class, 'json');
        $data = $masterservice->filterFunction($status);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    } 
    
    /**
     * @Route("/api/getAll/requeststatus/{lang}", name="getAllRequeststatus",methods={"GET"})
     */
    public function getAllRequeststatus(MasterServiceV2 $masterservice,Request $request,$lang)
    {
        $data = $masterservice->getAllReqStatus($lang);
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json','success');   
    }     
}
