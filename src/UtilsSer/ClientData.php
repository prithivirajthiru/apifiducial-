<?php

namespace App\UtilsSer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\UtilsSer\DataReq;

class ClientData
{
    
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
    private $dataRequest;
    private $companytype;
    private $actdesc_client;
    private $otherbanque_client;
    private $siren;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
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

    public function getTurnoveryearClient(): ?string
    {
        return $this->turnoveryear_client;
    }

    public function setTurnoveryearClient(?string $turnoveryear_client): self
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

    public function getShareamountClient(): ?float
    {
        return $this->shareamount_client;
    }

    public function setShareamountClient(?float $shareamount_client): self
    {
        $this->shareamount_client = $shareamount_client;

        return $this;
    }

  

    public function getDataRequest(): ?DataReq
    {
        return $this->dataRequest;
    }

    public function setDataRequest(DataReq $dataRequest): self
    {
        $this->dataRequest = $dataRequest;

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


    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }




}
