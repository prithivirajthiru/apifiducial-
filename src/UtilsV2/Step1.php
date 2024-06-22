<?php

namespace App\UtilsV2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Step1{
    private $client_id;
    private $civility;
    private $typeclient;
    private $companytype;
    private $name;
    private $surname;
    private $isleader;
    private $contacts;
    private $email;
    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    public function setClientId($client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }
    public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility($civility): self
    {
        $this->civility = $civility;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

     public function getTypeClient(): ?int
    {
        return $this->typeclient;
    }

    public function setTypeClient($typeclient): self
    {
        $this->typeclient = $typeclient;

        return $this;
    }

     public function getCompanyType(): ?int
    {
        return $this->companytype;
    }

    public function setCompanyType($companytype): self
    {
        $this->companytype = $companytype;

        return $this;
    }


     public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

     public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getIsleader(): ?bool
    {
        return $this->isleader;
    }

    public function setIsleader(?bool $isleader): self
    {
        $this->isleader = $isleader;

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
class Contac{

    private $type_contact;
    private $value_contact;

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