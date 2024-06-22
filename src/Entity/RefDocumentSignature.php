<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefDocumentSignatureRepository")
 */
class RefDocumentSignature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefDocument")
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_document;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocument(): ?RefDocument
    {
        return $this->document;
    }

    public function setDocument(?RefDocument $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getUrlDocument(): ?string
    {
        return $this->url_document;
    }

    public function setUrlDocument(?string $url_document): self
    {
        $this->url_document = $url_document;

        return $this;
    }
}
