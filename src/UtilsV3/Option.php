<?php
namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\DataOffer;
class Option{
    private $product;

    private $cheque;

    private $tpc;
    
    private $cash;
    
    private $cards;

    private $requestRef;

    private $offer;



    public function getCheque(): ?bool
    {
        return $this->cheque;
    }

    public function setCheque(?bool $cheque): self
    {
        $this->cheque = $cheque;

        return $this;
    }

    public function getTpc(): ?bool
    {
        return $this->tpc;
    }

    public function setTpc(?bool $tpc): self
    {
        $this->tpc = $tpc;

        return $this;
    }


    public function getProduct(): ?array
    {
        return $this->product;
    }

    public function setProduct(?array $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOffer(): ?DataOffer
    {
        return $this->offer;
    }

    public function setOffer(?DataOffer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    

    public function getCards(): ?array
    {
        return $this->cards;
    }

    public function setCards(?array $cards): self
    {
        $this->cards = $cards;

        return $this;
    }

    
    public function getCash(): ?bool
    {
        return $this->cash;
    }

    public function setCash(?bool $cash): self
    {
        $this->cash = $cash;

        return $this;
    }

    public function getRequestRef(): ?int
    {
        return $this->requestRef;
    }

    public function setRequestRef(?int $requestRef): self
    {
        $this->requestRef = $requestRef;

        return $this;
    }
}


