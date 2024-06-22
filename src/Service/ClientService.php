<?php

namespace App\Service;

use App\Utils\ApiResponse;

use App\UtilsSer\ClientData;
use App\UtilsV3\PersonalData;
use App\Entity\DataAttorney;
use App\Entity\DataFieldIssue;
use App\Entity\DataRequestFile;
use App\Service\DataRequestService;
use App\Repository\DataClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DataRequestRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ClientService{

    private $EM;
    private $params;

    public function __construct(EntityManagerInterface $EM, ParameterBagInterface $params)
    {
        $this->EM     = $EM;
        $this->params = $params;
    }

    public function getClientData(DataClientRepository $clientrepo,$id,DataRequestService $datareqserv,DataRequestRepository $datarepo){
        $clientdata=$clientrepo->findOneBy(['id'=>$id]);
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize($clientdata->getTurnoveryearClient());
       
         $dataclient=new ClientData();
         $dataclient-> setId($clientdata->getId());
         $dataclient-> setCompanynameClient($clientdata->getCompanynameClient());
         $dataclient-> setCaptionClient($clientdata->getCaptionClient());
         $dataclient-> setAddressClient($clientdata->getAddressClient());
         $dataclient-> setZipcodeClient($clientdata->getZipcodeClient());
         $dataclient-> setCityClient($clientdata->getCityClient());
         $dataclient-> setTurnoverClient($clientdata->getTurnoverClient());
         $dataclient-> setTurnoveryearClient($dateAsString);
         $dataclient-> setTurnovertypeClient($clientdata->getTurnovertypeClient());
         $dataclient-> setIbanClient($clientdata->getIbanClient());
         $dataclient-> setBicClient($clientdata->getBicClient());
         $dataclient-> setShareamountClient($clientdata->getShareamountClient());
         $dataclient-> setActdescClient($clientdata->getActdescClient());
         $dataclient-> setOtherbanqueClient($clientdata->getOtherbanqueClient());
         $dataclient-> setSiren($clientdata->getSiren());
         $datareq=$datareqserv->getDataRequestUseClient($datarepo,$clientdata->getId());
        //  return $datareq ;
         $dataclient-> setDataRequest($datareq);
       

        return $dataclient ;
    }

    public function getAllClientData(DataClientRepository $clientrepo){
     
        $clientdata=$clientrepo->findAll();
        return  $clientdata;
    }

    public function getClientDetail($clientrepo,$id){
        $clientdata=$clientrepo->findOneBy(['id'=>$id]);
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize($clientdata->getTurnoveryearClient());
       
         $dataclient=new ClientData();
         $dataclient-> setId($clientdata->getId());
         $dataclient-> setCompanynameClient($clientdata->getCompanynameClient());
         $dataclient-> setCaptionClient($clientdata->getCaptionClient());
         $dataclient-> setAddressClient($clientdata->getAddressClient());
         $dataclient-> setZipcodeClient($clientdata->getZipcodeClient());
         $dataclient-> setCityClient($clientdata->getCityClient());
         $dataclient-> setTurnoverClient($clientdata->getTurnoverClient());
         $dataclient-> setTurnoveryearClient($dateAsString);
         $dataclient-> setTurnovertypeClient($clientdata->getTurnovertypeClient());
         $dataclient-> setIbanClient($clientdata->getIbanClient());
         $dataclient-> setBicClient($clientdata->getBicClient());
         $dataclient-> setShareamountClient($clientdata->getShareamountClient());
         $dataclient-> setActdescClient($clientdata->getActdescClient());
         $dataclient-> setOtherbanqueClient($clientdata->getOtherbanqueClient());
         $dataclient-> setSiren($clientdata->getSiren());
        

        return $dataclient ;
    }

    public function checkClientId($clientid, $request) {

        if (trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.back')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.webdev')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.web'))) {
            if($clientid != base64_decode($request->headers->get('tcli')) && $request->headers->get('tcli') !== null){
                return false;
            }
        }
        return true;
    }

    public function checkAttorneyId($attorneyid, $request) {

        if (trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.back')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.webdev')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.web'))) {
            $attorny = $this->EM->getRepository(DataAttorney::class);

            $chkattorny = $attorny->findOneBy(['id'=>$attorneyid]);
            if(!$chkattorny){
                return false;
            }
            
            if($chkattorny->getClient()->getId() != base64_decode($request->headers->get('tcli')) && $request->headers->get('tcli') !== null) {
                return false;
            }
        }
        return true;
    }

    public function checkFileId($fileid, $request) {

        if (trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.back')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.webdev')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.web'))) {
            $myfile = $this->EM->getRepository(DataRequestFile::class);

            $chkfile = $myfile->findOneBy(['id'=>$fileid]);
            if(!$myfile){
                return false;
            }
            
            if($chkfile->getRequest()->getId() != base64_decode($request->headers->get('tcli')) && $request->headers->get('tcli') !== null) {
                return false;
            }
        }
        return true;
    }

    public function checkIssueId($issueid, $request) {

        if (trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.back')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.webdev')) 
        && trim($_SERVER['REMOTE_ADDR']) != trim($this->params->get('app.web'))) {
            $myissue = $this->EM->getRepository(DataFieldIssue::class);

            $chkissue = $myissue->findOneBy(['id'=>$issueid]);
            if(!$myissue){
                return false;
            }
            
            if($chkissue->getClient()->getId() != base64_decode($request->headers->get('tcli')) && $request->headers->get('tcli') !== null) {
                return false;
            }
        }
        return true;
    }
    
}