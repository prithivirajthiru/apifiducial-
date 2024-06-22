<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataClientOfferRepository")
 */
class DataClientOffer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataOffer")
     */
    private $offer;

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

    public function getOffer(): ?DataOffer
    {
        return $this->offer;
    }

    public function setOffer(?DataOffer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }
}
