<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class BankInfo{
    private $iban_client;
    private $bic_client;
    private $bank;
    private $client_id;

    public function getBank(): ?int
    {
        return $this->bank;
    }

    public function setBank($bank): self
    {
        $this->bank = $bank;

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
    
     public function getClientId(): ?string
    {
        return $this->client_id;
    }

    public function setClientId(string $client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }
}  