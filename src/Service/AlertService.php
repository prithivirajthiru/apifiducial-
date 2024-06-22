<?php

namespace App\Service;

use App\Entity\DataCTO;
use App\Utils\ApiResponse;
use App\Entity\DataRequest;
use App\Entity\DataAttorney;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AlertService {

    private $EM;
    private $params;

    public function __construct(EntityManagerInterface $EM, ParameterBagInterface $params)
    {
        $this->EM    = $EM;
        $this->params = $params;
    }

    public function getLastPassage($requestid) {
        $ctorepo  = $this->EM->getRepository(DataCTO::class);
        $chkcto   = $ctorepo->findOneBy(['request'=>$requestid], ['id' => 'DESC']);
        if (!$chkcto) {
            return null;
        }
        return $chkcto->getPassage();
    }

    public function apeAlert($requestid, $ape, $request, $api, $save, $passage) {				
        $requestrepo   =  $this->EM->getRepository(DataRequest::class);
        if ($save) {
            $chkrequest    =  $requestrepo->findOneBy(['id'=>$requestid]);
            if (!$chkrequest) {
                return new ApiResponse([],400,[],'json',"request_id not found");
            }
        }

        $responsedata  =  $this->apeJson($ape, $api);
        if ($save) {	
            foreach($responsedata as $key =>$values){
                foreach($values as $value){
                    $datacto =  new DataCTO;
                    $datacto -> setRequest($chkrequest);
                    $datacto -> setTypeAlert("APE");
                    $datacto -> setValue($value['code']);
                    $datacto -> setCode($value['Res']);
                    $datacto -> setAlertDesc($value['des']);
                    $datacto -> setDate(new \DateTime());
                    $datacto -> setApp("EER");
                    $datacto -> setPassage($passage);
                    $this->EM-> persist($datacto);
                    $this->EM-> flush();  
                }              
            }		
            return "success";
        }
        return $responsedata;
    }

    public function countryAlert($requestid, $alerttype, $country, $request, $api, $save, $libcountry, $passage) {
        $requestrepo   =  $this->EM->getRepository(DataRequest::class);
        if ($save) {
            $chkrequest    =  $requestrepo->findOneBy(['id'=>$requestid]);
            if(!$chkrequest){
                return new ApiResponse([],400,[],'json',"request_id not found");
            }
        }
		
        $responsedata  =  $this->countryJson($country, $api);   				
        if ($save) {
            foreach($responsedata as $key =>$values){
                foreach($values as $value){
                    $datacto =  new DataCTO;
                    $datacto -> setRequest($chkrequest);
                    $datacto -> setTypeAlert($alerttype);
                    $datacto -> setValue($value['code']." - ".$libcountry);
                    $datacto -> setCode($value['Res']);
                    $datacto -> setAlertDesc($value['des']);
                    $datacto -> setDate(new \DateTime());
                    $datacto -> setApp("EER");
                    $datacto -> setPassage($passage);
                    $this->EM-> persist($datacto);
                    $this->EM-> flush();  
                }              
            }
            return "success";
        }
        return $responsedata;
    }

    
    public function personaldataAlert($requestid, $nom, $prenom,$birthname, $attorneyid, $request, $api, $save, $passage, $type) {        
        $requestrepo   =  $this->EM->getRepository(DataRequest::class);
        $attorneyrepo  =  $this->EM->getRepository(DataAttorney::class);
        if ($save) {
            $chkattorney   =  $attorneyrepo->findOneBy(['id'=>$attorneyid]);
            $chkrequest    =  $requestrepo->findOneBy(['id'=>$requestid]);
            if(!$chkrequest){
                return new ApiResponse([],400,[],'json',"request_id not found");
            }
            if(!$chkattorney){
                return new ApiResponse([],400,[],'json',"attorney not found");
            }		
        }

        $responsedata  =  $this->personaldataJson($nom, $prenom,$birthname, $api);  
		
        if ($save) {
            foreach($responsedata as $key =>$values){
                foreach($values as $value){
                    $name=$value['nom']."  ".$value['prenom']." ".$value['birthname'];
                    $datacto =  new DataCTO;
                    $datacto -> setRequest($chkrequest);
                    $datacto -> setTypeAlert("Nom/Raison sociale ".$type);
                    $datacto -> setValue($name);
                    $datacto -> setCode($value['res']);
                    $datacto -> setAlertDesc($value['des']." ID : ".$value['idalert']);
                    $datacto -> setDate(new \DateTime());
                    $datacto -> setAttorney($chkattorney);
                    $datacto -> setApp("EER");
                    $datacto -> setPassage($passage);
                    $this->EM-> persist($datacto);
                    $this->EM-> flush();  
                }
            }
            return "success";
        }
        return $responsedata;
    }
	
	public function virementAlert($requestid, $account, $request, $api) {				
        $requestrepo   =  $this->EM->getRepository(DataRequest::class);
        $chkrequest    =  $requestrepo->findOneBy(['id'=>$requestid]);
        if (!$chkrequest) {
            return new ApiResponse([],400,[],'json',"request_id not found");
        }
        $responsedata  =  $this->virementJson($account, $api);		
        return $responsedata;
    }

    // ################################ The calls ####################################################
    public  function apeJson($ape, $api) {
        $data = [  
            "ape"   => [["code" => $ape]],
            "appli" => [["code" => "EER"]]
        ];

        $options = $data;
        if ($this->params->get('app.where') == 'internal') {
            $apeapi      = $api->decodeData($api->apiCto("CTO/APE", $options));                        
            $obj['code'] = $apeapi->ape[0]->code;
            $obj['Res']  = $apeapi->ape[0]->Res;
            $obj['des']  = $apeapi->ape[0]->des;
        } else {
            $apeapi      = array();
            $obj['code'] = $ape;
            $obj['Res']  = "OK";
            $obj['des']  = "000";            
        }

        switch ($obj['des']) {
            case "000":
                $obj['des'] = "APE non à risque";
                $obj['Res']  = "OK";
                $obj['des']  = "000";   
                break;
            case "001":
                $obj['des'] = "APE non existant";
                break;
            case "002":
                $obj['des'] = "Format non accepté";
                break;
            case "003":
                $obj['des'] = "APE non fourni";
                break;
            case "010":
                $obj['des'] = "APE à risque";
                break;
            case "020":
                $obj['des'] = "Profession reglementée";
                break;
            case "050":
                $obj['des'] = "APE interdit";
                break;
            default :
                $obj['des'] = "APE non à risque";
                $obj['Res']  = "OK";
                $obj['des']  = "000";   
                break;    
        }

        $res['ape']  = [$obj];
        return $res;
    }

    public  function countryJson($country, $api) {				
        $data = [  
            "pays"   => [["code" => $country]],
            "appli" => [["code" => "EER"]]
        ];
        $options = $data;
        if ($this->params->get('app.where') == 'internal') {
            $paysapi        = $api->decodeData($api->apiCto("CTO/PAYS", $options));
            $obj['code'] 	= $paysapi->pays[0]->code;
            $obj['Res'] 	= $paysapi->pays[0]->Res;
            $obj['des'] 	= $paysapi->pays[0]->des;        
        } else {
            $paysapi        = array();
            $obj['code'] 	= $country;
            $obj['Res'] 	= "OK";
            $obj['des'] 	= "000";
        }

        switch ($obj['des']) {
            case "000":
                $obj['des'] = "Pays non à risque";
                break;
            case "001":
                $obj['des'] = "Pays non existant";
                break;
            case "002":
                $obj['des'] = "Format non accepté";
                break;
            case "003":
                $obj['des'] = "Pays non fourni";
                break;
            case "020":
            case "021":
            case "022":
            case "023":
            case "024":
            case "025":
            case "026":
            case "027":
            case "028":
                $obj['des'] = "Pays à risque";
                break;            
        }

        $res['pays']    = [$obj];        		
        return $res;
    }

    public function personaldataJson($nom, $prenom,$birthname, $api) {		
        $data = [  
            "pppm"   => [["nom" => strtoupper($nom), "prenom" => strtoupper($prenom),"birthname" => strtoupper($birthname)]],
            "appli" => [["code" => "EER"]]
        ];
        $options = $data;
        if ($this->params->get('app.where') == 'internal') {
            $pppmapi        = $api->decodeData($api->apiCto("CTO/PPPM", $options));
			
            $obj['nom'] 	= $pppmapi->pppm[0]->nom;
            $obj['prenom'] 	= $pppmapi->pppm[0]->prenom;
            $obj['birthname'] = $pppmapi->pppm[0]->birthname;
            $obj['res']		= $pppmapi->pppm[0]->res;
            $obj['des']		= $pppmapi->pppm[0]->des;
            
            switch ($pppmapi->pppm[0]->des) {
				case '000100':
					$obj['des']		= $pppmapi->pppm[0]->des." - Personne à risque";
					break;
				case '000200':
					$obj['des']		= $pppmapi->pppm[0]->des." - Personne à risque";
					break;
				case '000300':
					$obj['des']		= $pppmapi->pppm[0]->des." - Personne politiquement exposée";
					break;
				case '000400':
					$obj['des']		= $pppmapi->pppm[0]->des." - Gel national";
					break;
				case '000999':
					$obj['des']		= $pppmapi->pppm[0]->des." - Liste OFAC";
					break;
			}

            $obj['idalert'] = $pppmapi->pppm[0]->idalrt;
        } else {
            $pppmapi        = array();
            $obj['nom'] 	= $nom;
            $obj['prenom'] 	= $prenom;
            $obj['birthname'] = $birthname;
            $obj['res']		= "OK";
            $obj['des']		= "000";
            $obj['idalert'] = "";
        }

        switch ($obj['des']) {
            case "000":
                $obj['des'] = "Personne non à risque";
                break;
            case "001":
                $obj['des'] = "Nom manquant";
                break;            
        }

        $res['pppm'] 	= [$obj];		
        return $res;
    }
	
	public function virementJson($account, $api) {		
        $data = [  
            "cpt"   => [["IBAN" => $account]],
            "appli" => [["code" => "EER"]]
        ];
        $options = $data;		
        if ($this->params->get('app.where') == 'internal') {
            $mouvapi = $api->decodeData($api->apiCto("Mouvements", $options));
        } else {
            $mouvapi = array();
        }
        
		$data = array();
        $i = 0;
        foreach ($mouvapi->cptok as $key => $value) {
            $data[$i]['IBANDO']   = $value->IBANDO;
            $data[$i]['DATE']     = $value->DATE;
            $data[$i]['NOMDO']    = $value->NOMDO;
            $data[$i]['BICDO']    = $value->BICDO;
            $data[$i]['MNTDO']    = $value->MNTDO;
            $data[$i]['REFDO']    = $value->REFDO;
            $i++;
        }
        return $data;
    }
}