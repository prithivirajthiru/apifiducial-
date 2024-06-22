<?php

namespace App\Entity;
use App\UtilsSer\Application;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataLockRepository")
 */
class DataLock
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRequest")
     */
    private $request;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dlock;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;
    private $request_id;
    private $dateTime;
    private $dataRequest;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?DataRequest
    {
        return $this->request;
    }

    public function setRequest(?DataRequest $request): self
    {
        $this->request = $request;

        return $this;
    }
    

    public function getDlock(): ?bool
    {
        return $this->dlock;
    }

    public function setDlock(?bool $dlock): self
    {
        $this->dlock = $dlock;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function getRequestId(): ?int
    {
        return $this->request_id;
    }

    public function setRequestId(?int $request_id): self
    {
        $this->request_id = $request_id;

        return $this;
    }
    public function getDateTime(): ?string
    {
        return $this->dateTime;
    }

    public function setDateTime(?string $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }
    public function getDataRequest(): ?Application
    {
        return $this->dataRequest;
    }

    public function setDataRequest(?Application $dataRequest): self
    {
        $this->dataRequest = $dataRequest;

        return $this;
    }

}
