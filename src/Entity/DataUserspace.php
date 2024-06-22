<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataUserspaceRepository")
 */
class DataUserspace
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DataRequest", inversedBy="dataUserspace", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_request;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_us;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $password_us;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $active_us;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRequest(): ?DataRequest
    {
        return $this->id_request;
    }

    public function setIdRequest(DataRequest $id_request): self
    {
        $this->id_request = $id_request;

        return $this;
    }

    public function getEmailUs(): ?string
    {
        return $this->email_us;
    }

    public function setEmailUs(string $email_us): self
    {
        $this->email_us = $email_us;

        return $this;
    }

    public function getPasswordUs(): ?string
    {
        return $this->password_us;
    }

    public function setPasswordUs(string $password_us): self
    {
        $this->password_us = $password_us;

        return $this;
    }

    public function getActiveUs(): ?string
    {
        return $this->active_us;
    }

    public function setActiveUs(string $active_us): self
    {
        $this->active_us = $active_us;

        return $this;
    }
}
