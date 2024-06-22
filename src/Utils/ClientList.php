<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class ClientList{
    private $clientId;
    private $typeClient;
    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getTypeClient(): ?int
    {
        return $this->typeClient;
    }

    public function setTypeClient(?int $typeClient): self
    {
        $this->typeClient = $typeClient;

        return $this;
    }
  


}