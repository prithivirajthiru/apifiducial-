<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardsRepository")
 */
class Cards
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCardtype")
     */
    private $cardtype;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefDebittype")
     */
    private $debittype;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefPriority")
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCountry")
     * @ORM\JoinColumn(nullable=true)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient")
     */
    private $client;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telecode;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCountry(): ?RefCountry
    {
        return $this->country;
    }

    public function setCountry(?RefCountry $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getClient(): ?DataClient
    {
        return $this->client;
    }

    public function setClient(?DataClient $client): self
    {
        $this->client = $client;

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
