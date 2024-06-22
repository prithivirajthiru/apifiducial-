<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefEpaRepository")
 */
class RefEpa
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
    private $code_epa;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_epa;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_epa;

 

    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    private $code_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $epa_code;

   
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
    //  * @ORM\OneToMany(targetEntity="App\Entity\RefEpafile", mappedBy="epa")
    //  */
    // private $refEpafiles;

    // /**
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataClient", mappedBy="epa")
    //  */
    // private $dataClients;
    public function __construct()
    {
        // $this->refEpafiles = new ArrayCollection();
        // $this->dataClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeEpa(): ?string
    {
        return $this->code_epa;
    }

    public function setCodeEpa(string $code_epa): self
    {
        $this->code_epa = $code_epa;

        return $this;
    }

    public function getDescEpa(): ?string
    {
        return $this->desc_epa;
    }

    public function setDescEpa(string $desc_epa): self
    {
        $this->desc_epa = $desc_epa;

        return $this;
    }

    public function getActiveEpa(): ?string
    {
        return $this->active_epa;
    }

    public function setActiveEpa(string $active_epa): self
    {
        $this->active_epa = $active_epa;

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

    public function getEpaCode(): ?string
    {
        return $this->epa_code;
    }

    public function setEpaCode(?string $epa_code): self
    {
        $this->epa_code = $epa_code;

        return $this;
    }

   
    // /**
    //  * @return Collection|RefEpafile[]
    //  */
    // public function getRefEpafiles(): Collection
    // {
    //     return $this->refEpafiles;
    // }

    // public function addRefEpafile(RefEpafile $refEpafile): self
    // {
    //     if (!$this->refEpafiles->contains($refEpafile)) {
    //         $this->refEpafiles[] = $refEpafile;
    //         $refEpafile->setEpa($this);
    //     }

    //     return $this;
    // }

    // public function removeRefEpafile(RefEpafile $refEpafile): self
    // {
    //     if ($this->refEpafiles->contains($refEpafile)) {
    //         $this->refEpafiles->removeElement($refEpafile);
    //         // set the owning side to null (unless already changed)
    //         if ($refEpafile->getEpa() === $this) {
    //             $refEpafile->setEpa(null);
    //         }
    //     }

    //     return $this;
    // }

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
    //         $dataClient->setEpa($this);
    //     }

    //     return $this;
    // }

    // public function removeDataClient(DataClient $dataClient): self
    // {
    //     if ($this->dataClients->contains($dataClient)) {
    //         $this->dataClients->removeElement($dataClient);
    //         // set the owning side to null (unless already changed)
    //         if ($dataClient->getEpa() === $this) {
    //             $dataClient->setEpa(null);
    //         }
    //     }

    //     return $this;
    // }

 
}
