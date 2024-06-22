<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

    class LegStep3{
        private $client;
        private $dob;
        private $placebirth;
        private $contrybirth;
        private $nationality;
        private $american;
        private $fiscalcontry;
        private $attorney;
        private $fiscalnumber;
        private $boxId;
        public function getAttorney(): ?int
        {
            return $this->attorney;
        }
    
        public function setAttorney(?int $attorney): self
        {
            $this->attorney = $attorney;
    
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

        public function getDob(): ?string
        {
            return $this->dob;
        }
    
        public function setDob(?string $dob): self
        {
            $this->dob = $dob;
    
            return $this;
        }
        public function getPlacebirth(): ?string
        {
            return $this->placebirth;
        }
    
        public function setPlacebirth(?string $placebirth): self
        {
            $this->placebirth = $placebirth;
    
            return $this;
        }
        public function getContrybirth(): ?int
        {
            return $this->contrybirth;
        }
    
        public function setContrybirth(?int $contrybirth): self
        {
            $this->contrybirth = $contrybirth;
    
            return $this;
        }
        public function getNationality(): ?int
        {
            return $this->nationality;
        }
    
        public function setNationality(?int $nationality): self
        {
            $this->nationality = $nationality;
    
            return $this;
        }
        public function getFiscalcontry(): ?int
        {
            return $this->fiscalcontry;
        }
    
        public function setFiscalcontry(?int $fiscalcontry): self
        {
            $this->fiscalcontry = $fiscalcontry;
    
            return $this;
        }
        public function getAmerican(): ?bool
        {
            return $this->american;
        }
    
        public function setAmerican(?bool $american): self
        {
            $this->american = $american;
    
            return $this;
        }

      
        public function getFiscalnumber(): ?string
        {
            return $this->fiscalnumber;
        }
    
        public function setFiscalnumber(?string $fiscalnumber): self
        {
            $this->fiscalnumber = $fiscalnumber;
    
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