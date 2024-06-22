<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class DocTempPage{

    private $id;
    private $action;

    private $documenttemplate;

    private $html;

    private $css;

    private $signature_location;

    private $pagename;

    private $status;

    private $page_order;

    private $created_on;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?int
    {
        return $this->action;
    }

    public function setAction(?int $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getDocumenttemplate(): ?int
    {
        return $this->documenttemplate;
    }

    public function setDocumenttemplate(?int $documenttemplate): self
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