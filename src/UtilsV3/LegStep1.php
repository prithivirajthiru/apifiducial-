<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class LegStep1 {

    private $civility;
    private $name;
    private $surname; 
    private $contacts;
    private $client;

    public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility($civility): self
    {
        $this->civility = $civility;
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
        return $this->contacts;
    }

    public function setContacts(array $contacts): self
    {
        $this->contacts = $contacts;
        return $this;
    }

    public function getClient(): ?int
    {
        return $this->client;
    }

    public function setClient($client): self
    {
        $this->client = $client;
        return $this;
    }
}

class Contac {

    private $type_contact;
    private $value_contact;

    public function getTypeContact(): ?int
    {
        return $this->type_contact;
    }

    public function setTypeContact($type_contact): self
    {
        $this->type_contact = $type_contact;
        return $this;
    }

    public function getValueContact(): ?string
    {
        return $this->value_contact;
    }

    public function setValueContact($value_contact): self
    {
        $this->value_contact = $value_contact;
        return $this;
    }
}