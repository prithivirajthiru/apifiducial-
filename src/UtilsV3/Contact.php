<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class Contact{
    private $email;
    private $phone;
    private $phonefix;
    private $telecode;
    private $telecodefix;
    private $countrycode;
    private $countrycodefix;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
    public function getPhonefix(): ?string
    {
        return $this->phonefix;
    }

    public function setPhonefix(?string $phonefix): self
    {
        $this->phonefix = $phonefix;

        return $this;
    }

    public function getTelecode(): ?string
    {
        return $this->telecode;
    }

    public function setTelecode(?string $telecode): self
    {
        $this->telecode = $telecode;

        return $this;
    }
    public function getTelecodeFix(): ?string
    {
        return $this->telecodefix;
    }

    public function setTelecodeFix(?string $telecodefix): self
    {
        $this->telecodefix = $telecodefix;

        return $this;
    }
    public function getCountrycode(): ?string
    {
        return $this->countrycode;
    }

    public function setCountrycode(?string $countrycode): self
    {
        $this->countrycode = $countrycode;

        return $this;
    }
    public function getcountrycodeFix(): ?string
    {
        return $this->countrycodefix;
    }

    public function setCountrycodeFix(?string $countrycodefix): self
    {
        $this->countrycodefix = $countrycodefix;

        return $this;
    }
}
