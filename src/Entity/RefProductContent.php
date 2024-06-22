<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefProductContentRepository")
 */
class RefProductContent
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_product;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_productcontent;
    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;


    private $code_id;


    public function getCodeId(): ?int
    {
        return $this->code_id;
    }
    public function setCodeId(int $code_id): self
    {
        $this->code_id = $code_id;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $product): self
    {
        $this->id = $id;

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

    public function getDescProduct(): ?string
    {
        return $this->desc_product;
    }

    public function setDescProduct(?string $desc_product): self
    {
        $this->desc_product = $desc_product;

        return $this;
    }

    public function getCodeProductcontent(): ?string
    {
        return $this->code_productcontent;
    }

    public function setCodeProductcontent(?string $code_productcontent): self
    {
        $this->code_productcontent = $code_productcontent;

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
