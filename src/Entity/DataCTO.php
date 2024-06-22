<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataCTORepository")
 */
class DataCTO
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type_alert;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alert_desc;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $app;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataAttorney")
     */
    private $attorney;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passage;

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

    public function getTypeAlert(): ?string
    {
        return $this->type_alert;
    }

    public function setTypeAlert(?string $type_alert): self
    {
        $this->type_alert = $type_alert;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAlertDesc(): ?string
    {
        return $this->alert_desc;
    }

    public function setAlertDesc(?string $alert_desc): self
    {
        $this->alert_desc = $alert_desc;

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

    public function getApp(): ?string
    {
        return $this->app;
    }

    public function setApp(?string $app): self
    {
        $this->app = $app;

        return $this;
    }

    public function getAttorney(): ?DataAttorney
    {
        return $this->attorney;
    }

    public function setAttorney(?DataAttorney $attorney): self
    {
        $this->attorney = $attorney;

        return $this;
    }

    public function getPassage(): ?int
    {
        return $this->passage;
    }

    public function setPassage(?int $passage): self
    {
        $this->passage = $passage;

        return $this;
    }
}
