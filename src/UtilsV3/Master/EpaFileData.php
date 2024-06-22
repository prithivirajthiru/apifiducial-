<?php

namespace App\UtilsV3\Master;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EpaFileData{

    private $epa;

    private $file;

    private $mandatory_epafile;

    private $active_epafile;

    private $code_epafile;

    private $refLabel;

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
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpa(): ?int
    {
        return $this->epa;
    }

    public function setEpa(?int $epa): self
    {
        $this->epa = $epa;

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

    public function getMandatoryEpafile(): ?string
    {
        return $this->mandatory_epafile;
    }

    public function setMandatoryEpafile(string $mandatory_epafile): self
    {
        $this->mandatory_epafile = $mandatory_epafile;

        return $this;
    }

    public function getActiveEpafile(): ?string
    {
        return $this->active_epafile;
    }

    public function setActiveEpafile(string $active_epafile): self
    {
        $this->active_epafile = $active_epafile;

        return $this;
    }

    public function getCodeEpafile(): ?string
    {
        return $this->code_epafile;
    }

    public function setCodeEpafile(?string $code_epafile): self
    {
        $this->code_epafile = $code_epafile;

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