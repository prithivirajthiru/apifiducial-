<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefOfferRepository")
 */
class RefOffer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_offer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $business;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $visa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buisDI;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buisDD;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buisSUPDI;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buisSUPDD;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $VISADI;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $VISADD;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $offer_number;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $VISASUPDI;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $VISASUPDD;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_offer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $active_offer;
    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescOffer(): ?string
    {
        return $this->desc_offer;
    }

    public function setDescOffer(?string $desc_offer): self
    {
        $this->desc_offer = $desc_offer;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBusiness(): ?string
    {
        return $this->business;
    }

    public function setBusiness(?string $business): self
    {
        $this->business = $business;

        return $this;
    }

    public function getVisa(): ?string
    {
        return $this->visa;
    }

    public function setVisa(?string $visa): self
    {
        $this->visa = $visa;

        return $this;
    }

    public function getBuisDI(): ?int
    {
        return $this->buisDI;
    }

    public function setBuisDI(?int $buisDI): self
    {
        $this->buisDI = $buisDI;

        return $this;
    }

    public function getBuisDD(): ?int
    {
        return $this->buisDD;
    }

    public function setBuisDD(?int $buisDD): self
    {
        $this->buisDD = $buisDD;

        return $this;
    }

    public function getBuisSUPDI(): ?int
    {
        return $this->buisSUPDI;
    }

    public function setBuisSUPDI(?int $buisSUPDI): self
    {
        $this->buisSUPDI = $buisSUPDI;

        return $this;
    }

    public function getBuisSUPDD(): ?int
    {
        return $this->buisSUPDD;
    }

    public function setBuisSUPDD(?int $buisSUPDD): self
    {
        $this->buisSUPDD = $buisSUPDD;

        return $this;
    }

    public function getVISADI(): ?int
    {
        return $this->VISADI;
    }

    public function setVISADI(?int $VISADI): self
    {
        $this->VISADI = $VISADI;

        return $this;
    }

    public function getVISADD(): ?int
    {
        return $this->VISADD;
    }

    public function setVISADD(?int $VISADD): self
    {
        $this->VISADD = $VISADD;

        return $this;
    }

    public function getOfferNumber(): ?string
    {
        return $this->offer_number;
    }

    public function setOfferNumber(?string $offer_number): self
    {
        $this->offer_number = $offer_number;

        return $this;
    }

    public function getVISASUPDI(): ?int
    {
        return $this->VISASUPDI;
    }

    public function setVISASUPDI(?int $VISASUPDI): self
    {
        $this->VISASUPDI = $VISASUPDI;

        return $this;
    }

    public function getVISASUPDD(): ?int
    {
        return $this->VISASUPDD;
    }

    public function setVISASUPDD(?int $VISASUPDD): self
    {
        $this->VISASUPDD = $VISASUPDD;

        return $this;
    }

    public function getCodeOffer(): ?string
    {
        return $this->code_offer;
    }

    public function setCodeOffer(?string $code_offer): self
    {
        $this->code_offer = $code_offer;

        return $this;
    }

    public function getActiveOffer(): ?string
    {
        return $this->active_offer;
    }

    public function setActiveOffer(?string $active_offer): self
    {
        $this->active_offer = $active_offer;

        return $this;
    }
    
    public function getRefLabel(): ?string
    {
        return $this->refLabel;
    }

    public function setRefLabel(string $refLabel): self
    {
        $this->refLabel = $refLabel;

        return $this;
    }
     public function getRefLabels(): ?array
    {
        return $this->refLabels;
    }

    public function setRefLabels(array $refLabels): self
    {
        $this->refLabels = $refLabels;

        return $this;
    }
}
