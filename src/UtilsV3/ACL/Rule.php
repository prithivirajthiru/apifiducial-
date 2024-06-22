<?php

namespace App\UtilsV3\ACL;
use App\Entity\RefAction;

class Rule{
  
    private $id;

    private $action;

    private $Role;

    private $desc_rule;

    private $status;

    private $vesion;

    private $visibility;

   
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?RefAction
    {
        return $this->action;
    }

    public function setAction(?RefAction $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getRole(): ?DataRole
    {
        return $this->Role;
    }

    public function setRole(?DataRole $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    public function getDescRule(): ?string
    {
        return $this->desc_rule;
    }

    public function setDescRule(?string $desc_rule): self
    {
        $this->desc_rule = $desc_rule;

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

    public function getVesion(): ?string
    {
        return $this->vesion;
    }

    public function setVesion(?string $vesion): self
    {
        $this->vesion = $vesion;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(?bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }
   
}