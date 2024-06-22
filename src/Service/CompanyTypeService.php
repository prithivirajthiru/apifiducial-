<?php
namespace App\Service;

use App\UtilsSer\CompanyType;
use App\UtilsSer\Representative;
use App\Entity\RefCompanyType;
use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use App\Entity\DataAttorney;
use Doctrine\ORM\EntityManagerInterface;

class CompanyTypeService {

    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }

    public function getCompanyTypeData($companytypeid) {
        $companytyperepo = $this->EM->getRepository(RefCompanyType::class);
        $labelrepo       = $this->EM->getRepository(RefLabel::class);
        $languagerepo    = $this->EM->getRepository(RefLanguage::class);
        $data            = $companytyperepo->findOneBy(['id'=>$companytypeid]);
        if (!$data) {
            return null;
        }
        $labledata       = $labelrepo->findBy(['code_label'=>$data->getCodeCompanytype()]);
        $languages       = $languagerepo->findAll();
        $companytype     = new CompanyType();
        $companytype->setId($data->getId());
        $companytype->setCodeCompanytype($data->getCodeCompanytype());
        $companytype->setDescCompanytype($data->getDescCompanytype());
        $companytype->setActiveCompanytype($data->getActiveCompanytype());
        
        $refLabels = array();
        foreach($languages as $language) {
            $label = $labelrepo->findOneBy(["lang_label"=>$language,"code_label"=>$data->getCodeCompanytype()]);
            if (!$label) {
                $refLabels[$language->getCodeLanguage()] = "";
                continue;
            }
            $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
        }
        $companytype->setRefLabels($refLabels);
        return $companytype;
    }

    public function getRepresentatives($clientid){
        $attornyrepo    = $this->EM->getRepository(DataAttorney::class); 
        $data           = $attornyrepo->findOneBy(['client'=>$clientid,'ismandatory_attorney'=>true]);
        $representative = new Representative();
        if ($data) {
            $representative->setNameAttorney($data->getNameAttorney()?$data->getNameAttorney():"");
            $representative->setSurnameAttorney($data->getSurnameAttorney()?$data->getSurnameAttorney():"");
            $representative->setBirthName($data->getBirthName()?$data->getBirthName():"");
            return $representative;
        } else {
            return null;
        }
    }
}