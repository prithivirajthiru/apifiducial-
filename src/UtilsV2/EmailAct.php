<?php
namespace App\UtilsV2;

use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

 class EmailAct{
    private $id;
    private $title;
    private $desc_emailaction;
    private $status;
    private $code;
    private $is_automatic;
    private $is_statuschange;
    private $from_status;
    private $to_status;
    private $arraystatus;
    private $no_of_days;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescEmailaction(): ?string
    {
        return $this->desc_emailaction;
    }

    public function setDescEmailaction(?string $desc_emailaction): self
    {
        $this->desc_emailaction = $desc_emailaction;

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

     public function getArrayStatus(): ?array
    {
        return $this->arraystatus;
    }

    public function setArrayStatus(?array $arraystatus): self
    {
        $this->arraystatus = $arraystatus;

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
    public function getIsAutomatic(): ?bool
    {
        return $this->is_automatic;
    }

    public function setIsAutomatic(?bool $is_automatic): self
    {
        $this->is_automatic = $is_automatic;

        return $this;
    }

    public function getIsStatuschange(): ?bool
    {
        return $this->is_statuschange;
    }

    public function setIsStatuschange(?bool $is_statuschange): self
    {
        $this->is_statuschange = $is_statuschange;

        return $this;
    }

    public function getFromStatus(): ?int
    {
        return $this->from_status;
    }

    public function setFromStatus(?int $from_status): self
    {
        $this->from_status = $from_status;

        return $this;
    }

    public function getToStatus(): ?int
    {
        return $this->to_status;
    }

    public function setToStatus(?int $to_status): self
    {
        $this->to_status = $to_status;

        return $this;
    }
    public function getNoOfDays(): ?int
    {
        return $this->no_of_days;
    }

    public function setNoOfDays(?int $no_of_days): self
    {
        $this->no_of_days = $no_of_days;

        return $this;
    }
}