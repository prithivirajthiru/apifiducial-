<?php
namespace App\Utils;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\json_encode;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ApiHelper
{
    private $params;
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function eloquaPost($route, $data): Response
    {
    //    $client = new \GuzzleHttp\Client();
       $client = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.eloqua')."/"]);
       $response = $client->post($route,['json' => $data,'headers'=>['Authorization'=>'Basic RmlkdWNpYWxJbmZvcm1hdGlxdWVcQmFucXVlLlRlc3Q6RmlkMjAyMCo=']]);
       return $response;
    }
    
    public function apiPost($route, $data): Response
    {
        $client     = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.contralia')."/Contralia/api/v2/"]);        
        $response   = $client->post( $route,['form_params' => $data,'auth' => ['themis_banque-test', 'Fiducial2020#']]);
        return $response;
    }
    public function apiGet($route, $data): Response
    {
        $client     = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.contralia')."/Contralia/api/v2/"]);        
        $response   = $client->get( $route,['form_params' => $data,'auth' => ['themis_banque-test', 'Fiducial2020#']]);
        return $response;
    }
    public function apiFilePost($route, $data): Response
    {
        $client     = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.contralia')."/Contralia/api/v2/"]);        
        $response   = $client->post( $route,['multipart' => $data,'auth' => ['themis_banque-test', 'Fiducial2020#']]);
        return $response;
    }
    public function eDocPost($route, $data): Response
    {
       $client = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.contralia')."/eDoc/api/"]);
       $response = $client->post( $route,['form_params' => $data,'auth' => ['themis_banque-test', 'Fiducial2020#']]);
        return $response;
    }
    public function apiCto($uri, $data) : Response
    {
        $client     = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.thmapi')."/"]);        
        $response   = $client->post($uri, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
        return $response;
    }
    public function apiUpd($uri, $data) : Response
    {
        $client     = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.thmapi')."/"]);        
        $response   = $client->get($uri, ['headers' => ['Content-Type' => 'application/json']]);
        return $response;
    }

    public function apiGetBack($uri) : Response 
    {
        $client     = new \GuzzleHttp\Client(["base_uri" => $this->params->get('app.back')."/", "verify" => false]);        
        $response   = $client->get($uri, []);
        return $response;
    }

    public function decodeData(Response $data) 
    {
        return json_decode($data->getBody());
    }

    public function inseeApiPost($route, $data) {

        $client = new \GuzzleHttp\Client(["base_uri" => "https://api.insee.fr/", 
		'proxy' => 'http://192.168.224.4:8080', 'verify' => false
		]);       
        try {       
            $response = $client->post($route, $data);
            return json_decode($response->getBody());                
        } catch (Exception $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
            return false;
        }
    }

    public function inseeApiGet($route, $data) {

        $client = new \GuzzleHttp\Client(["base_uri" => "https://api.insee.fr/", 
		'proxy' => 'http://192.168.224.4:8080', 'verify' => false
		]);       
        try {       
            $response = $client->get($route, $data);
            return json_decode($response->getBody());                
        } catch (Exception $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br/>";
            return false;
        }
    }
  
}
