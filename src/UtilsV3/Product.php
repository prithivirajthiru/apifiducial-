<?php
namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\RefLabel;
use App\Entity\RefProduct;
use App\Entity\RefProductContent;
use App\Entity\RefTypeclient;
use App\Entity\RefCompanytype;


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
    private $business;
    private $visa;
    private $buis_di;
    private $buis_dd;
    private $buis_supdi;
    private $buis_supdd;
    private $vida_di;
    private $visa_dd;
    private $visa_supdi;
    private $visa_supdd;
    private $typeclient;
    private $cheque;
    private $tpe;
    private $card_limit;
    private $reftypeclient;
    private $companytype;
    /**
     * @var RefLabel
     */
    private  $refLabels ;

    private $priority;
    private $code_id;
    private $cash;
    private $sabcategory;

    private $color;
    private $headId;
    private $headName;
    private $headProduct;
    private $labelColor;


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
    public function getTypeclient(): ?int
    {
        return $this->typeclient;
    }
    public function setTypeclient(int $typeclient): self
    {
        $this->typeclient = $typeclient;
        return $this;
    }
    public function getRefTypeclient(): ?RefTypeclient
    {
        return $this->reftypeclient;
    }
    public function setRefTypeclient(RefTypeclient $reftypeclient): self
    {
        $this->reftypeclient = $reftypeclient;
        return $this;
    }
    public function getCompanytype(): ?RefCompanytype
    {
        return $this->companytype;
    }
    public function setCompanytype(RefCompanytype $companytype): self
    {
        $this->companytype = $companytype;
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

    public function getBuisDi(): ?int
    {
        return $this->buis_di;
    }

    public function setBuisDi(?int $buis_di): self
    {
        $this->buis_di = $buis_di;

        return $this;
    }

    public function getBuisDd(): ?int
    {
        return $this->buis_dd;
    }

    public function setBuisDd(?int $buis_dd): self
    {
        $this->buis_dd = $buis_dd;

        return $this;
    }

    public function getBuisSupdi(): ?int
    {
        return $this->buis_supdi;
    }

    public function setBuisSupdi(?int $buis_supdi): self
    {
        $this->buis_supdi = $buis_supdi;

        return $this;
    }

    public function getBuisSupdd(): ?int
    {
        return $this->buis_supdd;
    }

    public function setBuisSupdd(?int $buis_supdd): self
    {
        $this->buis_supdd = $buis_supdd;

        return $this;
    }

    public function getVidaDi(): ?int
    {
        return $this->vida_di;
    }

    public function setVidaDi(?int $vida_di): self
    {
        $this->vida_di = $vida_di;

        return $this;
    }

    public function getVisaDd(): ?int
    {
        return $this->visa_dd;
    }

    public function setVisaDd(?int $visa_dd): self
    {
        $this->visa_dd = $visa_dd;

        return $this;
    }

    public function getVisaSupdi(): ?int
    {
        return $this->visa_supdi;
    }

    public function setVisaSupdi(?int $visa_supdi): self
    {
        $this->visa_supdi = $visa_supdi;

        return $this;
    }

    public function getVisaSupdd(): ?int
    {
        return $this->visa_supdd;
    }

    public function setVisaSupdd(?int $visa_supdd): self
    {
        $this->visa_supdd = $visa_supdd;

        return $this;
    }

    public function getCheque(): ?string
    {
        return $this->cheque;
    }

    public function setCheque(?string $cheque): self
    {
        $this->cheque = $cheque;

        return $this;
    }

    public function getTpe(): ?string
    {
        return $this->tpe;
    }

    public function setTpe(?string $tpe): self
    {
        $this->tpe = $tpe;

        return $this;
    }

    public function getCardLimit(): ?int
    {
        return $this->card_limit;
    }

    public function setCardLimit(?int $card_limit): self
    {
        $this->card_limit = $card_limit;

        return $this;
    }
    public function getCash(): ?string
    {
        return $this->cash;
    }

    public function setCash(?string $cash): self
    {
        $this->cash = $cash;

        return $this;
    }
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getSabcategory(): ?string
    {
        return $this->sabcategory;
    }

    public function setSabcategory(?string $sabcategory): self
    {
        $this->sabcategory = $sabcategory;

        return $this;
    }
    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
    
    public function getHeadId(): ?int
    {
        return $this->headId;
    }

    public function setHeadId(?int $headId): self
    {
        $this->headId = $headId;

        return $this;
    }

    public function getHeadName(): ?string
    {
        return $this->headName;
    }

    public function setHeadName(?string $headName): self
    {
        $this->headName = $headName;

        return $this;
    }

    public function getHeadProduct(): ?RefProduct
    {
        return $this->headProduct;
    }

    public function setHeadProduct(?RefProduct $headProduct): self
    {
        $this->headProduct = $headProduct;

        return $this;
    }
    public function getLabelColor(): ?string
    {
        return $this->labelColor;
    }

    public function setLabelColor(?string $labelColor): self
    {
        $this->labelColor = $labelColor;

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