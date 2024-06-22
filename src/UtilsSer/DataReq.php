<?php

namespace App\UtilsSer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\UtilsSer\ClientData;


class DataReq
{
   
    private $id;

    private $date_request;

    private $dateupd_request;

    private $firstOpening;
    
    
   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getDateupdRequest(): ?string
    {
        return $this->dateupd_request;
    }

    public function setDateupdRequest(string $dateupd_request): self
    {
        $this->dateupd_request = $dateupd_request;

        return $this;
    }

    public function getFirstOpening(): ?string
    {
        return $this->firstOpening;
    }

    public function setFirstOpening(?string $firstOpening): self
    {
        $this->firstOpening = $firstOpening;

        return $this;
    }

  
 
}
