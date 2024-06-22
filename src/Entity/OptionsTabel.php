<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionsTabelRepository")
 */
class OptionsTabel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefProduct")
     */
    private $product;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cheque;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tpc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient")
     */
    private $client;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cash;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?RefProduct
    {
        return $this->product;
    }

    public function setProduct(?RefProduct $product): self
    {
        $this->product = $product;

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

    public function getClient(): ?DataClient
    {
        return $this->client;
    }

    public function setClient(?DataClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCash(): ?bool
    {
        return $this->cash;
    }

    public function setCash(?bool $cash): self
    {
        $this->cash = $cash;

        return $this;
    }
}
