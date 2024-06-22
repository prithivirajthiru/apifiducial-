<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataTreatmentRepository")
 */
class DataTreatment
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
     * @ORM\ManyToOne(targetEntity="App\Entity\RefRequeststatus")
     */
    private $fromStatus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefRequeststatus")
     */
    private $toStatus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $delay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $login;

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

    public function getFromStatus(): ?RefRequeststatus
    {
        return $this->fromStatus;
    }

    public function setFromStatus(?RefRequeststatus $fromStatus): self
    {
        $this->fromStatus = $fromStatus;

        return $this;
    }

    public function getToStatus(): ?RefRequeststatus
    {
        return $this->toStatus;
    }

    public function setToStatus(?RefRequeststatus $toStatus): self
    {
        $this->toStatus = $toStatus;

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

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(?int $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }
}
