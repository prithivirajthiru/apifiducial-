<?php

namespace App\UtilsV2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Template{
    private $content;
    private $labels;

     public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
    public function getLabels():array
    {
        return $this->contacts;
    }

    public function setLabels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }
}