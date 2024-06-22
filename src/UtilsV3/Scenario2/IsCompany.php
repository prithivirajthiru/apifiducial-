<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

    class IsCompany{
        private $client;
        private $siren_attorney;
        private $nationality_attorney;
        private $function;
        private $percentage_attorney;
        private $iscompany;


        public function getClient(): ?int
        {
            return $this->client;
        }
    
        public function setClient(?int $client): self
        {
            $this->client = $client;
    
            return $this;
        }
        public function getNationalityAttorney(): ?int
        {
            return $this->nationality_attorney;
        }
    
        public function setNationalityAttorney(?int $nationality_attorney): self
        {
            $this->nationality_attorney = $nationality_attorney;
    
            return $this;
        }
    
        public function getSirenAttorney(): ?string
    {
        return $this->siren_attorney;
    }

    public function setSirenAttorney(?string $siren_attorney): self
    {
        $this->siren_attorney = $siren_attorney;

        return $this;
    }
        public function getFunction(): ?int
        {
            return $this->function;
        }
    
        public function setFunction(?int $function): self
        {
            $this->function = $function;
    
            return $this;
        }

        public function getPercentageAttorney(): ?float
    {
        return $this->percentage_attorney;
    }

    public function setPercentageAttorney(?float $percentage_attorney): self
    {
        $this->percentage_attorney = $percentage_attorney;

        return $this;
    }

    public function getIscompany(): ?bool
    {
        return $this->iscompany;
    }

    public function setIscompany(?bool $iscompany): self
    {
        $this->iscompany = $iscompany;

        return $this;
    }
    }