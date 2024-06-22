<?php

namespace App\UtilsSer;

use App\Entity\RefTypeclient;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\UtilsSer\DataReq;
use App\UtilsSer\ClientData;
use App\UtilsSer\CompanyType;
use App\UtilsSer\Legalform;
use App\UtilsSer\Representative;
use phpDocumentor\Reflection\Types\Boolean;

class Application
{

    private $datarequest;
    private $client;
    private $companytype;
    private $typeclient;
    private $legalform;
    private $representative;
    private $descstatus;
    private $source;
    private $isvir;
 
    public function getDataRequest(): ?DataReq
    {
        return $this->datarequest;
    }

    public function setDataRequest(?DataReq $datarequest): self
    {
        $this->datarequest = $datarequest;

        return $this;
    }

    public function getClient(): ?ClientData
    {
        return $this->client;
    }

    public function setClient(?ClientData $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCompanyType(): ?CompanyType
    {
        return $this->companytype;
    }

    public function setCompanyType(?CompanyType $companytype): self
    {
        $this->companytype = $companytype;

        return $this;
    }
    public function getTypeClient(): ?RefTypeclient
    {
        return $this->typeclient;
    }
    public function setTypeClient(?RefTypeclient $typeclient): self
    {
        $this->typeclient = $typeclient;

        return $this;
    }
    public function getLegalfrom(): ?Legalform
    {
        return $this->Legalform;
    }

    public function setLegalfrom(?Legalform $Legalform): self
    {
        $this->Legalform = $Legalform;

        return $this;
    }

    public function getRepresentative(): ?Representative
    {
        return $this->representative;
    }

    public function setRepresentative(?Representative $representative): self
    {
        $this->representative = $representative;

        return $this;
    }

    public function setDescStatus(?String $descstatus): self
    {
        $this->descstatus = $descstatus;

        return $this;
    }

    public function getDescStatus(): ?String
    {
        return $this->descstatus;
    }


    public function setSource(?String $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getSource(): ?String
    {
        return $this->source;
    }

    public function setIsVir(?String $isvir): self
    {
        $this->isvir = $isvir;

        return $this;
    }

    public function getIsVir(): ?String
    {
        return $this->isvir;
    }


}
    