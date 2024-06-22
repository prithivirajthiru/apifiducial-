<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataClientWheresupplierRepository")
 */
class DataClientWheresupplier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient", inversedBy="dataClientWheresuppliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefWheresupplier", inversedBy="dataClientWheresuppliers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wheresupplier;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getWheresupplier(): ?RefWheresupplier
    {
        return $this->wheresupplier;
    }

    public function setWheresupplier(?RefWheresupplier $wheresupplier): self
    {
        $this->wheresupplier = $wheresupplier;

        return $this;
    }
}
