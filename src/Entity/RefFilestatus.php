<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefFilestatusRepository")
 */
class RefFilestatus
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
    private $code_filestatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_filestatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_filestatus;

    
       
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

    public function getCodeFilestatus(): ?string
    {
        return $this->code_filestatus;
    }

    public function setCodeFilestatus(string $code_filestatus): self
    {
        $this->code_filestatus = $code_filestatus;

        return $this;
    }

    public function getDescFilestatus(): ?string
    {
        return $this->desc_filestatus;
    }

    public function setDescFilestatus(string $desc_filestatus): self
    {
        $this->desc_filestatus = $desc_filestatus;

        return $this;
    }

    public function getActiveFilestatus(): ?string
    {
        return $this->active_filestatus;
    }

    public function setActiveFilestatus(string $active_filestatus): self
    {
        $this->active_filestatus = $active_filestatus;

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
