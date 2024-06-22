<?php

namespace App\UtilsSer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\UtilsSer\ClientData;
use App\Entity\RefRequeststatus;
use App\Entity\RefRequestStatusOrder;


class DataReqReqStatus
{

    private $id;
    private $date;
    private $login;
    private $status;
    private $client_detail;
    private $refRequestStatusOrder;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getDateRequestRequeststatus(): ?string
    {
        return $this->date;
    }

    public function setDateRequestRequeststatus(string $date): self
    {
        $this->date = $date;

        return $this;
    }
   
    public function getIdRequeststatus(): ?RefRequeststatus
    {
        return $this->status;
    }

    public function setIdRequeststatus(?RefRequeststatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getClient(): ?ClientData
    {
        return $this->client_detail;
    }

    public function setClient(ClientData $client_detail): self
    {
        $this->client_detail = $client_detail;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }
    public function getRefRequestStatusOrder(): ?RefRequestStatusOrder
    {
        return $this->refRequestStatusOrder;
    }

    public function setRefRequestStatusOrder(?RefRequestStatusOrder $refRequestStatusOrder): self
    {
        $this->refRequestStatusOrder = $refRequestStatusOrder;

        return $this;
    }
    
}