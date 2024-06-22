<?php

namespace App\UtilsSer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\UtilsSer\DataReq;

class CompanyType
{
    private $id;
    private $code_companytype;
    private $desc_companytype;
    private $active_companytype;
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
    public function getCodeCompanytype(): ?string
    {
        return $this->code_companytype;
    }

    public function setCodeCompanytype(string $code_companytype): self
    {
        $this->code_companytype = $code_companytype;

        return $this;
    }

    public function getDescCompanytype(): ?string
    {
        return $this->desc_companytype;
    }

    public function setDescCompanytype(string $desc_companytype): self
    {
        $this->desc_companytype = $desc_companytype;

        return $this;
    }

    public function getActiveCompanytype(): ?string
    {
        return $this->active_companytype;
    }

    public function setActiveCompanytype(string $active_companytype): self
    {
        $this->active_companytype = $active_companytype;

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