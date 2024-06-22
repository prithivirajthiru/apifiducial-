<?php

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


class Eloqua{
    private $type;
    private $currentStatus;
    private $id;
    private $createdAt;
    private $depth;
    private $name;
    private $updatedAt;
    private $emailAddress;
    private $emailFormatPreference;
    private $fieldValues;
    private $isBounceback;
    private $isSubscribed;
    private $isMapped;
    private $uniqueCode;
    private $subscriptionDate;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

     public function getcurrentStatus(): ?string
    {
        return $this->currentStatus;
    }

    public function setcurrentStatus(string $currentStatus): self
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }
    

     public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

     public function getcreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setcreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getdepth(): ?string
    {
        return $this->depth;
    }

    public function setdepth(string $depth): self
    {
        $this->depth = $depth;

        return $this;
    }
    public function getname(): ?string
    {
        return $this->name;
    }

    public function setname(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getupdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setupdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function getemailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setemailAddress(string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }
     public function getfieldValues():array
    {
        return $this->fieldValues;
    }

    public function setfieldValues(array $fieldValues): self
    {
        $this->fieldValues = $fieldValues;

        return $this;
    }

    public function getemailFormatPreference(): ?string
    {
        return $this->emailFormatPreference;
    }
    public function setemailFormatPreference(string $emailFormatPreference): self
    {
        $this->emailFormatPreference = $emailFormatPreference;

        return $this;
    }

     public function getisBounceback(): ?bool
    {
        return $this->isBounceback;
    }

    public function setisBounceback(bool $isBounceback): self
    {
        $this->isBounceback = $isBounceback;

        return $this;
    }
    public function getsubscriptionDate(): ?string
    {
        return $this->subscriptionDate;
    }
    public function setsubscriptionDate(string $subscriptionDate): self
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    public function getIsMapped(): ?string
    {
        return $this->isMapped;
    }
    public function setIsMapped(string $isMapped): self
    {
        $this->isMapped = $isMapped;

        return $this;
    }

    public function getUniqueCode(): ?string
    {
        return $this->uniqueCode;
    }
    public function setUniqueCode(string $uniqueCode): self
    {
        $this->uniqueCode = $uniqueCode;

        return $this;
    }
    


    public function getisSubscribed(): ?bool
    {
        return $this->isSubscribed;
    }

    public function setisSubscribed(bool $isSubscribed): self
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

}