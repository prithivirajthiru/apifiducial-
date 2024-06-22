<?php

namespace App\UtilsV2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Step3{
    private $attorney_id;
    private $client_id;
    private $civility_attorney;
    private $name_attorney;
    private $surname_attorney;
    private $datebirth_attorney;
    private $placebirth_attorney;
    private $nationality_attorney;
    private $fiscalcountry_attorney;
    private $american_attorney;

    public function getAttorneyId(): ?int
    {
        return $this->attorney_id;
    }

    public function setAttorneyId( $attorney_id): self
    {
        $this->attorney_id = $attorney_id;

        return $this;
    }

    public function getCivilityAttorney(): ?string
    {
        return $this->civility_attorney;
    }

    public function setCivilityAttorney(?string $civility_attorney): self
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

    public function getDatebirthAttorney(): ?string
    {
        return $this->datebirth_attorney;
    }

    public function setDatebirthAttorney($datebirth_attorney): self
    {
        $this->datebirth_attorney = $datebirth_attorney;

        return $this;
    }

    public function getPlacebirthAttorney(): ?int
    {
        return $this->placebirth_attorney;
    }

    public function setPlacebirthAttorney($placebirth_attorney): self
    {
        $this->placebirth_attorney = $placebirth_attorney;

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
    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    public function setClientId($client_id): self
    {
        $this->client_id = $client_id;

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

}