<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefLegalformRepository")
 */
class RefLegalform
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCompanytype", inversedBy="refLegalforms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_company;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_legalform;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_legalform;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_legalform;

    private $company;


    private $refLabel;

    
    private  $refLabels ;

    private $refcompanyid;

    private $code_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $legalform_code;

   
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
    //  * @ORM\OneToMany(targetEntity="App\Entity\RefLegalformfile", mappedBy="legalform")
    //  */
    // private $refLegalformfiles;

    // /**
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataClient", mappedBy="legalform")
    //  */
    // private $dataClients;

    public function __construct()
    {
        // $this->refLegalformfiles = new ArrayCollection();
        // $this->dataClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCompany(): ?RefCompanytype
    {
        return $this->id_company;
    }

    public function setIdCompany(?RefCompanytype $id_company): self
    {
        $this->id_company = $id_company;

        return $this;
    }

    public function getCodeLegalform(): ?string
    {
        return $this->code_legalform;
    }

    public function setCodeLegalform(string $code_legalform): self
    {
        $this->code_legalform = $code_legalform;

        return $this;
    }

    public function getDescLegalform(): ?string
    {
        return $this->desc_legalform;
    }

    public function setDescLegalform(string $desc_legalform): self
    {
        $this->desc_legalform = $desc_legalform;

        return $this;
    }

    public function getActiveLegalform(): ?string
    {
        return $this->active_legalform;
    }

    public function setActiveLegalform(string $active_legalform): self
    {
        $this->active_legalform = $active_legalform;

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
     public function getRefCompanyId(): ?string
    {
        return $this->refcompanyid;
    }

    public function setRefCompanyId(string $refcompanyid): self
    {
        $this->refcompanyid = $refcompanyid;

        return $this;
    }
    public function getCompany(): ?int
    {
        return $this->company;
    }

    public function setCompany(?int $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getLegalformCode(): ?string
    {
        return $this->legalform_code;
    }

    public function setLegalformCode(?string $legalform_code): self
    {
        $this->legalform_code = $legalform_code;

        return $this;
    }


    // /**
    //  * @return Collection|RefLegalformfile[]
    //  */
    // public function getRefLegalformfiles(): Collection
    // {
    //     return $this->refLegalformfiles;
    // }

    // public function addRefLegalformfile(RefLegalformfile $refLegalformfile): self
    // {
    //     if (!$this->refLegalformfiles->contains($refLegalformfile)) {
    //         $this->refLegalformfiles[] = $refLegalformfile;
    //         $refLegalformfile->setLegalform($this);
    //     }

    //     return $this;
    // }

    // public function removeRefLegalformfile(RefLegalformfile $refLegalformfile): self
    // {
    //     if ($this->refLegalformfiles->contains($refLegalformfile)) {
    //         $this->refLegalformfiles->removeElement($refLegalformfile);
    //         // set the owning side to null (unless already changed)
    //         if ($refLegalformfile->getLegalform() === $this) {
    //             $refLegalformfile->setLegalform(null);
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
    //         $dataClient->setLegalform($this);
    //     }

    //     return $this;
    // }

    // public function removeDataClient(DataClient $dataClient): self
    // {
    //     if ($this->dataClients->contains($dataClient)) {
    //         $this->dataClients->removeElement($dataClient);
    //         // set the owning side to null (unless already changed)
    //         if ($dataClient->getLegalform() === $this) {
    //             $dataClient->setLegalform(null);
    //         }
    //     }

    //     return $this;
    // }
    
   
}
