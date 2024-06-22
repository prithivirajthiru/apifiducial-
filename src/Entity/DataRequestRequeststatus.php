<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataRequestRequeststatusRepository")
 */
class DataRequestRequeststatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRequest", inversedBy="dataRequestRequeststatuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_request;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefRequeststatus", inversedBy="dataRequestRequeststatuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_requeststatus;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $login_request_requeststatus;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_request_requeststatus;

    private $date_request;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRequest(): ?DataRequest
    {
        return $this->id_request;
    }

    public function setIdRequest(?DataRequest $id_request): self
    {
        $this->id_request = $id_request;

        return $this;
    }

    public function getIdRequeststatus(): ?RefRequeststatus
    {
        return $this->id_requeststatus;
    }

    public function setIdRequeststatus(?RefRequeststatus $id_requeststatus): self
    {
        $this->id_requeststatus = $id_requeststatus;

        return $this;
    }

    public function getLoginRequestRequeststatus(): ?string
    {
        return $this->login_request_requeststatus;
    }

    public function setLoginRequestRequeststatus(string $login_request_requeststatus): self
    {
        $this->login_request_requeststatus = $login_request_requeststatus;

        return $this;
    }

    public function getDateRequestRequeststatus(): ?\DateTimeInterface
    {
        return $this->date_request_requeststatus;
    }

    public function setDateRequestRequeststatus(\DateTimeInterface $date_request_requeststatus): self
    {
        $this->date_request_requeststatus = $date_request_requeststatus;

        return $this;
    }

    public function getDateRequest(): ?string
    {
        return $this->date_request;
    }

    public function setDateRequest(string $date_request): self
    {
        $this->date_request = $date_request;

        return $this;
    }
}
