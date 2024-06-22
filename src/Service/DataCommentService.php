<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DataComment;
use App\Entity\DataClient;

class DataCommentService{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }

    public function insert($data,$entityManager) {
        $dataclientrepo = $this->EM->getRepository(DataClient::class);
        $chkdataclient  = $dataclientrepo->findOneBy(['id'=>$data->getClient()]);
        if(!$chkdataclient){
           return "client_id invalid";
        }
        $datacomment    =  new DataComment;
        $datacomment    -> setClient($chkdataclient);
        $datacomment    -> setLoginId($data->getLoginId());
        $datacomment    -> setComment($data->getComment());
        $datacomment    -> setTime(new \DateTime());
        $entityManager  -> persist($datacomment);
        $entityManager  -> flush();
        return true;
    }

    public function getDataComment($entityManager,$client_id) {
        $datacommrntrepo =$this->EM->getRepository(DataComment::class);
        $data =$datacommrntrepo->findBy(array('client' => $client_id),array('id' => 'ASC') );
        return $data;
    }
}