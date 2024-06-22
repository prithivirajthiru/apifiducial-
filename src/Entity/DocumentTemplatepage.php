<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentTemplatepageRepository")
 */
class DocumentTemplatepage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataDocumentaction")
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentTemplate")
     */
    private $documenttemplate;

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
    private $signature_location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pagename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $page_order;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_on;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?DataDocumentaction
    {
        return $this->action;
    }

    public function setAction(?DataDocumentaction $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getDocumenttemplate(): ?DocumentTemplate
    {
        return $this->documenttemplate;
    }

    public function setDocumenttemplate(?DocumentTemplate $documenttemplate): self
    {
        $this->documenttemplate = $documenttemplate;

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

    public function getSignatureLocation(): ?string
    {
        return $this->signature_location;
    }

    public function setSignatureLocation(?string $signature_location): self
    {
        $this->signature_location = $signature_location;

        return $this;
    }

    public function getPagename(): ?string
    {
        return $this->pagename;
    }

    public function setPagename(?string $pagename): self
    {
        $this->pagename = $pagename;

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

    public function getPageOrder(): ?int
    {
        return $this->page_order;
    }

    public function setPageOrder(?int $page_order): self
    {
        $this->page_order = $page_order;

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
