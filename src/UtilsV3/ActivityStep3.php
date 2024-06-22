<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ActivityStep3 {

    private $client;
    private $actdesc;
    private $other_whereclient;
    private $other_wheresupplier;
    private $other_wc_countrylist;
    private $other_ws_Countrylist;
    private $whereclientlist;
    private $wheresupplierlist;
    private $whoclient;
    private $turnover;
    

    public function getClient(): ?int
    {
        return $this->client;
    }

    public function setClient(?int $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getTurnover(): ?float
    {
        return $this->turnover;
    }

    public function setTurnover(?float $turnover): self
    {
        $this->turnover = $turnover; 
        return $this;
    }
    public function getActdesc(): ?string
    {
        return $this->actdesc;
    }

    public function setActdesc(?string $actdesc): self
    {
        $this->actdesc = $actdesc;
        return $this;
    }
    
    public function getOtherWhereclient(): ?bool
    {
        return $this->other_whereclient;
    }

    public function setOtherWhereclient(?bool $other_whereclient): self
    {
        $this->other_whereclient = $other_whereclient;
        return $this;
    }

    public function getOtherWheresupplier(): ?bool
    {
        return $this->other_wheresupplier;
    }

    public function setOtherWheresupplier(?bool $other_wheresupplier): self
    {
        $this->other_wheresupplier = $other_wheresupplier;
        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;
        return $this;
    }

    public function getWhereSupplierList():array
    {
        if($this->wheresupplierlist){
            return $this->wheresupplierlist;
        }else{
            return [];
        }
      
    }

    public function setWhereSupplierList(array $wheresupplierlist): self
    {
        $this->wheresupplierlist = $wheresupplierlist;
        return $this;
    }

    public function getWhereClientList():array
    {
        
        if($this->whereclientlist){
            return $this->whereclientlist;
        }else{
            return [];
        }

    }

    public function setWhereClientList(array $whereclientlist): self
    {
        $this->whereclientlist = $whereclientlist;
        return $this;
    }

    public function getOtherWSCountryList():array
    {
        return $this->other_ws_Countrylist;
    }

    public function setOtherWSCountryList(array $other_ws_Countrylist): self
    {
        $this->other_ws_Countrylist = $other_ws_Countrylist;
        return $this;
    }

    public function getOtherWCCountryList():array
    {
        return $this->other_wc_countrylist;
    }

    public function setOtherWCCountryList(array $other_wc_countrylist): self
    {
        $this->other_wc_countrylist = $other_wc_countrylist;
        return $this;
    }

    public function getWhoclient(): ?int
    {
        return $this->whoclient;
    }

    public function setWhoclient($whoclient): self
    {
        $this->whoclient = $whoclient;
        return $this;
    }
}