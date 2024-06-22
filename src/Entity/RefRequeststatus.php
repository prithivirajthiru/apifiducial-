<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefRequeststatusRepository")
 */
class RefRequeststatus
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
    private $code_requeststatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_requeststatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_requeststatus;


    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $order_requeststatus;

    private $code_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status_requeststatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $visibility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fo_desc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $destination;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $action;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cto;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $n2n0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $n2n1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $origine;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $integration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $attdepot;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $eloqua;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $step;

   
    public function getCodeId(): ?int
    {
        return $this->code_id;
    }
    public function setCodeId(int $code_id): self
    {
        $this->code_id = $code_id;

        return $this;
    }
    
    public function __construct()
    {
        
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

    public function getCodeRequeststatus(): ?string
    {
        return $this->code_requeststatus;
    }

    public function setCodeRequeststatus(string $code_requeststatus): self
    {
        $this->code_requeststatus = $code_requeststatus;

        return $this;
    }

    public function getDescRequeststatus(): ?string
    {
        return $this->desc_requeststatus;
    }

    public function setDescRequeststatus(string $desc_requeststatus): self
    {
        $this->desc_requeststatus = $desc_requeststatus;

        return $this;
    }

    public function getActiveRequeststatus(): ?string
    {
        return $this->active_requeststatus;
    }

    public function setActiveRequeststatus(string $active_requeststatus): self
    {
        $this->active_requeststatus = $active_requeststatus;

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


    public function getOrderRequeststatus(): ?int
    {
        return $this->order_requeststatus;
    }

    public function setOrderRequeststatus(?int $order_requeststatus): self
    {
        $this->order_requeststatus = $order_requeststatus;

        return $this;
    }

    public function getStatusRequeststatus(): ?string
    {
        return $this->status_requeststatus;
    }

    public function setStatusRequeststatus(?string $status_requeststatus): self
    {
        $this->status_requeststatus = $status_requeststatus;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(?string $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getFoDesc(): ?string
    {
        return $this->fo_desc;
    }

    public function setFoDesc(?string $fo_desc): self
    {
        $this->fo_desc = $fo_desc;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getCto(): ?bool
    {
        return $this->cto;
    }

    public function setCto(?bool $cto): self
    {
        $this->cto = $cto;

        return $this;
    }

    public function getN2n0(): ?bool
    {
        return $this->n2n0;
    }

    public function setN2n0(?bool $n2n0): self
    {
        $this->n2n0 = $n2n0;

        return $this;
    }

    public function getN2n1(): ?bool
    {
        return $this->n2n1;
    }

    public function setN2n1(?bool $n2n1): self
    {
        $this->n2n1 = $n2n1;

        return $this;
    }

    public function getOrigine(): ?string
    {
        return $this->origine;
    }

    public function setOrigine(string $origine): self
    {
        $this->origine = $origine;

        return $this;
    }

    public function getIntegration(): ?int
    {
        return $this->integration;
    }

    public function setIntegration(string $integration): self
    {
        $this->integration = $integration;

        return $this;
    }

    public function getAttDepot(): ?int
    {
        return $this->attdepot;
    }

    public function setAttDepot(string $attdepot): self
    {
        $this->attdepot = $attdepot;

        return $this;
    }

    public function getEloqua(): ?bool
    {
        return $this->eloqua;
    }

    public function setEloqua(?bool $eloqua): self
    {
        $this->eloqua = $eloqua;

        return $this;
    }

    public function getStep(): ?string
    {
        return $this->step;
    }

    public function setStep(?string $step): self
    {
        $this->step = $step;

        return $this;
    }
}
