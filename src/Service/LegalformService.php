<?php

namespace App\Service;

use App\UtilsSer\Legalform;
use App\Entity\RefLegalform;
use App\Entity\RefLanguage;
use App\Entity\RefLabel;
use Doctrine\ORM\EntityManagerInterface;

class LegalformService{
    private $EM;
    public function __construct(EntityManagerInterface $EM)
    {
        $this->EM = $EM;
    }
    public function getLegalformData($legalformid){
        $legalformrepo=$this->EM->getRepository(RefLegalform::class);
        $labelrepo=$this->EM->getRepository(RefLabel::class);
        $languagerepo=$this->EM->getRepository(RefLanguage::class);
        $data=$legalformrepo->findOneBy(['id'=>$legalformid]);
        if(!$data){
            return null;
        }
        $labledata=$labelrepo->findBy(['code_label'=>$data->getCodeLegalform()]);
        $languages=$languagerepo->findAll();
        $legalform=new Legalform();
        $legalform->setId($data->getId());
        $legalform->setCodeLegalform($data->getCodeLegalform());
        $legalform->setDescLegalform($data->getDescLegalform());
        $legalform->setActiveLegalform($data->getActiveLegalform());
        

        $refLabels=array();
                    foreach($languages as $language){
                        $label= $labelrepo->findOneBy(["lang_label"=>$language,"code_label"=>$data->getCodeLegalform()]);
                        if (!$label){
                            $refLabels[$language->getCodeLanguage()]="";
                            continue;
                            }
                         $refLabels[$language->getCodeLanguage()]=$label->getLabelLabel();
                       }
                       $legalform-> setRefLabels($refLabels);
                return $legalform;

    }

    }