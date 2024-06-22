<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefOfferContentRepository")
 */
class RefOfferContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefOffer")
     */
    private $offer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_offer_content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_offer_content;

    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $active_offercontent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffer(): ?RefOffer
    {
        return $this->offer;
    }

    public function setOffer(?RefOffer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getDescOfferContent(): ?string
    {
        return $this->desc_offer_content;
    }

    public function setDescOfferContent(?string $desc_offer_content): self
    {
        $this->desc_offer_content = $desc_offer_content;

        return $this;
    }

    public function getCodeOfferContent(): ?string
    {
        return $this->code_offer_content;
    }

    public function setCodeOfferContent(?string $code_offer_content): self
    {
        $this->code_offer_content = $code_offer_content;

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

    public function getActiveOffercontent(): ?string
    {
        return $this->active_offercontent;
    }

    public function setActiveOffercontent(?string $active_offercontent): self
    {
        $this->active_offercontent = $active_offercontent;

        return $this;
    }
}
