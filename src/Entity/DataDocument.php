<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataDocumentRepository")
 */
class DataDocument
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataDocumenttype")
     */
    private $data_documenttype;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $html;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $css;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_on;

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getDocumentName(): ?string
    {
        return $this->document_name;
    }

    public function setDocumentName(?string $document_name): self
    {
        $this->document_name = $document_name;

        return $this;
    }

    public function getDataDocumenttype(): ?DataDocumenttype
    {
        return $this->data_documenttype;
    }

    public function setDataDocumenttype(?DataDocumenttype $data_documenttype): self
    {
        $this->data_documenttype = $data_documenttype;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(?string $css): self
    {
        $this->css = $css;

        return $this;
    }


    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->created_on;
    }

    public function setCreatedOn(?\DateTimeInterface $created_on): self
    {
        $this->created_on = $created_on;

        return $this;
    }
}
