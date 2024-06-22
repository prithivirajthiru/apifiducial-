<?php 

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
class FieldChecks{
    private $fields;
    private $client;
    public function getClientId(): ?int
    {
        return $this->client;
    }

    public function SetClientId(?int $client): self
    {
        $this->client = $client;

        return $this;
    }
    public function getFields(): ?array
    {
        return $this->fields;
    }

    public function setFields(?array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }
}