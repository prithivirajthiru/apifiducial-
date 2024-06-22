<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataOfferRepository")
 */
class DataOffer
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
    private $refInternal;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $descInternal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $offerCode;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fromDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $toDate;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $msgClient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $msgExpiration;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxQuantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefOfferType", inversedBy="no")
     */
    private $type;
    private $productOffers;
    private $productIds;
    private $typeId;
    private $fromDateString;
    private $toDateString;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRefInternal(): ?string
    {
        return $this->refInternal;
    }

    public function setRefInternal(?string $refInternal): self
    {
        $this->refInternal = $refInternal;

        return $this;
    }

    public function getDescInternal(): ?string
    {
        return $this->descInternal;
    }

    public function setDescInternal(?string $descInternal): self
    {
        $this->descInternal = $descInternal;

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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(?\DateTimeInterface $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->toDate;
    }

    public function setToDate(?\DateTimeInterface $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    public function getMsgClient(): ?string
    {
        return $this->msgClient;
    }

    public function setMsgClient(?string $msgClient): self
    {
        $this->msgClient = $msgClient;

        return $this;
    }

    public function getMsgExpiration(): ?string
    {
        return $this->msgExpiration;
    }

    public function setMsgExpiration(?string $msgExpiration): self
    {
        $this->msgExpiration = $msgExpiration;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getMaxQuantity(): ?int
    {
        return $this->maxQuantity;
    }

    public function setMaxQuantity(?int $maxQuantity): self
    {
        $this->maxQuantity = $maxQuantity;

        return $this;
    }

    public function getType(): ?RefOfferType
    {
        return $this->type;
    }

    public function setType(?RefOfferType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProductIds(): ?array
    {
        return $this->productIds;
    }

    public function setProductIds(?array $productIds): self
    {
        $this->productIds = $productIds;

        return $this;
    }
    public function getProductOffers(): ?array
    {
        return $this->productOffers;
    }

    public function setProductOffers(?array $productOffers): self
    {
        $this->productOffers = $productOffers;

        return $this;
    }
    
    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function setTypeId(?int $typeId): self
    {
        $this->typeId = $typeId;

        return $this;
    }
    public function getFromDateString(): ?string
    {
        return $this->fromDateString;
    }

    public function setFromDateString(?string $fromDateString): self
    {
        $this->fromDateString = $fromDateString;

        return $this;
    }
    public function getToDateString(): ?string
    {
        return $this->toDateString;
    }

    public function setToDateString(?string $toDateString): self
    {
        $this->toDateString = $toDateString;

        return $this;
    }
}
