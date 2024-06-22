<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefFunctionRepository")
 */
class RefFunction
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
    private $code_function;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_function;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_function;

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
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataAttorney", mappedBy="function")
    //  */
    // private $dataAttorneys;

   

    public function __construct()
    {
        $this->dataAttroneys = new ArrayCollection();
        $this->dataAttorneys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeFunction(): ?string
    {
        return $this->code_function;
    }

    public function setCodeFunction(string $code_function): self
    {
        $this->code_function = $code_function;

        return $this;
    }

    public function getDescFunction(): ?string
    {
        return $this->desc_function;
    }

    public function setDescFunction(string $desc_function): self
    {
        $this->desc_function = $desc_function;

        return $this;
    }

    public function getActiveFunction(): ?string
    {
        return $this->active_function;
    }

    public function setActiveFunction(string $active_function): self
    {
        $this->active_function = $active_function;

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
    //  * @return Collection|DataAttorney[]
    //  */
    // public function getDataAttorneys(): Collection
    // {
    //     return $this->dataAttorneys;
    // }

    // public function addDataAttorney(DataAttorney $dataAttorney): self
    // {
    //     if (!$this->dataAttorneys->contains($dataAttorney)) {
    //         $this->dataAttorneys[] = $dataAttorney;
    //         $dataAttorney->setFunction($this);
    //     }

    //     return $this;
    // }

    // public function removeDataAttorney(DataAttorney $dataAttorney): self
    // {
    //     if ($this->dataAttorneys->contains($dataAttorney)) {
    //         $this->dataAttorneys->removeElement($dataAttorney);
    //         // set the owning side to null (unless already changed)
    //         if ($dataAttorney->getFunction() === $this) {
    //             $dataAttorney->setFunction(null);
    //         }
    //     }

    //     return $this;
    // }

    
}
