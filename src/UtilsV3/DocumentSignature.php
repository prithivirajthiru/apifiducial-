<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class DocumentSignature{
private $id;
private $document;

private $url_document;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getDocument(): ?int
    {
        return $this->document;
    }

    public function setDocument(?int $document): self
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