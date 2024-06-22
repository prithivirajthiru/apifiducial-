<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class PersonalInfo  
{
private $attorney_id;
private $civility_attorney;
private $name_attorney;
private $surname_attorney;
private $datebirth_attorney;
private $placebirth_attorney;
private $countrybirth_attorney;
private $nationality_attorney;
private $address_attorney;
private $zipcode_attorney;
private $city_attorney;
private $residentcountry_attorney;
private $fiscalcountry_attorney;
private $american_attorney;
private $active_attorney;
private $client;
private $function;
private $siren_attorney;
private $iscompany;
private $isrepresentative;
private $percentage_attorney;
private $part_attorney;
private $legalform;
private $ismandatory_attorney;
private $isshareholder_attorney;
private $fiscalnumber_attorney;
private $contacts;

   public function getAttorneyId(): ?int
    {
        return $this->attorney_id;
    }

    public function setAttorneyId($attorney_id): self
    {
        $this->attorney_id = $attorney_id;

        return $this;
    }
   public function getCivilityAttorney(): ?int
    {
        return $this->civility_attorney;
    }

    public function setCivilityAttorney(?int $civility_attorney): self
    {
        $this->civility_attorney = $civility_attorney;

        return $this;
    }

    public function getSirenAttorney(): ?string
    {
        return $this->siren_attorney;
    }

    public function setSirenAttorney(?string $siren_attorney): self
    {
        $this->siren_attorney = $siren_attorney;

        return $this;
    }

    public function getClient(): ?int
    {
        return $this->client;
    }
    public function getPercentageAttorney(): ?float
    {
        return $this->percentage_attorney;
    }

    public function setPercentageAttorney(?float $percentage_attorney): self
    {
        $this->percentage_attorney = $percentage_attorney;

        return $this;
    }
    public function getPartAttorney(): ?int
    {
        return $this->part_attorney;
    }

    public function setPartAttorney(?int $part_attorney): self
    {
        $this->part_attorney = $part_attorney;

        return $this;
    }
    public function setClient($client): self
    {
        $this->client = $client;

        return $this;
    }
    public function getNameAttorney(): ?string
    {
        return $this->name_attorney;
    }

    public function setNameAttorney(?string $name_attorney): self
    {
        $this->name_attorney = $name_attorney;

        return $this;
    }

    public function getSurnameAttorney(): ?string
    {
        return $this->surname_attorney;
    }

    public function setSurnameAttorney(?string $surname_attorney): self
    {
        $this->surname_attorney = $surname_attorney;

        return $this;
    }

    public function getDatebirthAttorney(): ?string
    {
        return $this->datebirth_attorney;
    }

    public function setDatebirthAttorney(?string $datebirth_attorney): self
    {
        $this->datebirth_attorney = $datebirth_attorney;

        return $this;
    }

    public function getPlacebirthAttorney(): ?string
    {
        return $this->placebirth_attorney;
    }

    public function setPlacebirthAttorney($placebirth_attorney): self
    {
        $this->placebirth_attorney = $placebirth_attorney;

        return $this;
    }

    public function getCountrybirthAttorney(): ?int
    {
        return $this->countrybirth_attorney;
    }

    public function setCountrybirthAttorney($countrybirth_attorney): self
    {
        $this->countrybirth_attorney = $countrybirth_attorney;

        return $this;
    }

    public function getNationalityAttorney(): ?int
    {
        return $this->nationality_attorney;
    }

    public function setNationalityAttorney($nationality_attorney): self
    {
        $this->nationality_attorney = $nationality_attorney;

        return $this;
    }

    public function getAddressAttorney(): ?string
    {
        return $this->address_attorney;
    }

    public function setAddressAttorney(?string $address_attorney): self
    {
        $this->address_attorney = $address_attorney;

        return $this;
    }

    public function getZipcodeAttorney(): ?string
    {
        return $this->zipcode_attorney;
    }

    public function setZipcodeAttorney(?string $zipcode_attorney): self
    {
        $this->zipcode_attorney = $zipcode_attorney;

        return $this;
    }

    public function getCityAttorney(): ?string
    {
        return $this->city_attorney;
    }

    public function setCityAttorney(?string $city_attorney): self
    {
        $this->city_attorney = $city_attorney;

        return $this;
    }

    public function getResidentcountryAttorney(): ?int
    {
        return $this->residentcountry_attorney;
    }

    public function setResidentcountryAttorney($residentcountry_attorney): self
    {
        $this->residentcountry_attorney = $residentcountry_attorney;

        return $this;
    }

    public function getFiscalcountryAttorney(): ?int
    {
        return $this->fiscalcountry_attorney;
    }

    public function setFiscalcountryAttorney($fiscalcountry_attorney): self
    {
        $this->fiscalcountry_attorney = $fiscalcountry_attorney;

        return $this;
    }

    public function getAmericanAttorney(): ?bool
    {
        return $this->american_attorney;
    }

    public function setAmericanAttorney(?bool $american_attorney): self
    {
        $this->american_attorney = $american_attorney;

        return $this;
    }

    public function getActiveAttorney(): ?string
    {
        return $this->active_attorney;
    }

    public function setActiveAttorney(?string $active_attorney): self
    {
        $this->active_attorney = $active_attorney;

        return $this;
    }
    public function getFunction(): ?int
    {
        return $this->function;
    }

    public function setFunction($function): self
    {
        $this->function = $function;

        return $this;
    }

     public function getIscompany(): ?bool
    {
        return $this->iscompany;
    }

    public function setIscompany(?bool $iscompany): self
    {
        $this->iscompany = $iscompany;

        return $this;
    }

     public function getIsrepresentative(): ?bool
    {
        return $this->isrepresentative;
    }

    public function setIsrepresentative(?bool $isrepresentative): self
    {
        $this->isrepresentative = $isrepresentative;

        return $this;
    }
    public function getIsmandatoryAttorney(): ?bool
    {
        return $this->ismandatory_attorney;
    }

    public function setIsmandatoryAttorney(?bool $ismandatory_attorney): self
    {
        $this->ismandatory_attorney = $ismandatory_attorney;

        return $this;
    }

    public function getIsshareholderAttorney(): ?bool
    {
        return $this->isshareholder_attorney;
    }

    public function setIsshareholderAttorney(?bool $isshareholder_attorney): self
    {
        $this->isshareholder_attorney = $isshareholder_attorney;

        return $this;
    }
    public function getLegalform(): ?int
    {
        return $this->legalform;
    }

    public function setLegalform(?int $legalform): self
    {
        $this->legalform = $legalform;

        return $this;
    }

    public function getFiscalnumberAttorney(): ?string
    {
        return $this->fiscalnumber_attorney;
    }

    public function setFiscalnumberAttorney(?string $fiscalnumber_attorney): self
    {
        $this->fiscalnumber_attorney = $fiscalnumber_attorney;

        return $this;
    }
    public function getContacts(): ?array 
    {
        return $this->contacts;
    }

    public function setContacts(?array  $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }


}
