<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class StepV1{


    private $product;

    private $cheque;

    private $tpc;

    private $cards;

    private $companytype;

    private $email;


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProduct(): ?int
    {
        return $this->product;
    }

    public function setProduct(?int $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCompanyType(): ?int
    {
        return $this->companytype;
    }

    public function setCompanyType(?int $companytype): self
    {
        $this->companytype = $companytype;

        return $this;
    }


    public function getCheque(): ?bool
    {
        return $this->cheque;
    }

    public function setCheque(?bool $cheque): self
    {
        $this->cheque = $cheque;

        return $this;
    }

    public function getTpc(): ?bool
    {
        return $this->tpc;
    }

    public function setTpc(?bool $tpc): self
    {
        $this->tpc = $tpc;

        return $this;
    }

    public function getCards(): ?array
    {
        return $this->cards;
    }

    public function setCards(?array $cards): self
    {
        $this->cards = $cards;

        return $this;
    }
}

class Cardss{

    private $cardtype;

    private $debittype;

    private $priority;

    private $name;

    private $surname;

    private $country;
    
    private $telecode;
    private $telephone;

    public function getCardtype(): ?int
    {
        return $this->cardtype;
    }

    public function setCardtype(?int $cardtype): self
    {
        $this->cardtype = $cardtype;

        return $this;
    }

    public function getDebittype(): ?int
    {
        return $this->debittype;
    }

    public function setDebittype(?int $debittype): self
    {
        $this->debittype = $debittype;

        return $this;
    }


    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

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

    public function getCountry(): ?int
    {
        return $this->country;
    }

    public function setCountry(?int $country): self
    {
        $this->country = $country;

        return $this;
    }
    
public function getTelephone(): ?string
{
    return $this->telephone;
}

public function setTelephone(?string $telephone): self
{
    $this->telephone = $telephone;

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
}


