<?php
namespace App\Utils;

use App\Entity\DataSession;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Jwt
{
    private $secret = "MY_PRIVATE_KEY";
    private $header;

    public function __construct()
    {
        $this->header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256',
        ]);
    }
    public function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
    public function getToken($data)
    {
        // Create the token payload

        $encoders = [new JsonEncoder()];

        $objNormaliser = new ObjectNormalizer();
        // $objNormaliser->setCircularReferenceLimit($cr);
        // $objNormaliser->setCircularReferenceHandler(function ($object) {

        //     return null;
        // });
        $normalizers = [$objNormaliser, new DateTimeNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $payload = $serializer->serialize($data, 'json');

        // Encode Header
        $base64UrlHeader = $this->base64UrlEncode($this->header);
        // Encode Payload
        $base64UrlPayload = $this->base64UrlEncode($payload);
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
        // Encode Signature to Base64Url String
        $base64UrlSignature = $this->base64UrlEncode($signature);
        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $jwt;
    }
    public function validateToken($jwt)
    {
        // split the token
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];
        // build a signature based on the header and payload using the secret
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);
        // verify it matches the signature provided in the token
        $encoders = [ new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers ,$encoders);
        $data = $serializer->deserialize($payload, DataSession::class, 'json');
        $current = time();
        $expiry =0;
        if($current>$data->getDate()){
            $expiry =1;
        }
         
        $signatureValid = ($base64UrlSignature === $signatureProvided);
        if($signatureValid==1 && $expiry==0){
             $signatureValid=1;
        }
        else{
            $signatureValid=0;
        }

        return $signatureValid;
    }
}
