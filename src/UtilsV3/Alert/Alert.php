<?php

namespace App\UtilsV3\Alert;

class Alert{

    private $ape;
    private $appli;
    private $code;
    private $pppm;
    private $pays;
    private $nom;
    private $prenom;
   
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
    public function getApe(): ?array
    {
        return $this->ape;
    }

    public function setApe(?array $ape): self
    {
        $this->ape = $ape;

        return $this;
    }
    public function getPays(): ?array
    {
        return $this->pays;
    }

    public function setPays(?array $pays): self
    {
        $this->pays = $pays;

        return $this;
    }
    public function getAppli(): ?array
    {
        return $this->appli;
    }

    public function setAppli(?array $appli): self
    {
        $this->appli = $appli;

        return $this;
    }
    public function getPPPM(): ?array
    {
        return $this->pppm;
    }

    public function setPPPM(?array $pppm): self
    {
        $this->pppm = $pppm;

        return $this;
    }
   
}