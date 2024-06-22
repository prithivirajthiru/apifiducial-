<?php

namespace App\Service;

use App\Entity\DataCTO;
use App\UtilsV3\Contact;
use App\UtilsV2\Attorney;
use App\Entity\DataClient;
use App\Utils\ApiResponse;
use App\Entity\DataRequest;
use App\Entity\DataAttorney;
use App\Entity\DataClientSab;
use App\Entity\DataRequestFile;
use App\Repository\DataClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DataContactRepository;
use App\Repository\DataAttorneyRepository;
use App\Repository\DataClientSabRepository;
use App\Repository\DataRequestFileRepository;
use App\Repository\DataUserspaceRepository;
use App\Repository\OptionsTabelRepository;
use App\Repository\RefFileRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class IntegrationService {

    private $EM;
    private $params;

    public function __construct(EntityManagerInterface $EM, ParameterBagInterface $params)
    {
        $this->EM     = $EM;
        $this->params = $params;
    }

    public function sendIntegrationData($requestid, $api, DataClientRepository $clientrepo, DataAttorneyRepository $attorneyrepo, DataUserspaceRepository $userspacerepo,  DataContactRepository $datacontrepo, OptionsTabelRepository $prod) 
    {

        $data = array();
		
		$data["Fiche"] = array();
		$data["Fiche"][] = [
            "Type"       => $requestid
        ];
		
        // Get Client Data #######################################
        $chkclient  = $clientrepo->findOneBy(['id' => $requestid]);

        $chkprod    = $prod->findOneBy(['client' => $requestid]);
        $sabprod    = "TMP";
        if ($chkprod !== null) {
            $sabprod = $chkprod->getProduct()->getSabcategory();            
        }

        $epa	  = ($chkclient->getEpa() !== null) ? $chkclient->getEpa()->getEpaCode() : "0000Z";
        $ca       = ($chkclient->getTypeclient()->getId() == 1) ? $chkclient->getTurnoverClient() : 0;
        $datca    = ($chkclient->getTypeclient()->getId() == 1) ? date("Y") - 1 : date("Y") - 1;
        $cheque   = ($chkprod->getCheque()) ? 1 : 0;
		
		$comtype  = ($chkclient->getCompanytype()->getId() == 1 && $chkclient->getTypeclient()->getId() == 2) ? 3 : 1;
        $comtype  = ($chkclient->getCompanytype()->getId() == 2) ? 3 : $comtype;				
		
		$data["Client"] = array();
        $data["Client"][] = [
            "nom"            => $this->strtoupperFr($chkclient->getCompanynameClient()), 
            "sigle"          => ($chkclient->getCaptionClient() != "") ? $this->strtoupperFr($chkclient->getCaptionClient()) : "", 
            "adresse"        => $this->strtoupperFr($chkclient->getAddressClient()), 
            "cp"             => $chkclient->getZipcodeClient(), 
            "ville"          => $this->strtoupperFr($chkclient->getCityClient()), 
            "pays"           => $chkclient->getCountry()->getCountryCodeLabel(), 
            "siret"          => $chkclient->getSiren(),
            "forme"          =>	$chkclient->getLegalform()->getLegalformCode(),
            "ape"            => $epa, 
            "caht"           => (string) round(($ca / 1000)),
            "annee_ca"       => $datca,
            "type"           => $comtype,
            "categorieoffre" => $sabprod,
			"cheque"		 => (string) $cheque
        ];

        // Get Shareholder Data #######################################
        $attorneys  = $attorneyrepo->findBy(['client' => $requestid]);
        
        foreach($attorneys as $attorney){
			if ($attorney->getIsshareholderAttorney() && $attorney->getActiveAttorney()) { 
                $type	  = ($attorney->getIsCompany() === true) ? "PM" : "PP";
                $rl		  = ($attorney->getIsmandatoryAttorney() === true) ? 1 : 0;
                if ($type == "PM") {
                    $data["Actionnaire"][] = [
                        "nom"               => $attorney->getCompanyName(), 
                        "nationalite"       => $attorney->getNationalityAttorney()->getCountryCodeLabel(),  
                        "siren"             => (string) $this->nulltostring($attorney->getRegisterNo()), 
                        "pct"               => $attorney->getPercentageAttorney(), 
                        "type"				=> $type
                    ];
                } else {
                    $civ	  = ($attorney->getCivilityAttorney()->getDescCivility() == 'Monsieur') ? "MR" : "MME";
                    $fonction = ($attorney->getFunction() != null) ? $attorney->getFunction()->getDescFunction() : "";		
                    
                    $phoneact       = $datacontrepo->findOneBy(['type_contact' => 4, 'attorney' => $attorney->getId()]);
                    $milact         = $datacontrepo->findOneBy(['type_contact' => 2, 'attorney' => $attorney->getId()]);
                    $phonemobact    = "";
                    $mailact        = "";
                    if ($phoneact) {
                        $phonemobact  = $phoneact->getValueContact();
                    }

                    if ($milact) {
                        $mailact = $milact->getValueContact();
                    }

                    $data["Actionnaire"][] = [
                        "nom"               => $this->strtoupperFr($attorney->getNameAttorney()), 
                        "prenom"            => $this->strtoupperFr($attorney->getSurnameAttorney()), 
						"birthname"         => $this->strtoupperFr($attorney->getBirthName()), 
                        "fonction"          => $fonction,
                        "naissance"         => date("Ymd", $attorney->getDatebirthAttorney()->getTimestamp()), 
                        "nationalite"       => $attorney->getNationalityAttorney()->getCountryCodeLabel(), 
                        "pays_naissance"    => $attorney->getCountrybirthAttorney()->getCountryCodeLabel(), 
                        "pays_residence"    => $attorney->getResidentcountryAttorney()->getCountryCodeLabel(),  
                        "siren"             => (string) $this->nulltostring($attorney->getSirenAttorney()), 
                        "pct"               => $attorney->getPercentageAttorney(), 
                        "civ"               => $civ,
                        "adresse"           => $this->strtoupperFr($this->nulltostring($attorney->getAddressAttorney())), 
                        "cp"                => $this->nulltostring($attorney->getZipcodeAttorney()), 
                        "ville"             => $this->strtoupperFr($this->nulltostring($attorney->getCityAttorney())), 
                        "fiscal"            => $this->nulltostring($attorney->getFiscalnumberAttorney()), 
                        "ville_naissance"   => $this->strtoupperFr($this->nulltostring($attorney->getPlacebirthAttorney())),
                        "rl"				=> (string) $rl,
                        "type"				=> $type,
                        "email"             => $mailact,
                        "mobile"            => $phonemobact
                    ];
                }
            }
        }
        
		$email    = $userspacerepo->findOneBy(['id_request' => $requestid]);
        $email_id = "";
        if ($email){
            $email_id = $email->getEmailUs();
        }
        
        // Get LegalRepresentant Data #######################################
        $attorney = $attorneyrepo->findOneBy(['client' => $requestid, 'ismandatory_attorney' => true]);
        $fonction = ($attorney->getFunction() != null) ? $attorney->getFunction()->getDescFunction() : "";
		$civ	  = ($attorney->getCivilityAttorney()->getDescCivility() == 'Monsieur') ? "MR" : "MME";
				
        $phone       = $datacontrepo->findOneBy(['type_contact' => 4, 'attorney' => $attorney->getId()]);
        $phonefix    = $datacontrepo->findOneBy(['type_contact' => 13, 'attorney' => $attorney->getId()]);
        $phonemob    = "";
        $phonefixe   = "";
        if ($phone) {
            $phonemob  = $phone->getValueContact();
        }

        if ($phonefix) {
            $phonefixe = $phonefix->getValueContact();
        }
        
        $data["Mandataire"][] = [
            "nom"               => $this->strtoupperFr($attorney->getNameAttorney()), 
            "prenom"            => $this->strtoupperFr($attorney->getSurnameAttorney()), 
			"birthname"         => $this->strtoupperFr($attorney->getBirthName()),
            "fonction"          => $fonction,
            "naissance"         => date("Ymd", $attorney->getDatebirthAttorney()->getTimestamp()), 
            "nationalite"       => $attorney->getNationalityAttorney()->getCountryCodeLabel(), 
            "pays_naissance"    => $attorney->getCountrybirthAttorney()->getCountryCodeLabel(), 
            "pays_residence"    => $attorney->getResidentcountryAttorney()->getCountryCodeLabel(),  
            "civ"               => $civ, 
            "adresse"           => $this->strtoupperFr($this->nulltostring($attorney->getAddressAttorney())), 
            "cp"                => $this->nulltostring($attorney->getZipcodeAttorney()), 
            "ville"             => $this->strtoupperFr($this->nulltostring($attorney->getCityAttorney())), 
            "ville_naissance"   => $this->strtoupperFr($attorney->getPlacebirthAttorney()),
			"pays"				=> $attorney->getResidentcountryAttorney()->getCountryCodeLabel(),
            "email"             => $email_id,
            "fixe"              => $phonefixe,
            "mobile"            => $phonemob
        ];

        $data["APPLI"] = [["code" => "EER"]];
		
        $retour = $api->decodeData($api->apiCto('/Fiducial/Entree', $data));
		//dd($retour);
        return $retour;
    }

	public function sendIntegrationFinal($requestid, $api, DataClientSabRepository $datasabrepo, DataClientRepository $clientrepo, DataRequestFileRepository $datareqfilerepo, RefFileRepository $reffilerepo) {
        $data           = array();
        $data["Client"] = array();

        $chkclient  = $clientrepo->findOneBy(['id' => $requestid]);       
        
        $epa	  = ($chkclient->getEpa() !== null) ? $chkclient->getEpa()->getEpaCode() : "0000Z";

        $datasab = $datasabrepo->findOneBy(['request' => $requestid]);
        if ($datasab !== null) {
            $data["Client"][] = [
                "Siret"        => $chkclient->getSiren(),
                "APE"          => $epa,
                "numfiche"     => $requestid
            ];
        }
        $data["APPLI"] = [["code" => "EER"]];		
		
        $retour = $api->decodeData($api->apiCto('/Fiducial/Majclient', $data));
		
		$data = array();
		if ($datasab !== null) {
            $data["Client"][] = [
                "client"     => $requestid
            ];
        }
        $data["APPLI"] = [["code" => "EER"]];        
		
        $retour = $api->decodeData($api->apiCto('/Fiducial/Entree2', $data));
		
		$reffile = $reffilerepo->findOneBy(['jsonkey' => 'contrat']);
		if (!$reffile) {
			return false;
		}
        $datarf = $datareqfilerepo->findOneBy(['file' => $reffile->getId(), 'request' => $requestid]);		
		if (!$datarf) {
			return false;
		}
        $this->sendDocInfo("251100EUR0".$datasab->getNumero()."001", "0".$datasab->getNumero(), $datarf->getFileUuid(), $api);
	
        return $retour;		
    }
	
	public function nulltostring($value) {
		if ($value == null) {
			return "";
		} 
		return $value;
	}
	
	public function sendDocInfo($cpt, $numcli, $cid, $api) {
        $data = [  
            "doc"     => [["date" => date("Ymd"), "cptsab" => $cpt, "agence" => 1, "desc" => "", "fichier" => $cid, 'type' => "ER"]],
            "appli"  => [["code" => "EER"]]
        ];

        $options = $data;
        if ($this->params->get('app.where') == 'internal') {
            $datas   = (array) $api->decodeData($api->apiCto("/ESAB/DOC", $options));
        }

        return true;
    }
	
	function strtoupperFr($string) {
	   $string = strtoupper($string);
	   $string = str_replace(
		  array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
		  array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
		  $string
	   );
	   return $string;
	}
}