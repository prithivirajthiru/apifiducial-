<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefBankRepository")
 */
class RefBank
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
    private $code_bank;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_bank;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_bank;
    
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
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataClient", mappedBy="bank")
    //  */
    // private $dataClients;

    public function __construct()
    {
        $this->dataClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeBank(): ?string
    {
        return $this->code_bank;
    }

    public function setCodeBank(string $code_bank): self
    {
        $this->code_bank = $code_bank;

        return $this;
    }

    public function getDescBank(): ?string
    {
        return $this->desc_bank;
    }

    public function setDescBank(string $desc_bank): self
    {
        $this->desc_bank = $desc_bank;

        return $this;
    }

    public function getActiveBank(): ?string
    {
        return $this->active_bank;
    }

    public function setActiveBank(string $active_bank): self
    {
        $this->active_bank = $active_bank;

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
    //  * @return Collection|DataClient[]
    //  */
    // public function getDataClients(): Collection
    // {
    //     return $this->dataClients;
    // }

    // public function addDataClient(DataClient $dataClient): self
    // {
    //     if (!$this->dataClients->contains($dataClient)) {
    //         $this->dataClients[] = $dataClient;
    //         $dataClient->setBank($this);
    //     }

    //     return $this;
    // }

    // public function removeDataClient(DataClient $dataClient): self
    // {
    //     if ($this->dataClients->contains($dataClient)) {
    //         $this->dataClients->removeElement($dataClient);
    //         // set the owning side to null (unless already changed)
    //         if ($dataClient->getBank() === $this) {
    //             $dataClient->setBank(null);
    //         }
    //     }

    //     return $this;
    // }
}
