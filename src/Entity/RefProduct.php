<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefProductRepository")
 */
class RefProduct
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageurl;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_product;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    
    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;


    private $code_id;

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
    private $buis_di;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buis_dd;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buis_supdi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $buis_supdd;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vida_di;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $visa_dd;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $visa_supdi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $visa_supdd;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefTypeclient")
     */
    private $typeclient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cheque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tpe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $card_limit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cash;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $sabcategory;

   /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $headId;
   
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $labelColor;

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

    public function getTypeclient(): ?RefTypeclient
    {
        return $this->typeclient;
    }

    public function setTypeclient(?RefTypeclient $typeclient): self
    {
        $this->typeclient = $typeclient;

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
