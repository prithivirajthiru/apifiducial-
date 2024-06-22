<?php

namespace App\Utils;


class CompanyType  
{
    private $companyId;
    private $name;
    private $legalForms;

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): self
    {
        $this->companyId = $companyId;

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



    public function getLegalForms(): ?array
    {
        return $this->legalForms;
    }

    public function setLegalForms(?array $legalForms): self
    {
        $this->legalForms = $legalForms;

        return $this;
    }
}



class LegalForm  
{
    private $legalformId;
    private $name;

    public function getLegalformId(): ?int
    {
        return $this->legalformId;
    }

    public function setLegalformId(?int $legalformId): self
    {
        $this->legalformId = $legalformId;

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
}