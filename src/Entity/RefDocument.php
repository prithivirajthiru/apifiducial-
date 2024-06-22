<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefDocumentRepository")
 */
class RefDocument
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
    private $code_document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_document;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_document;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mandatory_document;

       
    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    private $code_id;
    private $document_signature;
   
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
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCodeDocument(): ?string
    {
        return $this->code_document;
    }

    public function setCodeDocument(string $code_document): self
    {
        $this->code_document = $code_document;

        return $this;
    }

    public function getDescDocument(): ?string
    {
        return $this->desc_document;
    }

    public function setDescDocument(string $desc_document): self
    {
        $this->desc_document = $desc_document;

        return $this;
    }

    public function getActiveDocument(): ?string
    {
        return $this->active_document;
    }

    public function setActiveDocument(string $active_document): self
    {
        $this->active_document = $active_document;

        return $this;
    }

    public function getMandatoryDocument(): ?bool
    {
        return $this->mandatory_document;
    }

    public function setMandatoryDocument(bool $mandatory_document): self
    {
        $this->mandatory_document = $mandatory_document;

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
    public function getDocumentSignature(): ?array
    {
        return $this->document_signature;
    }
    public function setDocumentSignature(array $document_signature): self
    {
        $this->document_signature = $document_signature;

        return $this;
    }
}
