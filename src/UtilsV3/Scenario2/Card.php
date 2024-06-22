<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\RefCardtype;
use App\Entity\RefDebittype;
use App\Entity\RefPriority;
use App\Entity\RefCountry;


class Card{

private $cardtype;

private $debittype;

private $priority;

private $name;

private $surname;

private $country; 

private $price;

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
public function getPrice(): ?float
{
    return $this->price;
}

public function setPrice(?float $price): self
{
    $this->price = $price;

    return $this;
}

public function getCardtype(): ?RefCardtype
{
    return $this->cardtype;
}

public function setCardtype(?RefCardtype $cardtype): self
{
    $this->cardtype = $cardtype;

    return $this;
}

public function getDebittype(): ?RefDebittype
{
    return $this->debittype;
}

public function setDebittype(?RefDebittype $debittype): self
{
    $this->debittype = $debittype;

    return $this;
}


public function getPriority(): ?RefPriority
{
    return $this->priority;
}

public function setPriority(?RefPriority $priority): self
{
    $this->priority = $priority;

    return $this;
}



public function getCountry(): ?RefCountry
{
    return $this->country;
}

public function setCountry(?RefCountry $country): self
{
    $this->country = $country;

    return $this;
}


}


