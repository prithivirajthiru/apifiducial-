<?php

namespace App\Utils;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class RequestStatusOrder {

    private $levelCode;
    private $action;
    private $retour;
    private $avis;
    private $retourn;
    private $avisn;
    private $iscto;
    private $status;

    public function getLevelCode(): ?string
    {
        return $this->levelCode;
    }

    public function setLevelCode(string $levelCode): self
    {
        $this->levelCode = $levelCode;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getIsRetour(): ?int
    {
        return $this->retour;
    }

    public function setIsRetour(int $retour): self
    {
        $this->retour = $retour;

        return $this;
    }

    public function getIsAvis(): ?int
    {
        return $this->avis;
    }

    public function setIsAvis(int $avis): self
    {
        $this->avis = $avis;

        return $this;
    }

    public function getIsRetourn(): ?int
    {
        return $this->retourn;
    }

    public function setIsRetourn(int $retourn): self
    {
        $this->retourn = $retourn;

        return $this;
    }

    public function getIsAvisn(): ?int
    {
        return $this->avisn;
    }

    public function setIsAvisn(int $avisn): self
    {
        $this->avisn = $avisn;

        return $this;
    }

    public function getIsCto(): ?int
    {
        return $this->iscto;
    }

    public function setIsCto(int $iscto): self
    {
        $this->iscto = $iscto;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}