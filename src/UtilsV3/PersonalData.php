<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\RefCountry;
use App\Entity\RefFunction;
use App\Entity\DataClient;
use App\Entity\DataContact;
use App\Entity\RefCivility;


class PersonalData{
   

    private $id;
    private $civility_attorney;
    private $name_attorney;
    private $surname_attorney;
    private $birthName;
    private $datebirth_attorney;
    private $placebirth_attorney;
    private $countrybirth_attorney;
    private $nationality_attorney;
    private $address_attorney;
    private $zipcode_attorney;
    private $city_attorney;
    private $residentcountry_attorney;
    private $client;
    private $fiscalcountry_attorney;
    private $american_attorney;
    private $function;
    private $siren_attorney;
    private $part_attorney;
    private $percentage_attorney;
    private $active_attorney;
    private $iscompany;
    private $isrepresentative;
    private $contact;
    private $fiscalnumber;
    private $lrStringAddress;
    private $lrValidationAddress;
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getCivilityAttorney(): ?RefCivility
    {
        return $this->civility_attorney;
    }

    public function setCivilityAttorney(?RefCivility $civility_attorney): self
    {
        $this->civility_attorney = $civility_attorney;

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
    public function getBirthName(): ?string
    {
        return $this->birthName;
    }

    public function setBirthName(?string $birthName): self
    {
        $this->birthName = $birthName;

        return $this;
    }
    public function getDatebirthAttorney(): ?\DateTimeInterface
    {
        return $this->datebirth_attorney;
    }

    public function setDatebirthAttorney(?\DateTimeInterface $datebirth_attorney): self
    {
        $this->datebirth_attorney = $datebirth_attorney;

        return $this;
    }

    public function getPlacebirthAttorney(): ?string
    {
        return $this->placebirth_attorney;
    }

    public function setPlacebirthAttorney(?string $placebirth_attorney): self
    {
        $this->placebirth_attorney = $placebirth_attorney;

        return $this;
    }

    public function getCountrybirthAttorney(): ?RefCountry
    {
        return $this->countrybirth_attorney;
    }

    public function setCountrybirthAttorney(?RefCountry $countrybirth_attorney): self
    {
        $this->countrybirth_attorney = $countrybirth_attorney;

        return $this;
    }

    public function getNationalityAttorney(): ?RefCountry
    {
        return $this->nationality_attorney;
    }

    public function setNationalityAttorney(?RefCountry $nationality_attorney): self
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

    public function getResidentcountryAttorney(): ?RefCountry
    {
        return $this->residentcountry_attorney;
    }

    public function setResidentcountryAttorney(?RefCountry $residentcountry_attorney): self
    {
        $this->residentcountry_attorney = $residentcountry_attorney;

        return $this;
    }

    public function getFiscalcountryAttorney(): ?RefCountry
    {
        return $this->fiscalcountry_attorney;
    }

    public function setFiscalcountryAttorney(?RefCountry $fiscalcountry_attorney): self
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

    public function getFunction(): ?RefFunction
    {
        return $this->function;
    }

    public function setFunction(?RefFunction $function): self
    {
        $this->function = $function;

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

    public function getPartAttorney(): ?int
    {
        return $this->part_attorney;
    }

    public function setPartAttorney(?int $part_attorney): self
    {
        $this->part_attorney = $part_attorney;

        return $this;
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

    public function getActiveAttorney(): ?string
    {
        return $this->active_attorney;
    }

    public function setActiveAttorney(?string $active_attorney): self
    {
        $this->active_attorney = $active_attorney;

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
    public function getContact(): ?Contact
    {
        return $this->contact;
    }
    
    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;
    
        return $this;
    }
    public function getFiscalnumber(): ?string
    {
        return $this->fiscalnumber;
    }

    public function setFiscalnumber(?string $fiscalnumber): self
    {
        $this->fiscalnumber = $fiscalnumber;

        return $this;
    }
    public function getlrStringAddress(): ?string
    {
        return $this->lrStringAddress;
    }

    public function setlrStringAddress(?string $lrStringAddress): self
    {
        $this->lrStringAddress = $lrStringAddress;

        return $this;
    }

    public function getlrValidationAddress(): ?bool
    {
        return $this->lrValidationAddress;
    }

    public function setlrValidationAddress(?bool $lrValidationAddress): self
    {
        $this->lrValidationAddress = $lrValidationAddress;

        return $this;
    }

}

