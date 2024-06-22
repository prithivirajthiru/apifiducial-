<?php

namespace App\Service;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DataLock;
use App\Entity\DataRequest;

class DataLockService{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }

    public function dataLockInsert($request){
        $encoders       =  [new JsonEncoder()];
        $normalizer     =  new ObjectNormalizer();
        $normalizers    =  array($normalizer,new DateTimeNormalizer());
        $serializer     =  new Serializer($normalizers ,$encoders);
        $content        =  $request->getContent();
        $data           =  $serializer->deserialize($content, DataLock::class, 'json');
        $requestrepo    =  $this->EM->getRepository(DataRequest::class);
        $datalockrepo   =  $this->EM->getRepository(DataLock::class);
        $request        =  $requestrepo->findOneBy(['id'=> $data->getRequestId()]);
        if (!$request) {
            return "request";
        }
        $datalock        =  $datalockrepo->findOneBy(['request'=> $request->getId(),'dlock'=>true]);
        if ($datalock) {
            return 'login';              
        }
        $datalock  =  new DataLock;
        $datalock  -> setRequest($request);
        $datalock  -> setDate(new \DateTime());
        $datalock  -> setDlock(true);
        $datalock  -> setUsername($data->getUserName());
        $this->EM->persist($datalock);
        $this->EM-> flush();
        return "ok";        
    }

    public function dataLockUpdate($username){
        $datalockrepo   =  $this->EM->getRepository(DataLock::class);
        $datalocks      =  $datalockrepo->findBy(['username'=> $username, 'dlock'=>true]);
        if (!$datalocks) {
            return "ko";
        }

        foreach ($datalocks as $datalock) {
            $datalock  -> setDlock(false);
            $this->EM->persist($datalock);
            $this->EM-> flush();
        }   
        return $username;
    }
}