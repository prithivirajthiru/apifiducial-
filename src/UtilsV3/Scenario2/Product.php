<?php
namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\RefLabel;
use App\Entity\RefProduct;
use App\Entity\RefProductContent;


class Product{
   
    private $id;
    private $name;
    private $imageurl;
    private $price;
    private $status;
    private $arraystatus;
    private $refLabel;
    private $code_product;
    private $description;

    /**
     * @var RefLabel
     */
    private  $refLabels ;


    private $code_id;

   



    public function getArrayStatus(): ?array
    {
        return $this->arraystatus;
    }

    public function setArrayStatus(?array $arraystatus): self
    {
        $this->arraystatus = $arraystatus;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setImageurl(?string $imageurl): self
    {
        $this->imageurl = $imageurl;

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

    public function getCodeProduct(): ?string
    {
        return $this->code_product;
    }

    public function setCodeProduct(?string $code_product): self
    {
        $this->code_product = $code_product;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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
    public function getDescription(): ?array
    {
        return $this->description;
    }

    public function setDescription(?array $description): self
    {
        $this->description = $description;

        return $this;
    }
   

}

class Description{

    private $id;
    private $product;
    private $desc_product;
    private $code_productcontent;

      /**
     * @var RefLabel
     */
    private  $refLabels ;
    private $refLabel;
   

    public function getCodeProductcontent(): ?string
    {
        return $this->code_productcontent;
    }

    public function setCodeProductcontent(?string $code_productcontent): self
    {
        $this->code_productcontent = $code_productcontent;

        return $this;
    }
   
  
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProduct(): ?int
    {
        return $this->product;
    }

    public function setProduct(?int $product): self
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