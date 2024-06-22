<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataRuleRepository")
 */
class DataRule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefAction")
     */
    private $action;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRole")
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_rule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vesion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visibility;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefPage")
     */
    private $page;

    
    

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
        return $this->role;
    }

    public function setRole(?DataRole $role): self
    {
        $this->role = $role;

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

    public function getPage(): ?RefPage
    {
        return $this->page;
    }

    public function setPage(?RefPage $page): self
    {
        $this->page = $page;

        return $this;
    }

  
    
}
