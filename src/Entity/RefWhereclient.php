<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefWhereclientRepository")
 */
class RefWhereclient
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
    private $code_whereclient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_whereclient;

    
    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    // /**
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataClientWhereclient", mappedBy="whereclient")
    //  */
    // private $dataClientWhereclients;

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
    
    public function __construct()
    {
        $this->dataClientWhereclients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeWhereclient(): ?string
    {
        return $this->code_whereclient;
    }

    public function setCodeWhereclient(string $code_whereclient): self
    {
        $this->code_whereclient = $code_whereclient;

        return $this;
    }

    public function getActiveWhereclient(): ?string
    {
        return $this->active_whereclient;
    }

    public function setActiveWhereclient(string $active_whereclient): self
    {
        $this->active_whereclient = $active_whereclient;

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
    //  * @return Collection|DataClientWhereclient[]
    //  */
    // public function getDataClientWhereclients(): Collection
    // {
    //     return $this->dataClientWhereclients;
    // }

    // public function addDataClientWhereclient(DataClientWhereclient $dataClientWhereclient): self
    // {
    //     if (!$this->dataClientWhereclients->contains($dataClientWhereclient)) {
    //         $this->dataClientWhereclients[] = $dataClientWhereclient;
    //         $dataClientWhereclient->setWhereclient($this);
    //     }

    //     return $this;
    // }

    // public function removeDataClientWhereclient(DataClientWhereclient $dataClientWhereclient): self
    // {
    //     if ($this->dataClientWhereclients->contains($dataClientWhereclient)) {
    //         $this->dataClientWhereclients->removeElement($dataClientWhereclient);
    //         // set the owning side to null (unless already changed)
    //         if ($dataClientWhereclient->getWhereclient() === $this) {
    //             $dataClientWhereclient->setWhereclient(null);
    //         }
    //     }

    //     return $this;
    // }
}
