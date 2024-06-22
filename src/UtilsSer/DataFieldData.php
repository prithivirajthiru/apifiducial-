<?php

namespace App\UtilsSer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\UtilsSer\DataReq;
use App\Entity\RefField;
class DataFieldData
{
    private $id;

    private $ref_field;

    private $iscorrect;

    private $comments;

    private $userlogin;

    private $datarequest;

    private $status;

    private $date;

    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRefField(): ?RefField
    {
        return $this->ref_field;
    }

    public function setRefField(?RefField $ref_field): self
    {
        $this->ref_field = $ref_field;

        return $this;
    }

    public function getIscorrect(): ?bool
    {
        return $this->iscorrect;
    }

    public function setIscorrect(?bool $iscorrect): self
    {
        $this->iscorrect = $iscorrect;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getUserlogin(): ?string
    {
        return $this->userlogin;
    }

    public function setUserlogin(?string $userlogin): self
    {
        $this->userlogin = $userlogin;

        return $this;
    }

    public function getDatarequest(): ?DataReq
    {
        return $this->datarequest;
    }

    public function setDatarequest(?DataReq $datarequest): self
    {
        $this->datarequest = $datarequest;

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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

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
}