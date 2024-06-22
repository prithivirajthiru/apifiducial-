<?php
namespace App\Utils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiResponse extends Response{
    public function __construct($content = '', int $status = 200, array $headers = [],$type='json', $msg='',$ignoreType= [],float $remaining=0)
    {
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($content, $type, $msg) {
                return $content->getId();
            },
        ];
        $ObjectNormalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $normalizers = [ $ObjectNormalizer,new DateTimeNormalizer()];
        $serializer = new Serializer($normalizers ,[$encoder]);
       
        $jsonContent = $serializer->serialize(["data"=>$content,"msg"=>$msg,"status"=>$status,"remaining"=>$remaining], $type,['ignored_attributes' => $ignoreType]);
        
        $this->headers = new ResponseHeaderBag($headers);
        $this->setContent($jsonContent);
        $this->setStatusCode(200);
        $this->setProtocolVersion('1.0');
    }
}

