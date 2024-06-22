<?php

namespace App\UtilsV2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Step2{
      private $client_id;
      private $siren;
      private $companyname_client;
      private $caption_client;
      private $address_client;
      private $zipcode_client;
      private $city_client;
      private $turnover_client;
      private $turnoveryear_client;
      private $turnovertype_client;
      private $legalform;
      private $epa;
      private $actdesc_client;
      private $other_whereclient;
      private $other_wheresupplier;
      private $other_wc_countrylist;
      private $other_ws_Countrylist;
      private $whereclientlist;
      private $wheresupplierlist;
      private $whoclient;

    
    public function getWhoclient(): ?int
    {
        return $this->whoclient;
    }

    public function setWhoclient($whoclient): self
    {
        $this->whoclient = $whoclient;

        return $this;
    }
    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    public function setClientId($client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }


    public function getCompanynameClient(): ?string
    {
        return $this->companyname_client;
    }

    public function setCompanynameClient(?string $companyname_client): self
    {
        $this->companyname_client = $companyname_client;

        return $this;
    }
    public function getCaptionClient(): ?string
    {
        return $this->caption_client;
    }

    public function setCaptionClient(?string $caption_client): self
    {
        $this->caption_client = $caption_client;

        return $this;
    }
    public function getAddressClient(): ?string
    {
        return $this->address_client;
    }

    public function setAddressClient(?string $address_client): self
    {
        $this->address_client = $address_client;

        return $this;
    }

    public function getZipcodeClient(): ?string
    {
        return $this->zipcode_client;
    }

    public function setZipcodeClient(?string $zipcode_client): self
    {
        $this->zipcode_client = $zipcode_client;

        return $this;
    }

    public function getCityClient(): ?string
    {
        return $this->city_client;
    }

    public function setCityClient(?string $city_client): self
    {
        $this->city_client = $city_client;

        return $this;
    }

    public function getTurnoverClient(): ?float
    {
        return $this->turnover_client;
    }

    public function setTurnoverClient(?float $turnover_client): self
    {
        $this->turnover_client = $turnover_client;

        return $this;
    }

    public function getTurnoveryearClient(): ?string
    {
        return $this->turnoveryear_client;
    }

    public function setTurnoveryearClient(string $turnoveryear_client): self
    {
        $this->turnoveryear_client = $turnoveryear_client;

        return $this;
    }

    public function getTurnovertypeClient(): ?bool
    {
        return $this->turnovertype_client;
    }

    public function setTurnovertypeClient(?bool $turnovertype_client): self
    {
        $this->turnovertype_client = $turnovertype_client;

        return $this;
    }
    
    public function getLegalform(): ?int
    {
        return $this->legalform;
    }

    public function setLegalform($legalform): self
    {
        $this->legalform = $legalform;

        return $this;
    }

    public function getEpa(): ?int
    {
        return $this->epa;
    }

    public function setEpa($epa): self
    {
        $this->epa = $epa;

        return $this;
    }

    
    public function getActdescClient(): ?string
    {
        return $this->actdesc_client;
    }

    public function setActdescClient(?string $actdesc_client): self
    {
        $this->actdesc_client = $actdesc_client;

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
        return $this->wheresupplierlist;
    }

    public function setWhereSupplierList(array $wheresupplierlist): self
    {
        $this->wheresupplierlist = $wheresupplierlist;

        return $this;
    }
    public function getWhereClientList():array
    {
        return $this->whereclientlist;
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
}