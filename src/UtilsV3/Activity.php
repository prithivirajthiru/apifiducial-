<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\RefCountry;
use App\Entity\RefBank;
use App\Entity\RefLegalform;
use App\Entity\RefEpa;
use App\Entity\RefCompanyType;
use App\Entity\RefTypeClient;
use App\Entity\RefCivility;
use App\Entity\RefWhoClient;

class Activity{

    
    private $id;
    private $companyname_client;
    private $caption_client;
    private $address_client;
    private $zipcode_client;
    private $city_client;
    private $turnover_client;
    private $turnoveryear_client;
    private $turnovertype_client;
    private $iban_client;
    private $bic_client;
    private $shareamount_client;
    private $bank;
    private $legalform;
    private $epa;
    private $country;
    private $companytype;
    private $actdesc_client;
    private $otherbanque_client;
    private $typeclient;
    private $other_whereclient;
    private $other_wheresupplier;
    private $siren;
    private $civility;
    private $whoclient;
    private $dataClientWhereclients;
    private $dataClientWheresuppliers;
    private $dataCountryWhereclients;
    private $dataCountryWheresuppliers;
    private $whereclientlist;
    private $wheresupplierlist;
    private $isDataWhereClient;
    private $isDataWhereSupplier;
    private $strWhereClient;
    private $strWhereSupplier;
    private $acStringAddress;
    private $acValidationAddress;
    private $nomore25;
    private $dataFidArr;
    private $dataFidArrstring;
    public function getStrWhereClient(): ?string
    {
        return $this->strWhereClient;
    }

    public function setStrWhereClient(?string $strWhereClient): self
    {
        $this->strWhereClient = $strWhereClient;

        return $this;
    }

    public function getStrWhereSupplier(): ?string
    {
        return $this->strWhereSupplier;
    }

    public function setStrWhereSupplier(?string $strWhereSupplier): self
    {
        $this->strWhereSupplier = $strWhereSupplier;

        return $this;
    }


    public function getIsDataWhereclient(): ?string
    {
        return $this->isDataWhereClient;
    }

    public function setIsDataWhereclient(?string $isDataWhereClient): self
    {
        $this->isDataWhereClient = $isDataWhereClient;

        return $this;
    }
    public function getIsDataWhereSupplier(): ?string
    {
        return $this->isDataWhereSupplier;
    }

    public function setIsDataWhereSupplier(?string $isDataWhereSupplier): self
    {
        $this->isDataWhereSupplier = $isDataWhereSupplier;

        return $this;
    }

    
    public function getDataClientWhereclients(): ?array
    {
        return $this->dataClientWhereclients;
    }

    public function setDataClientWhereclients(?array $dataClientWhereclients): self
    {
        $this->dataClientWhereclients = $dataClientWhereclients;

        return $this;
    }
  
    public function getDataClientWheresuppliers(): ?array
    {
        return $this->dataClientWheresuppliers;
    }

    public function setDataClientWheresuppliers(?array $dataClientWheresuppliers): self
    {
        $this->dataClientWheresuppliers = $dataClientWheresuppliers;

        return $this;
    }
    public function getDataCountryWhereclients(): ?array
    {
        return $this->dataCountryWhereclients;
    }

    public function setDataCountryWhereclients(?array $dataCountryWhereclients): self
    {
        $this->dataCountryWhereclients = $dataCountryWhereclients;

        return $this;
    }
    public function getDataCountryWheresuppliers(): ?array
    {
        return $this->dataCountryWheresuppliers;
    }

    public function setDataCountryWheresuppliers(?array $dataCountryWheresuppliers): self
    {
        $this->dataCountryWheresuppliers = $dataCountryWheresuppliers;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

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

    public function getTurnoveryearClient(): ?\DateTimeInterface
    {
        return $this->turnoveryear_client;
    }

    public function setTurnoveryearClient(?\DateTimeInterface $turnoveryear_client): self
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

    public function getIbanClient(): ?string
    {
        return $this->iban_client;
    }

    public function setIbanClient(?string $iban_client): self
    {
        $this->iban_client = $iban_client;

        return $this;
    }

    public function getBicClient(): ?string
    {
        return $this->bic_client;
    }

    public function setBicClient(?string $bic_client): self
    {
        $this->bic_client = $bic_client;

        return $this;
    }
    
    
  

    public function getBank(): ?RefBank
    {
        return $this->bank;
    }

    public function setBank(?RefBank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getLegalform(): ?RefLegalForm
    {
        return $this->legalform;
    }

    public function setLegalform(?RefLegalForm $legalform): self
    {
        $this->legalform = $legalform;

        return $this;
    }

    public function getEpa(): ?RefEpa
    {
        return $this->epa;
    }

    public function setEpa(?RefEpa $epa): self
    {
        $this->epa = $epa;

        return $this;
    }

    public function getCountry(): ?RefCountry
    {
        return $this->country;
    }

    public function setCountry(?RefCountry $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCompanytype(): ?RefCompanyType
    {
        return $this->companytype;
    }

    public function setCompanytype(?RefCompanyType $companytype): self
    {
        $this->companytype = $companytype;

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

    public function getOtherbanqueClient(): ?bool
    {
        return $this->otherbanque_client;
    }

    public function setOtherbanqueClient(?bool $otherbanque_client): self
    {
        $this->otherbanque_client = $otherbanque_client;

        return $this;
    }

    public function getTypeclient(): ?RefTypeClient
    {
        return $this->typeclient;
    }

    public function setTypeclient(?RefTypeClient $typeclient): self
    {
        $this->typeclient = $typeclient;

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


    public function getCivility(): ?RefCivility
    {
        return $this->civility;
    }

    public function setCivility(?RefCivility $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getWhoclient(): ?RefWhoClient
    {
        return $this->whoclient;
    }

    public function setWhoclient(?RefWhoClient $whoclient): self
    {
        $this->whoclient = $whoclient;

        return $this;
    }
    public function getacStringAddress(): ?string
    {
        return $this->acStringAddress;
    }

    public function setacStringAddress(?string $acStringAddress): self
    {
        $this->acStringAddress = $acStringAddress;

        return $this;
    }
    public function getacValidationAddress(): ?bool
    {
        return $this->acValidationAddress;
    }

    public function setacValidationAddress(?bool $acValidationAddress): self
    {
        $this->acValidationAddress = $acValidationAddress;

        return $this;
    }
    public function getDataFidArr(): ?array
    {
        return $this->dataFidArr;
    }

    public function setDataFidArr(?array $dataFidArr): self
    {
        $this->dataFidArr = $dataFidArr;

        return $this;
    }
    // public function getWhereSupplierList():array
    // {
    //     return $this->wheresupplierlist;
    // }

    // public function setWhereSupplierList(array $wheresupplierlist): self
    // {
    //     $this->wheresupplierlist = $wheresupplierlist;

    //     return $this;
    // }
    // public function getWhereClientList():array
    // {
    //     return $this->whereclientlist;
    // }

    // public function setWhereClientList(array $whereclientlist): self
    // {
    //     $this->whereclientlist = $whereclientlist;

    //     return $this;
    // }
    public function getNomore25(): ?bool
    {
        return $this->nomore25;
    }

    public function setNomore25(?bool $nomore25): self
    {
        $this->nomore25 = $nomore25;

        return $this;
    }

    public function getDataFidArrstring(): ?string
    {
        return $this->dataFidArrstring;
    }

    public function setDataFidArrstring(?string $dataFidArrstring): self
    {
        $this->dataFidArrstring = $dataFidArrstring;

        return $this;
    }
}
