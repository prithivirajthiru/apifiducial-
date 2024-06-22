<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class ActivityInfo{
    private $client_id;
    private $companyname_client;
    private $caption_client;
    private $address_client;
    private $zipcode_client;
    private $city_client;
    private $epa;
    private $siren;
    private $contacts;

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

    public function setCompanynameClient(string $companyname_client): self
    {
        $this->companyname_client = $companyname_client;

        return $this;
    }
    

     public function getCaptionClient(): ?string
    {
        return $this->caption_client;
    }

    public function setCaptionClient(string $caption_client): self
    {
        $this->caption_client = $caption_client;

        return $this;
    }

     public function getAddressClient(): ?string
    {
        return $this->address_client;
    }

    public function setAddressClient(string $address_client): self
    {
        $this->address_client = $address_client;

        return $this;
    }

    public function getZipCodeClient(): ?string
    {
        return $this->zipcode_client;
    }

    public function setZipCodeClient(string $zipcode_client): self
    {
        $this->zipcode_client = $zipcode_client;

        return $this;
    }
    public function getCityClient(): ?string
    {
        return $this->city_client;
    }

    public function setCityClient(string $city_client): self
    {
        $this->city_client = $city_client;

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
    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }
     public function getContacts():array
    {
        return $this->contacts;
    }

    public function setContacts(array $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }
}
class Contacts{
    private $id;
    private $type_contact;
    private $value_contact;
   

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId( $id): self
    {
        $this->id = $id;

        return $this;
    }

     public function getTypeContact(): ?int
    {
        return $this->type_contact;
    }

    public function setTypeContact( $type_contact): self
    {
        $this->type_contact = $type_contact;

        return $this;
    }

    public function getValueContact(): ?string
    {
        return $this->value_contact;
    }

    public function setValueContact( $value_contact): self
    {
        $this->value_contact = $value_contact;

        return $this;
    }

}