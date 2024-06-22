<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class RequestCount  
{
    private $date;
    private $prospect;
    private $newdemand;
    private $accept;
    public function getdate(): ?string
    {
        return $this->date;
    }

    public function setdate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getprospect(): ?string
    {
        return $this->prospect;
    }

    public function setprospect(?string $prospect): self
    {
        $this->prospect = $prospect;

        return $this;
    }

    public function getnewdemand(): ?string
    {
        return $this->newdemand;
    }

    public function setnewdemand(?string $newdemand): self
    {
        $this->newdemand = $newdemand;

        return $this;
    }

    public function getaccept(): ?string
    {
        return $this->accept;
    }

    public function setaccept(?string $accept): self
    {
        $this->accept = $accept;

        return $this;
    }
}