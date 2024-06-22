<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class RequestContent 
{
   private $sabId;
   private $companyName;
   private $createDate;
   private $typeClient;
   private $product;

   public function getsabId(): ?string
    {
        return $this->sabId;
    }

    public function setsabId(?string $sabId): self
    {
        $this->sabId = $sabId;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCreateDate(): ?string
    {
        return $this->createDate;
    }

    public function setCreateDate(?string $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getTypeClient(): ?string
    {
        return $this->typeClient;
    }

    public function setTypeClient(?string $typeClient): self
    {
        $this->typeClient = $typeClient;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(?string $product): self
    {
        $this->product = $product;

        return $this;
    }
}