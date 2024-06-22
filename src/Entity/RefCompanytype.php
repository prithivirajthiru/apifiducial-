<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefCompanytypeRepository")
 */
class RefCompanytype
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
    private $code_companytype;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_companytype;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_companytype;

    // /**
    //  * @ORM\OneToMany(targetEntity="App\Entity\RefLegalform", mappedBy="id_company")
    //  */
    // private $refLegalforms;

    
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
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataClient", mappedBy="companytype")
    //  */
    // private $dataClients;

    public function __construct()
    {
        // $this->refLegalforms = new ArrayCollection();
        // $this->dataClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCompanytype(): ?string
    {
        return $this->code_companytype;
    }

    public function setCodeCompanytype(string $code_companytype): self
    {
        $this->code_companytype = $code_companytype;

        return $this;
    }

    public function getDescCompanytype(): ?string
    {
        return $this->desc_companytype;
    }

    public function setDescCompanytype(string $desc_companytype): self
    {
        $this->desc_companytype = $desc_companytype;

        return $this;
    }

    public function getActiveCompanytype(): ?string
    {
        return $this->active_companytype;
    }

    public function setActiveCompanytype(string $active_companytype): self
    {
        $this->active_companytype = $active_companytype;

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
    //  * @return Collection|RefLegalform[]
    //  */
    // public function getRefLegalforms(): Collection
    // {
    //     return $this->refLegalforms;
    // }
    

    // public function setRefLegalforms( $lfs)
    // {
    //      $this->refLegalforms = $lfs;
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
    //         $dataClient->setCompanytype($this);
    //     }

    //     return $this;
    // }

    // public function removeDataClient(DataClient $dataClient): self
    // {
    //     if ($this->dataClients->contains($dataClient)) {
    //         $this->dataClients->removeElement($dataClient);
    //         // set the owning side to null (unless already changed)
    //         if ($dataClient->getCompanytype() === $this) {
    //             $dataClient->setCompanytype(null);
    //         }
    //     }

    //     return $this;
    // }
}
