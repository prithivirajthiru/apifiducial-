<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefCdpricecalcRepository")
 */
class RefCdpricecalc
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefPriority")
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCardtype")
     */
    private $cardtype;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefDebittype")
     */
    private $debittype;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
