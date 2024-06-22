<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataProductOfferRepository")
 */
class DataProductOffer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataOffer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $offer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefProduct")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;
    private $productId;
    private $offerId;
    private $offerCode;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?RefProduct
    {
        return $this->product;
    }

    public function setProduct(?RefProduct $product): self
    {
        $this->product = $product;

        return $this;
    }
    public function getOfferId(): ?int
    {
        return $this->offerId;
    }

    public function setOfferId(?int $offerId): self
    {
        $this->offerId = $offerId;

        return $this;
    }

    public function getOfferCode(): ?string
    {
        return $this->offerCode;
    }

    public function setOfferCode(?string $offerCode): self
    {
        $this->offerCode = $offerCode;

        return $this;
    }
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }
}
