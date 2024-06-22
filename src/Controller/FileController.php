<?php

namespace App\Controller;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Utils\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use App\Entity\DataRequestFile;
use App\Entity\RefSignature;
use App\Repository\DataClientRepository;
use App\Repository\RefFileRepository;
use App\Repository\DataRequestRepository;
use App\Repository\DataRequestFileRepository;
use App\Repository\DataAttorneyRepository;
use App\Repository\RefRequeststatusRepository;
use App\UtilsV3\File;
use App\Service\DocumentService;
use App\Service\ClientService;
use App\Utils\ApiResponse;
use App\Entity\DataAttorney;
use App\Entity\DataRequestRequeststatus;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileController extends AbstractController
{
    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
     * @Route("/file", name="file")
     */
    public function index()
    {
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }

    /**
     * @Route("/api/offerfileupload/{folder}", name="offerfileupload")
     */
    public function offerFileUpload_($folder,Request $request,FileUploader $uploader){
        $file = $request->files->get('files');
        if (empty($file)) {
            return new Response("No file specified",  
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }
        $folder = str_replace("..", "", $folder);
        $filename = $file->getClientOriginalName();
        $uploader->upload('../file/'.$folder, $file, $filename);
        $filetype = mime_content_type("../file/".$folder."/".$filename);
        $origianl = explode("/", $filetype); 
        $extension = explode(".", $filename); 
        if($origianl[1] =='jpg' || $origianl[1] =='png' || $origianl[1] =='jpeg'){
            if($extension[1] =='jpg' || $extension[1] =='png' || $extension[1] =='jpeg'){
                return new ApiResponse(['path' => $filename],200,["Content-Type"=>"application/json"],'json','success');
            }   
        }
        $deletfilepath = "../file/".$folder."/".$filename;
        $filesystem = new Filesystem();
        $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
        return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid filetype or extension');
    }

    /**
     * @Route("/api/offerfiledownload/{filename}", name="offerfiledownload")
     */
    public function offerFileDownload_($filename, Request $request, FileUploader $uploader){
        $filesystem = new Filesystem();
        $filename = str_replace("..", "", $filename);
        $filename = str_replace("*", "", $filename);
        $url = "../file/offer/".$filename;
        $path = $filesystem->exists($url);
        if ($path == false) {
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','file not found');     
        }
        $file1 = file_get_contents($url);
        return $this->file($url);
    }

    /**
     * @Route("/api/sigimgdownload/{filename}", name="sigimgdownload")
     */
    public function sigImgDownload($filename, Request $request, FileUploader $uploader){
        $filesystem = new Filesystem();
        $filename = str_replace("..", "", $filename);
        $filename = str_replace("*", "", $filename);
        $url = "../file/signature/".$filename;
        $path = $filesystem->exists($url);
        if ($path == false) {
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','file not found');     
        }
        $file1 = file_get_contents($url);
        return $this->file($url);
    }

    /**
     * @Route("/api/fileuploadv2/{folder}", name="fileuploadv2")
     */
    public function fileuploadv2_($folder,Request $request,FileUploader $uploader){
        $file = $request->files->get('files');

        $folder = str_replace("..", "", $folder);

        if (empty($file)) 
        {
            return new Response("No file specified",  
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }        

        $filename = $file->getClientOriginalName();
        if($folder =='signature'){
            $str = strlen($filename) - 4;  
            $str = substr($filename,0,$str);  
            $date = date('d-m-Y_H-i-s');
            $extension = strlen($filename) - 3;  
            $extension = substr($filename, $extension);
            $filename = $str.$date.'.'.$extension;
        }
        $uploader->upload("../file/".$folder, $file, $filename);
        $filetype = mime_content_type("../file/".$folder."/".$filename);
        $origianl = explode ("/", $filetype); 
        $extension = strlen($filename) - 3;  
        $extension = substr($filename, $extension); 
        $extension = strtolower($extension);
        $origianl = strtolower($origianl[1]);
        if($origianl=='pdf'||$origianl=='jpg'||$origianl=='png'||$origianl=='jpeg'){
            if($extension=='pdf'||$extension=='jpg'||$extension=='png'||$extension=='peg'){
                return new ApiResponse(['path' => "$folder/".$filename],200,["Content-Type"=>"application/json"],'json','success');              
            }   
        }
        $deletfilepath = "../file/".$folder."/".$filename;
        $filesystem = new Filesystem();
        $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
        return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid filetype or extension');
        
    }


    /**
     * @Route("/api/filedownload_withuuid/{uuid}", name="filedownload_withuuid")
     */

    public function filedownload_withuuid($uuid, Request $request, ClientService $clientser){
        $filesystem = new Filesystem();
        $reqfilerepo = $this->EM->getRepository(DataRequestFile::class);
        $dataRequestfile = $reqfilerepo->findOneBy(['file_uuid'=>$uuid]);
        if(!$dataRequestfile){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','invalid uuid');     
        }

        if(!$clientser->checkFileId($dataRequestfile->getId(), $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $url = $dataRequestfile->getPath();
        $path = $filesystem->exists($url);
        if($path == false){
         return new ApiResponse([$path],404,["Content-Type"=>"application/json"],'json','file not found');     
        }
        $file1 = file_get_contents($url);
        return $this->file($url);
    }

    /**
     * @Route("/api/signaturedownload_withuuid/{uuid}", name="signaturedownload_withuuid")
     */
    public function signaturedownload_withuuid($uuid){
        $filesystem = new Filesystem();
        $signaturerepo = $this->EM->getRepository(RefSignature::class);
        $datasignature = $signaturerepo->findOneBy(['uuid'=>$uuid]);
        if(!$datasignature){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json','invalid uuid');     
        }
        $url = $datasignature->getData();
        $url = "../file/".$url;
        $path = $filesystem->exists($url);
        if($path == false){
         return new ApiResponse([$path],404,["Content-Type"=>"application/json"],'json','file not found');     
        }
        $file1 = file_get_contents($url);
        return $this->file($url);
    }
    
    /**
     * @Route("/api/uploadfileV3", name="uploadfilev3",methods={"POST"})
     */
    public function uploadfilev3(ClientService $clientser,Request $request,RefRequeststatusRepository $reqstatusrepo,FileUploader $uploader,DataClientRepository $clientrepo,RefFileRepository $filerepo,DataRequestRepository $reqrepo,DataRequestFileRepository $filereqrepo,DocumentService $docservice){
        $entityManager = $this->EM;
        $file = $request->files->get('files');
        $client_id = $request->get('client_id');
        $description = null;
        if($request->get('description')){
            $description = $request->get('description');
        }
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $file_id = $request->get('file_id');
        $actfilename = $file->getClientOriginalName();
        $str = strlen($actfilename) - 3;  
        $str = substr($actfilename, $str);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $date = new \DateTime();
        $client = $clientrepo->findOneBy(['id'=>$client_id]);
        if(!$client){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid client id');
        }
        $reqfile = $filerepo->findOneBy(['id'=>$file_id]);
        if(!$reqfile){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid file id');          
        }
        $datareq = $reqrepo->findOneBy(['client'=>$client_id]);
        if(!$datareq){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid client id');         
        }
        $filename = $reqfile->getFilenameFile();
        $date = date('d-m-Y_H-i-s');
        $folder = 'file';
        $filenamecopy = $filename.'_'.$client_id.'_'.$date.'.'.$str;
        $path = "../file/$client_id/$filenamecopy";
        if (empty($file)) 
        {
            return new Response("No file specified",  
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }        

        $filecheck = $filereqrepo->findOneBy(['file'=>$reqfile->getId(),'request'=>$datareq->getId()]);
        if(!$filecheck){
            $fileup = $uploader->upload("../file/".$client_id,$file,$filenamecopy);
            $filetype = mime_content_type("../file/".$client_id."/".$filenamecopy);
            $origianl = explode ("/", $filetype); 
            $extension = explode (".", $filenamecopy);
            $extension = strtolower($extension[1]);
            $origianl = strtolower($origianl[1]);  
            if($origianl=='pdf'||$origianl=='jpg'||$origianl=='png'||$origianl=='jpeg'){
                if($extension=='pdf'||$extension=='jpg'||$extension=='png'||$extension=='jpeg'){
                    $uuid = $docservice->UuidGenerate($client_id);
                    $datareqfile = new DataRequestFile();
                    $datareqfile->setFile($reqfile);
                    $datareqfile->setRequest($datareq);
                    $datareqfile->setPath($path);
                    $datareqfile->setFilename($actfilename);
                    $datareqfile->setFileUuid($uuid);
                    $datareqfile->setDescription($description);
                    $entityManager->persist($datareqfile);
                    $entityManager->flush();

                    $kbis = $filereqrepo->findOneBy(['file'=>9,'request'=>$datareq->getId()]);
                    $kbis2 = $filereqrepo->findOneBy(['file'=>10,'request'=>$datareq->getId()]);
                    $datareqfile->setIsKbis(false);
                    if($kbis!=null && $kbis2!=null){
                        $datareqfile->setIsKbis(true);
                    }
                    return new ApiResponse($datareqfile,200,["Content-Type"=>"application/json"],'json','success1',["request","path"]);    
                }   
            }
            else{
                $deletfilepath = "../file/".$client_id."/".$filenamecopy;
                $filesystem = new Filesystem();
                $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid filetype');
            }   
        }
        else{
            $fileup = $uploader->upload("../file/".$client_id,$file,$filenamecopy);
            $filetype = mime_content_type("../file/".$client_id."/".$filenamecopy);
            $origianl = explode ("/", $filetype); 
            $extension = strlen($filenamecopy) - 3;  
            $extension = substr($filenamecopy, $extension);
            $extension = strtolower($extension);
            $origianl = strtolower($origianl[1]); 
            if($origianl=='pdf'||$origianl=='jpg'||$origianl=='png'||$origianl=='jpeg'){
                if($extension=='pdf'||$extension=='jpg'||$extension=='png'||$extension=='peg'){
                    $filecheck->setPath($path);
                    $filecheck->setFilename($actfilename);
                    $filecheck->setDescription($description);
                    $entityManager->persist($filecheck);
                    $entityManager->flush();
                    $kbis = $filereqrepo->findOneBy(['file'=>9,'request'=>$datareq->getId()]);
                    $kbis2 = $filereqrepo->findOneBy(['file'=>10,'request'=>$datareq->getId()]);
                    $filecheck->setIsKbis(false);
                    if($kbis!=null && $kbis2!=null){
                        $filecheck->setIsKbis(true);
                    }
                    return new ApiResponse($filecheck,200,["Content-Type"=>"application/json"],'json','success',["request",'path']);  
                }
            }    
            else{
                $deletfilepath = "../file/".$client_id."/".$filenamecopy;
                $filesystem = new Filesystem();
                $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid filetype');
            }   
        }
        return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','somthing went wrong');  
    }

    /**
     * @Route("/api/updaterequest/kbis/{client_id}", name="kbisUpdateRequest",methods={"PUT"})
     */
    public function kbisUpdateRequest(ClientService $clientser,Request $request,$client_id,RefRequeststatusRepository $reqstatusrepo,DataRequestRepository $reqrepo,DataRequestFileRepository $filereqrepo){
        $entityManager = $this->EM;
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $datareq = $reqrepo->findOneBy(['client'=>$client_id]);
        if(!$datareq){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid client id');         
        }
        $reqstatus = $reqstatusrepo->findOneBy(['status_requeststatus'=>161]);
        $datareq->setRequeststatus($reqstatus);
        $datareq->setDateupdRequest(new \DateTime());
        $entityManager->persist($datareq);
        $entityManager->flush();
        $datarequestrequeststatus=new DataRequestRequeststatus();
        $datarequestrequeststatus->setIdRequest($datareq);
        $datarequestrequeststatus->setIdRequeststatus($reqstatus);
        $datarequestrequeststatus->setDateRequestRequeststatus(new \DateTime());
        $entityManager->persist($datarequestrequeststatus);
        $entityManager->flush();
        return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','successfully updated');  

    }    

    /**
     * @Route("/api/uploadfileattorney", name="uploadfileattorney",methods={"POST"})
     */
    public function uploadFileAttorney(Request $request,FileUploader $uploader,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefFileRepository $filerepo,DataRequestRepository $reqrepo,DataRequestFileRepository $filereqrepo,DocumentService $docservice, ClientService $clientser){
        $entityManager = $this->EM;
        $type = $request->get('type');
        $file = $request->files->get('files');
        $client_id = $request->get('client_id');

        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $file_id = $request->get('file_id');
        $getpresentage = $request->get('percentage');
        $is_mandatory = $request->get('is_mandatory');
        if($is_mandatory=='true'){
            $attony =$attonyrepo->findOneBy(['ismandatory_attorney'=>true,'client'=>$client_id]);
            $is_mandatory=true;
        }
        else{
            $attony = new DataAttorney;
            $is_mandatory=false;
        }
        $uuid = $docservice->UuidGenerate($client_id);
        $presentage=0;
        $attoneychk = $attonyrepo->findBy(['client'=>$client_id,'isshareholder_attorney'=>true,'active_attorney'=>'Active']);
        foreach($attoneychk as $atto){
            $presentage = $presentage+$atto->getPercentageAttorney();
        }
        $calcpresentage = $presentage+$getpresentage;
        if($calcpresentage>100){
            $remainingpresentage=100-$presentage;
        }
        $remainingpresentage=100-$calcpresentage;
        $clientchk = $clientrepo->findOneBy(['id'=>$client_id]);       
        if(!$clientchk){
            return new ApiResponse([],404,["Content-Type"=>"application/json"],'json',"invalid client!!!!");
        } 
        if($type == '2'){
            $companyname = $request->get('companyname');
            $clientchk = $clientrepo->findOneBy(['id'=>$client_id]);
            $attony->setClient($clientchk);
            $attony->setCompanyName($companyname);
            $attony->setPercentageAttorney($getpresentage);
            $attony->setIsshareholderAttorney(true);
            $attony->setIsmandatoryAttorney($is_mandatory);
            $attony->setIscompany(true);
            $attony->setActiveAttorney("Active");
            $entityManager->persist($attony);
            $entityManager->flush();
            $attoneyvalue = $attonyrepo->findOneBy(['id'=>$attony]);
            $actfilename = $file->getClientOriginalName();
            $str = strlen($actfilename) - 3;  
            $str = substr($actfilename, $str);  
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $date = new \DateTime();
            $reqfile = $filerepo->findOneBy(['id'=>$file_id]);
            if(!$reqfile){
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid file id');          
            }
            $datareq = $reqrepo->findOneBy(['client'=>$client_id]);
            if(!$datareq){
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid client id');          
            }
            $filename = $reqfile->getFilenameFile();
            $date = date('d-m-Y_H-i-s');
            $folder = 'file';
            $filenamecopy = $filename.'_'.$client_id.'_'.$attoneyvalue->getId().'_'.$date.'.'.strtolower($str);
            $path = "../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy;
            if (empty($file)) 
            {
                return new Response("No file specified",  
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
            }        
            $fileup = $uploader->upload("../file/".$client_id."/".$attoneyvalue->getId(),$file,$filenamecopy);
            $filetype = mime_content_type("../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy); 
            $origianl = explode ("/", $filetype); 
            $extension = strlen($filenamecopy) - 3;  
            $extension = substr($filenamecopy, $extension); 
            $extension = strtolower($extension);
            $origianl = strtolower($origianl[1]);
            if($origianl=='pdf'||$origianl=='jpg'||$origianl=='png'||$origianl=='jpeg'){
                if($extension=='pdf'||$extension=='jpg'||$extension=='png'||$extension=='peg'){
                    $datareqfile = new DataRequestFile();
                    $datareqfile->setFile($reqfile);
                    $datareqfile->setRequest($datareq);
                    $datareqfile->setPath($path);
                    $datareqfile->setFilename($actfilename);
                    $datareqfile->setAttorney($attoneyvalue);
                    $datareqfile->setFileUuid($uuid);
                    $entityManager->persist($datareqfile);
                    $entityManager->flush();
                    $attoneychk = $attonyrepo->findBy(['client'=>$client_id,'isshareholder_attorney'=>true,'active_attorney'=>'Active']);
                    $presentage =0;
                    foreach($attoneychk as $atto){
                        $presentage = $presentage+$atto->getPercentageAttorney();
                    }
                    $remainingpresentage=100-$presentage;
                    return new ApiResponse(['id'=>$datareqfile->getId(),'filename'=>$datareqfile->getFilename(),'attorney_id'=>$attoneyvalue->getId(),'file_id'=>$reqfile->getId(),'saved_filename'=>$filenamecopy,'fileUuid'=>$datareqfile->getFileUuid()],200,["Content-Type"=>"application/json"],'json','success',["request","path"],$remainingpresentage);
                }
                else{
                    $deletfilepath = "../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy;
                    $filesystem = new Filesystem();
                    $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
                    $entityManager->remove($attoneyvalue);
                    $entityManager->flush();
                    $entityManager->remove($attoneyvalue);
                    $entityManager->flush();
                    return new ApiResponse($filenamecopy,401,["Content-Type"=>"application/json"],'json','invalid extension');   
                }
            }        
            else{
                $deletfilepath = "../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy;
                $filesystem = new Filesystem();
                $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
                $entityManager->remove($attoneyvalue);
                $entityManager->flush();
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid filetype');
            }
        }
        if($type== '1'){
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $dob = $request->get('dob');
            $attony->setClient($clientchk);
            $attony->setNameAttorney($nom);
            $attony->setSurnameAttorney($prenom);
            $attony->setDatebirthAttorney(new \DateTime($dob));
            $attony->setPercentageAttorney($getpresentage);
            $attony->setIsshareholderAttorney(true);
            $attony->setIsmandatoryAttorney($is_mandatory);
            $attony->setIscompany(false);
            $attony->setActiveAttorney("Active");
            $entityManager->persist($attony);
            $entityManager->flush();
            $attoneyvalue = $attonyrepo->findOneBy(['id'=>$attony]);      
            $actfilename = $file->getClientOriginalName();
            $str = strlen($actfilename) - 3;  
            $str = substr($actfilename, $str); 
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $date = new \DateTime();
            $reqfile = $filerepo->findOneBy(['id'=>$file_id]);
            if(!$reqfile){
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid file id');          
            }
            $datareq = $reqrepo->findOneBy(['client'=>$client_id]);
            if(!$datareq){
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid client id');         
            }
            $filename = $reqfile->getFilenameFile();
            $date = date('d-m-Y_H-i-s');
            $folder = 'file';
            $filenamecopy = $filename.'_'.$client_id.'_'.$attoneyvalue->getId().'_'.$date.'.'.strtolower($str);
            $path = "../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy;
            if (empty($file)) 
            {
                return new Response("No file specified",  
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
            }        
            $fileup = $uploader->upload("../file/".$client_id."/".$attoneyvalue->getId(),$file,$filenamecopy);
            $filetype = mime_content_type("../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy); 
            $origianl = explode ("/", $filetype); 
            $extension = strlen($filenamecopy) - 3;  
            $extension = substr($filenamecopy, $extension); 
            $extension = strtolower($extension);
            $origianl = strtolower($origianl[1]);
            if($origianl=='pdf'||$origianl=='jpg'||$origianl=='png'||$origianl=='jpeg'){
                if($extension=='pdf'||$extension=='jpg'||$extension=='png'||$extension=='peg'){
                    $datareqfile = new DataRequestFile();
                    $datareqfile->setFile($reqfile);
                    $datareqfile->setRequest($datareq);
                    $datareqfile->setPath($path);
                    $datareqfile->setFilename($actfilename);
                    $datareqfile->setAttorney($attoneyvalue);
                    $datareqfile->setFileUuid($uuid);
                    $entityManager->persist($datareqfile);
                    $entityManager->flush();
                    $attoneychk = $attonyrepo->findBy(['client'=>$client_id,'isshareholder_attorney'=>true,'active_attorney'=>'Active']);
                    $presentage =0;
                    foreach($attoneychk as $atto){
                        $presentage = $presentage+$atto->getPercentageAttorney();
                    }
                    $remainingpresentage=100-$presentage;
                    return new ApiResponse(['id'=>$datareqfile->getId(),'filename'=>$datareqfile->getFilename(),'attorney_id'=>$attoneyvalue->getId(),'file_id'=>$reqfile->getId(),'saved_filename'=>$filenamecopy,'fileUuid'=>$datareqfile->getFileUuid()],200,["Content-Type"=>"application/json"],'json','success',["request"],$remainingpresentage); 
                }
                else{
                    $deletfilepath = "../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy;
                    $filesystem = new Filesystem();
                    $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
                    $entityManager->remove($attoneyvalue);
                    $entityManager->flush();
                    return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid extension');   
                }
            }        
            else{
                $deletfilepath = "../file/".$client_id."/".$attoneyvalue->getId()."/".$filenamecopy;
                $filesystem = new Filesystem();
                $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
                $entityManager->remove($attoneyvalue);
                $entityManager->flush();
                return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid filetype');   
            }
        }
        return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid type');   
    }


     /**
     * @Route("/api/updatefileattorney", name="updatefileattorney",methods={"POST"})
     */

    public function updateFileAttorney(ClientService $clientser,Request $request,FileUploader $uploader,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefFileRepository $filerepo,DataRequestRepository $reqrepo,DataRequestFileRepository $filereqrepo,DocumentService $docservice){
        $entityManager = $this->EM;
        $file = $request->files->get('files');
        $attorney_id = $request->get('attorney_id');
        $file_id = $request->get('file_id');
        $client_id = $request->get('client_id');
        $description = null;
        if($request->get('description')){
            $description = $request->get('description');
        }
        // return $description;
        if(!$clientser->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $reqFile=$filereqrepo->findOneBy(['attorney'=>$attorney_id,'file'=>$file_id]);
        $requestobj=$reqrepo->findOneBy(['client'=>$client_id]);
        $fileobj=$filerepo->findOneBy(['id'=>$file_id]);
        $attorneyobj=$attonyrepo->findOneBy(["id"=>$attorney_id]);
        if($reqFile){
            $client_id=$reqFile->getRequest()->getId();
            $deletfilepath=$reqFile->getPath();
            $filesystem = new Filesystem();
            $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);   
        }
        else{
            $unnid = $docservice->UuidGenerate($client_id);
            $reqFile = new DataRequestFile;
            $reqFile->setFile($fileobj);
            $reqFile->setRequest($requestobj);
            $reqFile->setAttorney($attorneyobj);
            $reqFile->setFileUuid($unnid);
            // $reqFile->setDescription($description);
        }
        $actfilename = $file->getClientOriginalName();
        $str = strlen($actfilename) - 3;  
        $str = substr($actfilename, $str); 
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $date = new \DateTime();
        $reqfile = $filerepo->findOneBy(['id'=>$file_id]);
        if(!$reqfile){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid file id');          
        }
        $filename = $reqfile->getFilenameFile();
        $date = date('d-m-Y_H-i-s');
        $folder = 'file';
        $filenamecopy = $filename.'_'.$client_id.'_'.$attorney_id.'_'.$date.'.'.strtolower($str);
        $path = "../file/".$client_id."/".$attorney_id."/".$filenamecopy;
        if (empty($file)) 
        {
            return new Response("No file specified",  
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }        
        $fileup = $uploader->upload("../file/".$client_id."/".$attorney_id,$file,$filenamecopy);
        $filetype = mime_content_type("../file/".$client_id."/".$attorney_id."/".$filenamecopy); 
        $origianl = explode ("/", $filetype); 
        $str = strlen($filenamecopy) - 3;  
        $extension = substr($filenamecopy, $str);
        $extension = strtolower($extension);
        $origianl = strtolower($origianl[1]);  
        if($origianl=='pdf'||$origianl=='jpg'||$origianl=='png'||$origianl=='jpeg'){
            if($extension=='pdf'||$extension=='jpg'||$extension=='png'||$extension=='peg'){
                $reqFile->setPath($path);
                $reqFile->setFilename($actfilename);
                $reqFile->setDescription($description);
                $entityManager->persist($reqFile);
                $entityManager->flush();
                return new ApiResponse(['id'=>$reqFile->getId(),'filename'=>$reqFile->getFilename(),'attorney_id'=>$attorney_id,'file_id'=>$file_id,'saved_filename'=>$filenamecopy,"fileUuid"=>$reqFile->getFileUuid(),"description"=>$description],200,["Content-Type"=>"application/json"],'json','success',["request",'timezone','attorney',"path"]); 
            }
        }
        else{
            $deletfilepath = "../file/".$client_id."/".$attorney_id."/".$filenamecopy;
            $filesystem = new Filesystem();
            $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid filetype');
        }
        
        }

    /**
     * @Route("/api/updatefileclient", name="updatefileclient",methods={"POST"})
     */

    public function updateFileClient(Request $request,FileUploader $uploader,DataClientRepository $clientrepo,DataAttorneyRepository $attonyrepo,RefFileRepository $filerepo,DataRequestRepository $reqrepo,DataRequestFileRepository $filereqrepo,DocumentService $docservice){
        $entityManager = $this->EM;
        $file = $request->files->get('files');
        $file_id = $request->get('file_id');
        $client_id = $request->get('client_id');
        $requestobj = $reqrepo->findOneBy(['client'=>$client_id]);
        $reqFile = $filereqrepo->findOneBy(['request'=>$requestobj->getId(),'file'=>$file_id]);
        $fileobj = $filerepo->findOneBy(['id'=>$file_id]);
        if($reqFile){
            $deletfilepath = $reqFile->getPath();
            $filesystem = new Filesystem();
            $filesystem->remove(['symlink',$deletfilepath, 'activity.log']);   
        }
       else{
        $unnid = $docservice->UuidGenerate($client_id);
        $reqFile = new DataRequestFile;
        $reqFile->setFile($fileobj);
        $reqFile->setRequest($requestobj);
        $reqFile->setFileUuid($unnid);

       }
        $actfilename = $file->getClientOriginalName();
        $str_arr = explode (".", $actfilename); 
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $date = new \DateTime();
        $reqfile = $filerepo->findOneBy(['id'=>$file_id]);
        if(!$reqfile){
            return new ApiResponse([],401,["Content-Type"=>"application/json"],'json','invalid file id');          
        }
        $filename = $reqfile->getFilenameFile();
        $date = date('d-m-Y_H-i-s');
        $folder = 'file';
        $filenamecopy = $filename.'_'.$client_id.'_'.$date.'.'.$str_arr[1];
        $path = "../file/".$client_id."/".$filenamecopy;
        if (empty($file)) 
        {
            return new Response("No file specified",  
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }        
        $fileup = $uploader->upload("../file/".$client_id."/",$file,$filenamecopy);
        $reqFile->setPath($path);
        $reqFile->setFilename($actfilename);
        $entityManager->persist($reqFile);
        $entityManager->flush();
        return new ApiResponse(['filename'=>$reqFile->getFilename(),'file_id'=>$file_id,'saved_filename'=>$filenamecopy],200,["Content-Type"=>"application/json"],'json','success',["request",'timezone','attorney']);
    }

    /**
     * @Route("/api/getfiledetail/{client_id}", name="getfiledetail")
     */
    public function getfiledetail_(Request $request,RefFileRepository $filerepo,DataRequestFileRepository $filereqrepo,$client_id,EntityManagerInterface $EM, ClientService $clientservice)
    {
		
        if(!$clientservice->checkClientId($client_id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $proof_of_address = $filerepo->findOneBy(['jsonkey'=>'proof_of_address']);
        $proof_of_identity_1 = $filerepo->findOneBy(['jsonkey'=>'proof_of_identity_1']);
        $proof_of_identity_2 = $filerepo->findOneBy(['jsonkey'=>'proof_of_identity_2']);
        $your_signature = $filerepo->findOneBy(['jsonkey'=>'your_signature']);
        $k_bis_less_than_3_months = $filerepo->findOneBy(['jsonkey'=>'k_bis_less_than_3_months']);
        $status_of_your_company = $filerepo->findOneBy(['jsonkey'=>'status_of_your_company']);
        $rib = $filerepo->findOneBy(['jsonkey'=>'RIB']);
        $project_status = $filerepo->findOneBy(['jsonkey'=>'project_status']);
        $fileproof = $filerepo->findOneBy(['jsonkey'=>'KBIS']);
        $kbis2 = $filerepo->findOneBy(['jsonkey'=>'KBIS2']);
        $contrat = $filerepo->findOneBy(['jsonkey'=>'contrat']);
        $ribfidu = $filerepo->findOneBy(['jsonkey'=>'RIB_Fiducial']);
        $attdep = $filerepo->findOneBy(['jsonkey'=>'attestation']);
        $document1 = $filerepo->findOneBy(['jsonkey'=>'document1']);
        $document2 = $filerepo->findOneBy(['jsonkey'=>'document2']);
        $document3 = $filerepo->findOneBy(['jsonkey'=>'document3']);
        $clientcheck= $filereqrepo->findOneBy(['request'=>$client_id]);
        $proof_of_address_data = ($proof_of_address != null) ? $filereqrepo->findOneBy(['file'=> $proof_of_address->getId(),'request'=>$client_id]) : array();
        $proof_of_identity_1_data = ($proof_of_identity_1 != null) ? $filereqrepo->findOneBy(['file'=> $proof_of_identity_1->getId(),'request'=>$client_id]) : array();
        $proof_of_identity_2_data = ($proof_of_identity_2 != null) ? $filereqrepo->findOneBy(['file'=> $proof_of_identity_2->getId(),'request'=>$client_id]) : array();
        $your_signature_data = ($your_signature != null) ? $filereqrepo->findOneBy(['file'=> $your_signature->getId(),'request'=>$client_id]) : array();
        $k_bis_less_than_3_months_data = ($k_bis_less_than_3_months != null) ? $filereqrepo->findOneBy(['file'=> $k_bis_less_than_3_months->getId(),'request'=>$client_id]) : array();
        $status_of_your_company_data = ($status_of_your_company != null) ? $filereqrepo->findOneBy(['file'=> $status_of_your_company->getId(),'request'=>$client_id]) : array();
        $rib_data = ($rib != null) ? $filereqrepo->findOneBy(['file'=> $rib->getId(),'request'=>$client_id,'attorney'=>null]) : array();
        $fileproof_data = ($fileproof != null) ? $filereqrepo->findOneBy(['file'=> $fileproof->getId(),'request'=>$client_id]) : array();
        $kbis2_data = ($fileproof != null) ? $filereqrepo->findOneBy(['file'=> $kbis2->getId(),'request'=>$client_id]) : array();
        $project_status_data = ($project_status != null) ? $filereqrepo->findOneBy(['file'=> $project_status->getId(),'request'=>$client_id]) : array();
        $contrat_data = ($contrat != null) ? $filereqrepo->findOneBy(['file'=> $contrat->getId(),'request'=>$client_id]) : array();
        $ribfidu_data = ($ribfidu != null) ? $filereqrepo->findOneBy(['file'=> $ribfidu->getId(),'request'=>$client_id]) : array();
        $attdep_data = ($attdep != null) ? $filereqrepo->findOneBy(['file'=> $attdep->getId(),'request'=>$client_id]) : array();
        $document1_data = ($document1 != null) ? $filereqrepo->findOneBy(['file'=> $document1->getId(),'request'=>$client_id]) : array();
        $document2_data = ($document2 != null) ? $filereqrepo->findOneBy(['file'=> $document2->getId(),'request'=>$client_id]) : array();
        $document3_data = ($document3 != null) ? $filereqrepo->findOneBy(['file'=> $document3->getId(),'request'=>$client_id]) : array();
        $RAW_QUERY = 'SELECT * FROM data_request_file where data_request_file.request_id =:client_id AND data_request_file.attorney_id IS NOT NULL;';
        $statement = $EM->getConnection()->prepare($RAW_QUERY);
        $connection = $this->EM->getconnection();
        $rib_datas = $connection->executeQuery($RAW_QUERY, ['client_id' => $client_id])->fetchAll();
        return new ApiResponse(["proof_of_address"=>$proof_of_address_data,"proof_of_identity_1"=>$proof_of_identity_1_data,
                                "proof_of_identity_2"=>$proof_of_identity_2_data,"your_signature"=>$your_signature_data,
                                "k_bis_less_than_3_months"=>$k_bis_less_than_3_months_data,"status_of_your_company"=>$status_of_your_company_data,
                                "RIB"=>$rib_data,"project_status"=>$project_status_data,"RIB_DATAS"=>$rib_datas,'KBIS'=>$fileproof_data,'KBIS2'=>$kbis2_data,'contrat'=>$contrat_data, 'RIB_Fiducial'=>$ribfidu_data, "Attestation" => $attdep_data,"document1"=>$document1_data,"document2"=>$document2_data,"document3"=>$document3_data],200,["Content-Type"=>"application/json"],'json','success',["request","path"]); 

    }

    /**
     * @Route("/api/getAttachement/{type}/{clientid}", name="getAttachement")
     */
    public function getAttachement(Request $request,RefFileRepository $filerepo,DataRequestFileRepository $filereqrepo,$clientid,$type)
    {
        $file = $filerepo->findOneBy(['filename_file'=>$type]);
        if(!$file){
            return new ApiResponse([$type],400,["Content-Type"=>"application/json"],'json','invalid file type'); 
        }
        $datafile = $filereqrepo->findOneBy(['file'=>$file->getId(),'request'=>$clientid,'attorney'=>null]);
        if(!$datafile){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','file not exist'); 
        }
        $filesystem = new Filesystem();
        $url = $datafile->getPath();
        $path = $filesystem->exists($url);
        if($path == false){
         return new ApiResponse([$path],404,["Content-Type"=>"application/json"],'json','file not found');     
        }
        $filecontent = file_get_contents($url);
        $response = new Response($filecontent); 
        $response->headers->set('Content-Type',mime_content_type($url));
        return   $response;
    }


     /**
     * @Route("/api/deletedocument/{id}", name="deleteDocument",methods={"PUT"})
     */
    public function deleteDocument(DataRequestRepository $reqrepo,DataRequestFileRepository $filereqrepo,$id, Request $request, ClientService $clientser){
        $entityManager = $this->EM;

        if(!$clientser->checkFileId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }

        $reqFile=$filereqrepo->findOneBy(['id'=>$id]);
        if(!$reqFile){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
            $deletfilepath=$reqFile->getPath();
            $filesystem = new Filesystem();
            $filesystem->remove(['symlink',$deletfilepath, 'activity.log']); 
            $entityManager->remove($reqFile);
            $entityManager->flush();
            return new ApiResponse([],200,["Content-Type"=>"application/json"],'json','file deleted');       
    }

     /**
     * @Route("/api/client/nomoreupdate/{id}/1", name="noMoreUpdate",methods={"PUT"})
     */
     public function noMoreUpdate(DataClientRepository $clientrepo,$id,ClientService $clientser,Request $request){
        $entityManager = $this->EM;
        if(!$clientser->checkClientId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $client = $clientrepo->findOneBy(['id'=>$id]);
        if(!$client){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
            $client->setNomore25(true);
            $entityManager->persist($client);
            $entityManager->flush();
            return new ApiResponse($client->getNomore25(),200,["Content-Type"=>"application/json"],'json','updated success');       
     }

    /**
     * @Route("/api/client/nomoreupdate/{id}/2", name="noMoreUpdate2",methods={"PUT"})
     */
    public function noMoreUpdate2(DataClientRepository $clientrepo,$id,ClientService $clientser,Request $request){
        $entityManager = $this->EM;
        if(!$clientser->checkClientId($id, $request)) {
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','Not allowed');
        }
        $client = $clientrepo->findOneBy(['id'=>$id]);
        if(!$client){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json','invalid id');       
        }
            $client->setNomore25(false);
            $entityManager->persist($client);
            $entityManager->flush();
            return new ApiResponse($client->getNomore25(),200,["Content-Type"=>"application/json"],'json','updated success');       
    }

    /**
     * @Route("/api/serverupload/{id}", name="serverupload")
     */
    public function serverupload(Request $request,FileUploader $uploader,$id,DocumentService $documentservice){
        $data  = $documentservice->documentCopy($id);
        return new ApiResponse([$data],200,["Content-Type"=>"application/json"],'json','success');  
    }

}