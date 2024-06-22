<?php
namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\RefLabel;
use App\Entity\RefProduct;
use App\Entity\RefProductContent;


class RequestStatus{

    private $id;
    private $desc_requeststatus;
    private $fo_desc;
    private $visibility;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getDescRequeststatus(): ?string
    {
        return $this->desc_requeststatus;
    }

    public function setDescRequeststatus(string $desc_requeststatus): self
    {
        $this->desc_requeststatus = $desc_requeststatus;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(?string $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }
    public function getFoDesc(): ?string
    {
        return $this->fo_desc;
    }

    public function setFoDesc(?string $fo_desc): self
    {
        $this->fo_desc = $fo_desc;

        return $this;
    }

}
   