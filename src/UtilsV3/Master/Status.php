<?php

namespace App\UtilsV3\Master;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Status{

    private $status;
    private $is_automatic;
    private $id;
    private $date;
    public function getId(): ?array
    {
        return $this->id;
    }
    public function setId(array $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getDate(): ?array
    {
        return $this->date;
    }
    public function setDate(array $date): self
    {
        $this->date = $date;

        return $this;
    }
    public function getStatus(): ?array
    {
        return $this->status;
    }
    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsAutomatic(): ?array
    {
        return $this->is_automatic;
    }

    public function setIsAutomatic(?array $is_automatic): self
    {
        $this->is_automatic = $is_automatic;

        return $this;
    }
}
