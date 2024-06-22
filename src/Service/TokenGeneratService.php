<?php
namespace App\Service;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\DataSession;
use App\Utils\ApiResponse;
use App\Utils\Jwt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class TokenGeneratService 
{

    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }

    function sessionInsert($request_id){

        $datasession = new DataSession;
        $datasession->setRequestId($request_id);
        $time =time();
        $time=$time+(3600*4);
        $datasession->setDate($time);
        $jwt=new Jwt();
        $token = $jwt->getToken($datasession);
        $datasession->setToken($token);
        $datasession->setCreatedOn(new \DateTime());
        $datasession->setLastUpdate((new \DateTime())->setTimestamp($time));
        $this->EM->persist($datasession);
        $this->EM->flush();
        // $cache = new FilesystemAdapter();
        // $cache_token = $cache->getItem('TOKEN_'.$token);
        // $cache_token->expiresAfter(120);
        // $cache_token->set($request_id);
        // $cache->save($cache_token);
        return $datasession->getToken();

    }
}