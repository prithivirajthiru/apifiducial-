<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefTypeclientRepository")
 */
class RefTypeclient
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
    private $code_typeclient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_typeclient;

    
    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    private $code_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_typeclient;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $labelColor;
    public function __construct()
    {
        $this->dataClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeTypeclient(): ?string
    {
        return $this->code_typeclient;
    }

    public function setCodeTypeclient(string $code_typeclient): self
    {
        $this->code_typeclient = $code_typeclient;

        return $this;
    }

    public function getActiveTypeclient(): ?string
    {
        return $this->active_typeclient;
    }

    public function setActiveTypeclient(string $active_typeclient): self
    {
        $this->active_typeclient = $active_typeclient;

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
     public function getCodeId(): ?int
    {
        return $this->code_id;
    }
    public function setCodeId(int $code_id): self
    {
        $this->code_id = $code_id;

        return $this;
    }

    public function getDescTypeclient(): ?string
    {
        return $this->desc_typeclient;
    }

    public function setDescTypeclient(?string $desc_typeclient): self
    {
        $this->desc_typeclient = $desc_typeclient;

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
    //         $dataClient->setTypeclient($this);
    //     }

    //     return $this;
    // }

    // public function removeDataClient(DataClient $dataClient): self
    // {
    //     if ($this->dataClients->contains($dataClient)) {
    //         $this->dataClients->removeElement($dataClient);
    //         // set the owning side to null (unless already changed)
    //         if ($dataClient->getTypeclient() === $this) {
    //             $dataClient->setTypeclient(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection|RefLegalform[]
    //  */
    // public function getRefLegalforms(): Collection
    // {
    //     return $this->refLegalforms;
    // }

    // public function addRefLegalform(RefLegalform $refLegalform): self
    // {
    //     if (!$this->refLegalforms->contains($refLegalform)) {
    //         $this->refLegalforms[] = $refLegalform;
    //         $refLegalform->setIdCompany($this);
    //     }

    //     return $this;
    // }

    // public function removeRefLegalform(RefLegalform $refLegalform): self
    // {
    //     if ($this->refLegalforms->contains($refLegalform)) {
    //         $this->refLegalforms->removeElement($refLegalform);
    //         // set the owning side to null (unless already changed)
    //         if ($refLegalform->getIdCompany() === $this) {
    //             $refLegalform->setIdCompany(null);
    //         }
    //     }

    //     return $this;
    // }
}

