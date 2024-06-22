<?php

namespace App\UtilsSer;

class ClientPersonal
{
    private $name;
    private $surname;
    private $civility;
    private $requestStatus;
    private $boxId;
    private $nextBoxId;
    private $birthName;
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getSurName(): ?string
    {
        return $this->surname;
    }

    public function setSurName(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }
    public function getBirthName(): ?string
    {
        return $this->birthName;
    }

    public function setBirthName(?string $birthName): self
    {
        $this->birthName = $birthName;

        return $this;
    }
    public function getCivility(): ?int
    {
        return $this->civility;
    }

    public function setCivility(?int $civility): self
    {
        $this->civility = $civility;

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
    public function getNextBoxId(): ?string
    {
        return $this->nextBoxId;
    }

    public function setNextBoxId(?string $nextBoxId): self
    {
        $this->nextBoxId = $nextBoxId;

        return $this;
    }
    public function getRequestStatus(): ?array
    {
        return $this->requestStatus;
    }

    public function setRequestStatus(?array $requestStatus): self
    {
        $this->requestStatus = $requestStatus;

        return $this;
    }
   
}