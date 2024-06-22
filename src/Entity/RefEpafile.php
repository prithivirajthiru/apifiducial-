<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefEpafileRepository")
 */
class RefEpafile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefEpa", inversedBy="refEpafiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $epa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefFile", inversedBy="refEpafiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mandatory_epafile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_epafile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_epafile;

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

    public function getEpa(): ?RefEpa
    {
        return $this->epa;
    }

    public function setEpa(?RefEpa $epa): self
    {
        $this->epa = $epa;

        return $this;
    }

    public function getFile(): ?RefFile
    {
        return $this->file;
    }

    public function setFile(?RefFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getMandatoryEpafile(): ?string
    {
        return $this->mandatory_epafile;
    }

    public function setMandatoryEpafile(string $mandatory_epafile): self
    {
        $this->mandatory_epafile = $mandatory_epafile;

        return $this;
    }

    public function getActiveEpafile(): ?string
    {
        return $this->active_epafile;
    }

    public function setActiveEpafile(string $active_epafile): self
    {
        $this->active_epafile = $active_epafile;

        return $this;
    }

    public function getCodeEpafile(): ?string
    {
        return $this->code_epafile;
    }

    public function setCodeEpafile(?string $code_epafile): self
    {
        $this->code_epafile = $code_epafile;

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
