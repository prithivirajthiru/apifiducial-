<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefLegalformfileRepository")
 */
class RefLegalformfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefLegalform", inversedBy="refLegalformfiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $legalform;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefFile", inversedBy="refLegalformfiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mandatory_legalformfile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_legalformfile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_legalformfile;


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

    public function getLegalform(): ?RefLegalform
    {
        return $this->legalform;
    }

    public function setLegalform(?RefLegalform $legalform): self
    {
        $this->legalform = $legalform;

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

    public function getMandatoryLegalformfile(): ?string
    {
        return $this->mandatory_legalformfile;
    }

    public function setMandatoryLegalformfile(string $mandatory_legalformfile): self
    {
        $this->mandatory_legalformfile = $mandatory_legalformfile;

        return $this;
    }

    public function getActiveLegalformfile(): ?string
    {
        return $this->active_legalformfile;
    }

    public function setActiveLegalformfile(string $active_legalformfile): self
    {
        $this->active_legalformfile = $active_legalformfile;

        return $this;
    }

    public function getCodeLegalformfile(): ?string
    {
        return $this->code_legalformfile;
    }

    public function setCodeLegalformfile(?string $code_legalformfile): self
    {
        $this->code_legalformfile = $code_legalformfile;

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
