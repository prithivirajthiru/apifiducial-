<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefFileRepository")
 */
class RefFile
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
    private $code_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mandatory_file;

       
    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RefLegalformfile", mappedBy="file")
     */
    private $refLegalformfiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RefEpafile", mappedBy="file")
     */
    private $refEpafiles;

    private $code_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jsonkey;

   
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
        $this->refLegalformfiles = new ArrayCollection();
        $this->refEpafiles = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCodeFile(): ?string
    {
        return $this->code_file;
    }

    public function setCodeFile(string $code_file): self
    {
        $this->code_file = $code_file;

        return $this;
    }

    public function getDescFile(): ?string
    {
        return $this->desc_file;
    }

    public function setDescFile(string $desc_file): self
    {
        $this->desc_file = $desc_file;

        return $this;
    }

    public function getActiveFile(): ?string
    {
        return $this->active_file;
    }

    public function setActiveFile(string $active_file): self
    {
        $this->active_file = $active_file;

        return $this;
    }

    public function getMandatoryFile(): ?string
    {
        return $this->mandatory_file;
    }

    public function setMandatoryFile(string $mandatory_file): self
    {
        $this->mandatory_file = $mandatory_file;

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
    //         $refLegalformfile->setFile($this);
    //     }

    //     return $this;
    // }

    // public function removeRefLegalformfile(RefLegalformfile $refLegalformfile): self
    // {
    //     if ($this->refLegalformfiles->contains($refLegalformfile)) {
    //         $this->refLegalformfiles->removeElement($refLegalformfile);
    //         // set the owning side to null (unless already changed)
    //         if ($refLegalformfile->getFile() === $this) {
    //             $refLegalformfile->setFile(null);
    //         }
    //     }

    //     return $this;
    // }

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
    //         $refEpafile->setFile($this);
    //     }

    //     return $this;
    // }

    // public function removeRefEpafile(RefEpafile $refEpafile): self
    // {
    //     if ($this->refEpafiles->contains($refEpafile)) {
    //         $this->refEpafiles->removeElement($refEpafile);
    //         // set the owning side to null (unless already changed)
    //         if ($refEpafile->getFile() === $this) {
    //             $refEpafile->setFile(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getFilenameFile(): ?string
    {
        return $this->filename_file;
    }

    public function setFilenameFile(?string $filename_file): self
    {
        $this->filename_file = $filename_file;

        return $this;
    }

    public function getJsonkey(): ?string
    {
        return $this->jsonkey;
    }

    public function setJsonkey(?string $jsonkey): self
    {
        $this->jsonkey = $jsonkey;

        return $this;
    }

}
