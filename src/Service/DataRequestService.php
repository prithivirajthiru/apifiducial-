<?php

namespace App\Service;

use App\UtilsSer\DataReq;
use App\UtilsSer\Application;

use App\Service\ClientService;
use App\Service\CompanyTypeService;
use App\Repository\DataRequestRepository;
use App\Repository\DataClientRepository;
use App\Repository\RefTypeclientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\UtilsSer\Pagination;

class DataRequestService{

    public function getDataRequestUseClient(DataRequestRepository $datarepo,$clientid){
        $data=$datarepo->findOneBy(['client'=>$clientid]);
        $date=$data->getDateRequest();
        // return $date;
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize($data->getDateRequest());
        $dateupString = $serializer->normalize($data->getDateupdRequest());
        
        $datarequest=new DataReq();
        $datarequest->setId($data->getId());
        $datarequest->setDateRequest($dateAsString);
        $datarequest->setDateupdRequest($dateupString);

        return $datarequest;

     
    }


    public function getDataRequest(DataRequestRepository $datarepo,$id){
        $data=$datarepo->findOneBy(['id'=>$id]);
        $date=$data->getDateRequest();
        // return $date;
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize($data->getDateRequest());
        $dateupString = $serializer->normalize($data->getDateupdRequest());
        
        $datarequest=new DataReq();
        $datarequest->setId($data->getId());
        $datarequest->setDateRequest($dateAsString);
        $datarequest->setDateupdRequest($dateupString);

        return $datarequest;

     
    }

    public function getRequesrstatus(DataRequestRepository $datarepo,$requeststatus_id,DataClientRepository $clientrepo,$clientid,DataRequestService $datareqserv,ClientService $clientservice){

        $data=$datarepo->findOneBy(['requeststatus'=>$requeststatus_id]);
        // $date=$data->getDateRequest();
        // return $date;
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize($data->getDateRequest());
        $dateupString = $serializer->normalize($data->getDateupdRequest());
        
        $datarequest=new DataReq();
        $datarequest->setId($data->getId());
        $datarequest->setDateRequest($dateAsString);
        $datarequest->setDateupdRequest($dateupString);
        $client=$clientservice->getClientData($clientrepo,$clientid,$datareqserv,$datarepo);
        $datarequest->setClient($client);
        

        return $client;

     
    }

    public function getRequestStatusV1($datareq,DataRequestRepository $datarepo,DataClientRepository $clientrepo
                                       ,DataRequestService $datareqserv,ClientService $clientservice,CompanyTypeService $companytypeservice ,LegalformService $legalformservice, RefTypeclientRepository $clienttyperepo, $descstatus, $source, $isvir){

      

        
        
        $clientdata=$clientrepo->findOneBy(['id'=>$datareq->getClient()->getId()]);
        $typeClientid=$clientdata->getTypeclient();
        $legalformid=$clientdata->getLegalform();
        // $date=$data->getDateRequest();
        // return $date;
        $serializer = new Serializer(array(new DateTimeNormalizer()));
        $dateAsString = $serializer->normalize($datareq->getDateRequest());
        $dateupString = $serializer->normalize($datareq->getDateupdRequest());
        
        $datarequest=new DataReq();
        $datarequest->setId($datareq->getId());
        $datarequest->setDateRequest($dateAsString);
        $datarequest->setDateupdRequest($dateupString);
        $datarequest->setFirstOpening($datareq->getFirstOpening());
        $client=$clientservice->getClientData($clientrepo,$datareq->getClient()->getId(),$datareqserv,$datarepo);
        $companytype=null;
        $typeclient= null;
        if($typeClientid!=null){
            $typeclient=$clienttyperepo->findOneBy(['id' => $typeClientid]);
        }
        $legalform=null;
        if($legalformid!=null){
            $legalform=$legalformservice->getLegalformData($legalformid);
        }
        $representative=$companytypeservice->getRepresentatives($datareq->getClient()->getId());
        //return $representative;
        $application =new Application;
        $application->setClient($client);
        $application->setDataRequest($datarequest);
        $application->setCompanyType($companytype);
        $application->setTypeClient($typeclient);
        $application->setLegalfrom($legalform);
        $application->setRepresentative($representative);
        $application->setDescStatus($descstatus);
        $application->setSource($source);
        $application->setIsVir($isvir);
        return $application;       
    }
    public function getRequestStatusV1Pagination(&$pagination,DataRequestRepository $datarepo,$requeststatus_id,DataClientRepository $clientrepo,DataRequestService $datareqserv,ClientService $clientservice
    ,CompanyTypeService $companytypeservice ,LegalformService $legalformservice){
       
          /*
        $query = $repository->createQueryBuilder('p')
        ->where('p.price > :price')
        ->setParameter('price', '19.99')
        ->orderBy('p.price', 'ASC')
        ->getQuery();
        */
        //$pagination->setCount(10);

        //count
        $count=$dataReqs=$datarepo->createQueryBuilder('p')
        // ->setParameter('requeststatus   ', $requeststatus_id)
        ->select('count(p.id)')
        ->where('p.requeststatus  = :requeststatus') 
        ->setParameter(':requeststatus', $requeststatus_id)            
        ->getQuery()
        ->getSingleScalarResult();
        $pagination->setCount($count);


        $pagination->calculate();


        $dataReqs=$datarepo->createQueryBuilder('p')
        // ->setParameter('requeststatus   ', $requeststatus_id)
        ->where('p.requeststatus  = :requeststatus') 
        ->setParameter(':requeststatus', $requeststatus_id)
        ->setMaxResults($pagination->getLimit())
        ->setFirstResult($pagination->getSkip())       
        ->getQuery()
        ->getResult();

        //$qb->select('count(account.id)');

        $applications=array();
        if($dataReqs){
            foreach ($dataReqs as $key => $value) {
                $clientdata=$clientrepo->findOneBy(['id'=>$value->getClient()->getId()]);
                $companytypeid=$clientdata->getCompanyType();
                $legalformid=$clientdata->getLegalform();
                $application =new Application;
                //Applying DataRequest
                $datarequest=new DataReq();
                $serializer = new Serializer(array(new DateTimeNormalizer()));
                $datarequest->setId($value->getId());
                $datarequest->setDateRequest($serializer->normalize($value->getDateRequest()));
                $datarequest->setDateupdRequest($serializer->normalize($value->getDateupdRequest()));
                $client=$clientservice->getClientData($clientrepo,$value->getClient()->getId(),$datareqserv,$datarepo);
                $companytype=null;
                if($companytypeid!=null){
                   $companytype=$companytypeservice->getCompanyTypeData($companytypeid,$value->getClient()->getId());
                }
                 $legalform=null;
                 if($legalformid!=null){
                 $legalform=$legalformservice->getLegalformData($legalformid);
                 }
                 $representative=$companytypeservice->getRepresentatives($value->getClient()->getId());
                 //return $representative;
                 $application =new Application;
                 $application->setClient($client);
                 $application->setDataRequest($datarequest);
                 $application->setCompanyType($companytype);
                 $application->setLegalfrom($legalform);
                 $application->setRepresentative($representative);
                 return $application;
                         array_push($applications, $application);
            }
        }
        
               return $applications;
    }


}