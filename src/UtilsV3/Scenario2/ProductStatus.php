<?php
namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\RefLabel;
use App\Entity\RefProduct;
use App\Entity\RefProductContent;


class ProductStatus{
    private $arraystatus;

    public function getArrayStatus(): ?array
    {
        return $this->arraystatus;
    }

    public function setArrayStatus(?array $arraystatus): self
    {
        $this->arraystatus = $arraystatus;

        return $this;
    }
}
   
