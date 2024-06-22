<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use App\Repository\DataTemplateVariablesV1Repository;
use App\Repository\RefTableRepository;
use App\Repository\EmailActionRepository;
use App\Repository\DataTemplateRepository;
use App\Repository\RefEmailidRepository;
use App\Repository\DataActionVariableRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\ApiResponse;
use App\Repository\DataCommentRepository;
use App\Repository\RefEpaRepository;
use App\UtilsV3\UtilDataComment;
use App\Service\DataCommentService;
use Doctrine\ORM\EntityManagerInterface;
class DataCommetnController extends AbstractController
{

    protected $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM    = $EM;
    }

    /**
      * @Route("/api/datacomment/insert", name="datacomment",methods={"POST"})
     */
    public function insertDataComment(Request $request,DataCommentRepository $datacommentrepo,DataCommentService $datacommentservice)
    {
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $content = $request->getContent();
        $data = $serializer->deserialize($content,UtilDataComment::class, 'json');
        $entityManager = $this->EM;
        $result = $datacommentservice->insert($data,$entityManager);
        return new ApiResponse($result,200,["Content-Type"=>"application/json"],'json',"insert successfully!!!",['timezone']);
    } 

    /**
     * @Route("/api/datacomment/get/{client_id}", name="getdatacomment")
     */
    public function getDataComment(Request $request,DataCommentService $datacommentservice,$client_id, DataCommentRepository $datacommentrepo)
    {
        $encoders = [new JsonEncoder()];
        $normalizer = new ObjectNormalizer();
        
        $entityManager = $this->EM;
        
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers ,$encoders);
        $comments = $datacommentrepo->findBy(['client'=> $client_id], ['time' => 'ASC']);
        $data = array();
        foreach ($comments as $key => $comment) {
           $data[] = array('time' => $comment->getTime(), 'comment' => $comment->getComment(), 'loginId' => $comment->getLoginId());
        }
        return new ApiResponse($data,200,["Content-Type"=>"application/json"],'json',"successfully!!!",['timezone']);
    }

    }
