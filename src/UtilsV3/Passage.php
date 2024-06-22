<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Passage{
    private $passage;
    private $ok;
    private $ko;

    public function getPassage(): ?int
    {
        return $this->passage;
    }

    public function setPassage(?int $passage): self
    {
        $this->passage = $passage;

        return $this;
    }

    public function getOk(): ?int
    {
        return $this->ok;
    }

    public function setOk(?int $ok): self
    {
        $this->ok = $ok;

        return $this;
    }

    public function getKo(): ?int
    {
        return $this->ko;
    }

    public function setKo(?int $ko): self
    {
        $this->ko = $ko;

        return $this;
    }

}