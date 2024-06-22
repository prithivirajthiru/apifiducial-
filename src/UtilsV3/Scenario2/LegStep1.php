<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

    class LegStep1{
        private $civility;
        private $name;
        private $surname; 
        private $contacts;
        private $client_id;
        private $ismandatory_attorney;
        private $isshareholder_attorney;
        private $attorney;
        private $boxId;
        private $birthName;

        public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility($civility): self
    {
        $this->civility = $civility;

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
    public function getContacts():array
    {
        if($this->contacts){
            return $this->contacts;
        }
        return [];
       
    }

    public function setContacts(array $contacts): self
    {
        $this->contacts = $contacts;

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
    public function getAttorney(): ?int
    {
        return $this->attorney;
    }

    public function setAttorney(?int $attorney): self
    {
        $this->attorney = $attorney;

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
    public function getBoxId(): ?string
    {
        return $this->boxId;
    }

    public function setBoxId(?string $boxId): self
    {
        $this->boxId = $boxId;

        return $this;
    }

}
   