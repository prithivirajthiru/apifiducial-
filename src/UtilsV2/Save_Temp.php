<?php
namespace App\UtilsV2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Save_Temp{
    private $html;
    private $css;

     public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(?string $css): self
    {
        $this->css = $css;

        return $this;
    }
}
class Data{
    private $template_data;
    public function getTemplateData()
    {
        return $this->template_data;
    }

    public function setTemplateData($template_data): self
    {
        $this->template_data = $template_data;

        return $this;
    }
}
