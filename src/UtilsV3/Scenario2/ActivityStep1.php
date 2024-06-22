<?php

namespace App\UtilsV3\Scenario2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

    class ActivityStep1{
        private $siren;
        private $legalform;
        private $epa;
        private $companyname;
        private $caption;
        private $client;
        private $boxId;
        private $dataFidArr;
        public function getClient(): ?int
        {
            return $this->client;
        }
    
        public function setClient(?int $client): self
        {
            $this->client = $client;
    
            return $this;
        }
        public function getSiren(): ?string
        {
            return $this->siren;
        }
    
        public function setSiren(?string $siren): self
        {
            $this->siren = $siren;
    
            return $this;
        }
        public function getLegalForm(): ?int
        {
            return $this->legalform;
        }
    
        public function setLegalForm(?int $legalform): self
        {
            $this->legalform = $legalform;
    
            return $this;
        }
        public function getEpa(): ?int
        {
            return $this->epa;
        }
    
        public function setEpa(?int $epa): self
        {
            $this->epa = $epa;
    
            return $this;
        }
        public function getCompanyName(): ?string
        {
            return $this->companyname;
        }
    
        public function setCompanyName(?string $companyname): self
        {
            $this->companyname = $companyname;
    
            return $this;
        }
        public function getCaption(): ?string
        {
            return $this->caption;
        }
    
        public function setCaption(?string $caption): self
        {
            $this->caption = $caption;
    
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

        public function getDataFidArr(): ?array
        {
            return $this->dataFidArr;
        }
    
        public function setDataFidArr(?array $dataFidArr): self
        {
            $this->dataFidArr = $dataFidArr;
    
            return $this;
        }

    }