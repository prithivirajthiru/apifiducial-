<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class AverageCalculation{
    private $requestId;
    private $requestStatus;
    private $period;
    private $date;
    private $status;
    private $type;
    private $typeclient;
    public function getRequestId(): ?int
    {
        return $this->requestId;
    }

    public function setRequestId($requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }

    public function getRequestStatus(): ?array
    {
        return $this->requestStatus;
    }

    public function setRequestStatus(array $requestStatus): self
    {
        $this->requestStatus = $requestStatus;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getDate(): ?array
    {
        return $this->date;
    }

    public function setDate(array $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeclient(): ?string
    {
        return $this->typeclient;
    }

    public function setTypeclient(string $typeclient): self
    {
        $this->typeclient = $typeclient;

        return $this;
    }

}