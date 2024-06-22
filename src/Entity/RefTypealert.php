<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefTypealertRepository")
 */
class RefTypealert
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
    private $code_typealert;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_typealert;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_typealert;

    
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

    public function getCodeTypealert(): ?string
    {
        return $this->code_typealert;
    }

    public function setCodeTypealert(string $code_typealert): self
    {
        $this->code_typealert = $code_typealert;

        return $this;
    }

    public function getDescTypealert(): ?string
    {
        return $this->desc_typealert;
    }

    public function setDescTypealert(string $desc_typealert): self
    {
        $this->desc_typealert = $desc_typealert;

        return $this;
    }

    public function getActiveTypealert(): ?string
    {
        return $this->active_typealert;
    }

    public function setActiveTypealert(string $active_typealert): self
    {
        $this->active_typealert = $active_typealert;

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
