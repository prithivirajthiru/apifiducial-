<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class Init  
{
  
      private $companytype_id;
      private $clienttype_id;
      private $client_id;
      private $legalform_id;

     public function getCompanytypeId(): ?string
    {
        return $this->companytype_id;
    }

    public function setCompanytypeId(string $companytype_id): self
    {
        $this->companytype_id = $companytype_id;

        return $this;
    }



    public function getClienttypeId(): ?string
    {
        return $this->clienttype_id;
    }

    public function setClienttypeId(string $clienttype_id): self
    {
        $this->clienttype_id = $clienttype_id;

        return $this;
    }



     public function getClientId(): ?string
    {
        return $this->client_id;
    }

    public function setClientId(string $client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }
    public function getLegalformId(): ?string
    {
        return $this->legalform_id;
    }

    public function setLegalformId(string $legalform_id): self
    {
        $this->legalform_id = $legalform_id;

        return $this;
    }
}
