<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DataRequestRequststatus{
    private $id_request;
    private $id_requeststatus;
    private $date_request_requeststatus;
    private $code;
    private $login;

    

    public function getIdRequest(): ?int
    {
        return $this->id_request;
    }

    public function setIdRequest(?int $id_request): self
    {
        $this->id_request = $id_request;

        return $this;
    }
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getIdRequeststatus(): ?int
    {
        return $this->id_requeststatus;
    }

    public function setIdRequeststatus(?int $id_requeststatus): self
    {
        $this->id_requeststatus = $id_requeststatus;

        return $this;
    }

    public function getDateRequestRequeststatus(): ?string
    {
        return $this->date_request_requeststatus;
    }

    public function setDateRequestRequeststatus(string $date_request_requeststatus): self
    {
        $this->date_request_requeststatus = $date_request_requeststatus;

        return $this;
    }


}