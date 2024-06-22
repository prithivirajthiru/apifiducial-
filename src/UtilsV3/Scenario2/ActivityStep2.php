<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

    class ActivityStep2{

        private $address;
        private $zipcode;
        private $city;
        private $contry;
        private $client;
        private $boxId;

        public function getAddress(): ?string
        {
            return $this->address;
        }
    
        public function setAddress(?string $address): self
        {
            $this->address = $address;
    
            return $this;
        }
        
        public function getZipcode(): ?string
        {
            return $this->zipcode;
        }
    
        public function setZipcode(?string $zipcode): self
        {
            $this->zipcode = $zipcode;
    
            return $this;
        }
        public function getClient(): ?int
        {
            return $this->client;
        }
    
        public function setClient(?int $client): self
        {
            $this->client = $client;
    
            return $this;
        }
        public function getCity(): ?string
        {
            return $this->city;
        }
    
        public function setCity(?string $city): self
        {
            $this->city = $city;
    
            return $this;
        }
        public function getContry(): ?string
        {
            return $this->contry;
        }
    
        public function setContry(?string $contry): self
        {
            $this->contry = $contry;
    
            return $this;
        }
        public function getBoxId(): ?string
        {
            return $this->boxId;
        }
    
        public function setBoxId(?string $boxId): self
        {
            $this->boxId = $boxId;
    
            return $this;
        }
    }