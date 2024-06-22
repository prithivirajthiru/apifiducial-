<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefWheresupplierRepository")
 */
class RefWheresupplier
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
    private $code_wheresupplier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_wheresupplier;

    
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
    // /**
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataClientWheresupplier", mappedBy="wheresupplier")
    //  */
    // private $dataClientWheresuppliers;

    public function __construct()
    {
        $this->dataClientWheresuppliers = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeWheresupplier(): ?string
    {
        return $this->code_wheresupplier;
    }

    public function setCodeWheresupplier(string $code_wheresupplier): self
    {
        $this->code_wheresupplier = $code_wheresupplier;

        return $this;
    }

    public function getActiveWheresupplier(): ?string
    {
        return $this->active_wheresupplier;
    }

    public function setActiveWheresupplier(string $active_wheresupplier): self
    {
        $this->active_wheresupplier = $active_wheresupplier;

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

    // /**
    //  * @return Collection|DataClientWheresupplier[]
    //  */
    // public function getDataClientWheresuppliers(): Collection
    // {
    //     return $this->dataClientWheresuppliers;
    // }

    // public function addDataClientWheresupplier(DataClientWheresupplier $dataClientWheresupplier): self
    // {
    //     if (!$this->dataClientWheresuppliers->contains($dataClientWheresupplier)) {
    //         $this->dataClientWheresuppliers[] = $dataClientWheresupplier;
    //         $dataClientWheresupplier->setWheresupplier($this);
    //     }

    //     return $this;
    // }

    // public function removeDataClientWheresupplier(DataClientWheresupplier $dataClientWheresupplier): self
    // {
    //     if ($this->dataClientWheresuppliers->contains($dataClientWheresupplier)) {
    //         $this->dataClientWheresuppliers->removeElement($dataClientWheresupplier);
    //         // set the owning side to null (unless already changed)
    //         if ($dataClientWheresupplier->getWheresupplier() === $this) {
    //             $dataClientWheresupplier->setWheresupplier(null);
    //         }
    //     }

    //     return $this;
    // }
}
