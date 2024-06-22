<?php

namespace App\UtilsSer;

class Legalform{
    private $id;

    private $id_company;

    private $code_legalform;

    private $desc_legalform;

    private $active_legalform;

    private $refLabels;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCodeLegalform(): ?string
    {
        return $this->code_legalform;
    }

    public function setCodeLegalform(string $code_legalform): self
    {
        $this->code_legalform = $code_legalform;

        return $this;
    }

    public function getDescLegalform(): ?string
    {
        return $this->desc_legalform;
    }

    public function setDescLegalform(string $desc_legalform): self
    {
        $this->desc_legalform = $desc_legalform;

        return $this;
    }

    public function getActiveLegalform(): ?string
    {
        return $this->active_legalform;
    }

    public function setActiveLegalform(string $active_legalform): self
    {
        $this->active_legalform = $active_legalform;

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