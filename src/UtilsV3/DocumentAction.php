<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class DocumentAction{
    private $id;

    private $name;

    private $desc_document_action;

    private $document_url;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescDocumentAction(): ?string
    {
        return $this->desc_document_action;
    }

    public function setDescDocumentAction(?string $desc_document_action): self
    {
        $this->desc_document_action = $desc_document_action;

        return $this;
    }
    public function getDocumentUrl(): ?string
    {
        return $this->document_url;
    }

    public function setDocumentUrl(?string $document_url): self
    {
        $this->document_url = $document_url;

        return $this;
    }
}