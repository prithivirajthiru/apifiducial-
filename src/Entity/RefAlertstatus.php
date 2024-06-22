<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefAlertstatusRepository")
 */
class RefAlertstatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_alertstatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_alertstatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_alertstatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeAlertstatus(): ?string
    {
        return $this->code_alertstatus;
    }

    public function setCodeAlertstatus(string $code_alertstatus): self
    {
        $this->code_alertstatus = $code_alertstatus;

        return $this;
    }

    public function getDescAlertstatus(): ?string
    {
        return $this->desc_alertstatus;
    }

    public function setDescAlertstatus(string $desc_alertstatus): self
    {
        $this->desc_alertstatus = $desc_alertstatus;

        return $this;
    }

    public function getActiveAlertstatus(): ?string
    {
        return $this->active_alertstatus;
    }

    public function setActiveAlertstatus(string $active_alertstatus): self
    {
        $this->active_alertstatus = $active_alertstatus;

        return $this;
    }
}
