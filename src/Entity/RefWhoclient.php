<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefWhoclientRepository")
 */
class RefWhoclient
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
    private $code_whoclient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_whoclient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $active_whoclient;

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

    public function getCodeWhoclient(): ?string
    {
        return $this->code_whoclient;
    }

    public function setCodeWhoclient(?string $code_whoclient): self
    {
        $this->code_whoclient = $code_whoclient;

        return $this;
    }

    public function getDescWhoclient(): ?string
    {
        return $this->desc_whoclient;
    }

    public function setDescWhoclient(?string $desc_whoclient): self
    {
        $this->desc_whoclient = $desc_whoclient;

        return $this;
    }

    public function getActiveWhoclient(): ?string
    {
        return $this->active_whoclient;
    }

    public function setActiveWhoclient(?string $active_whoclient): self
    {
        $this->active_whoclient = $active_whoclient;

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
