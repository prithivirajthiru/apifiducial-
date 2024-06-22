<?php

namespace App\Controller;

use Pikirasa\RSA;
use App\Utils\ApiResponse;
use App\Entity\DataUserspace;
use App\Repository\BoxRepository;
use App\Service\TokenGeneratService;
use App\Repository\DataRequestRepository;
use App\Repository\DataAttorneyRepository;
use App\Repository\DataUserspaceRepository;
use App\Repository\DataConnectionRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use \Swift_SmtpTransport,\Swift_Mailer,\Swift_Message;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class AuthController extends AbstractController
{

    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }
    
    /**
     * @Route("/api/validateotp/{email}/{userotp}", name="validateotpV1")
     */
    public function validateotpV1($email,$userotp,DataUserspaceRepository $userRepo,DataRequestRepository $requestRepo,DataAttorneyRepository $attorneyrepo,BoxRepository $boxrepo, TokenGeneratService $tokenservice,DataConnectionRepository $dataconnectionrepo)
   {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        
        $entityManager = $this->EM;
        $pub_key = file_get_contents('../pub.crt');
        $pri_key = file_get_contents('../pri.crt');
        $rsa = new RSA($pub_key, $pri_key);
        $email = $rsa->base64Decrypt(base64_decode($email));
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $chkemail = $userRepo->findOneBy(['email_us'=>$email]);
        $userid = $chkemail->getIdRequest()->getId();
        $request = $requestRepo->findOneBy(['id'=>$userid]);
        $client = $request->getClient()->getId();
        $attorney = $attorneyrepo->findOneBy(['client'=>$client,'ismandatory_attorney'=>true]);
        if($attorney){
            $attorney = $attorney->getId();
        }
        $dataconnection = $dataconnectionrepo->findOneBy(['clientId'=>$client,'status'=>'Active']);
        if(!$dataconnection){
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"no data founded");
        }
        if($userotp != $dataconnection->getOtp()){
            $dataconnection->setCount($dataconnection->getCount()+1);
            $entityManager->persist($dataconnection);
            $entityManager->flush();
            if($dataconnection->getCount()>2){
                $dataconnection->setStatus("Disabled");
                $entityManager->persist($dataconnection);
                $entityManager->flush();
                return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"count over");
            }
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"Invalid OTP!!!!");
        }
        $currentTime = time();
        $expirytime = $dataconnection->getExpiryTime()->getTimeStamp();

        if($currentTime>$expirytime){
            $dataconnection->setStatus("Disabled");
            $entityManager->persist($dataconnection);
            $entityManager->flush();
            return new ApiResponse([],400,["Content-Type"=>"application/json"],'json',"OTP EXPIRED!!!!");
        }
        $boxdata = $boxrepo->findOneBy(['client'=>$client]);
        if(!$boxdata){
            $box_id = null;

        }
        else{
            $box_id = $boxdata->getBoxId();
        }
        $token = $tokenservice->sessionInsert($client);
        if($userotp == $dataconnection->getOtp()){
            $dataconnection->setStatus("Disabled");
            $entityManager->persist($dataconnection);
            $entityManager->flush();
            return new ApiResponse(['client_id'=>$client,'attorney_id'=>$attorney,'box'=>$box_id, "token"=>$token],200,["Content-Type"=>"application/json"],'json',"success!!!!");
        }    
    }
   
}
