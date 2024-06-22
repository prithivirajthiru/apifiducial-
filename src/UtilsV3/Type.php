<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Type{
    private $type1;
    private $type2;
    private $type3;

    public function getType1(): ?array
    {
        return $this->type1;
    }

    public function setType1(?array $type1): self
    {
        $this->type1 = $type1;

        return $this;
    }

    public function getType2(): ?array
    {
        return $this->type2;
    }

    public function setType2(?array $type2): self
    {
        $this->type2 = $type2;

        return $this;
    }

    public function getType3(): ?array
    {
        return $this->type3;
    }

    public function setType3(?array $type3): self
    {
        $this->type3 = $type3;

        return $this;
    }

}