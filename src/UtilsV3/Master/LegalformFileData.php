<?php

namespace App\UtilsV3\Master;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class LegalformFileData{

    private $legalform;

    
    private $file;

   
    private $mandatory_legalformfile;

    
    private $active_legalformfile;

    
    private $code_legalformfile;


     /**
     * @var RefLabel
     */
    private  $refLabels ;

    private $code_id;

   
    public function getCodeId(): ?int
    {
        return $this->code_id;
    }
    public function setCodeId(int $code_id): self
    {
        $this->code_id = $code_id;

        return $this;
    }


    public function getLegalform(): ?int
    {
        return $this->legalform;
    }

    public function setLegalform(?int $legalform): self
    {
        $this->legalform = $legalform;

        return $this;
    }

    public function getFile(): ?int
    {
        return $this->file;
    }

    public function setFile(?int $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getMandatoryLegalformfile(): ?string
    {
        return $this->mandatory_legalformfile;
    }

    public function setMandatoryLegalformfile(string $mandatory_legalformfile): self
    {
        $this->mandatory_legalformfile = $mandatory_legalformfile;

        return $this;
    }

    public function getActiveLegalformfile(): ?string
    {
        return $this->active_legalformfile;
    }

    public function setActiveLegalformfile(string $active_legalformfile): self
    {
        $this->active_legalformfile = $active_legalformfile;

        return $this;
    }

    public function getCodeLegalformfile(): ?string
    {
        return $this->code_legalformfile;
    }

    public function setCodeLegalformfile(?string $code_legalformfile): self
    {
        $this->code_legalformfile = $code_legalformfile;

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
}