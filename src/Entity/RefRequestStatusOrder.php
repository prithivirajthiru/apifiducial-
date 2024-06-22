<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefRequestStatusOrderRepository")
 */
class RefRequestStatusOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $levelCode;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $actionCode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCto;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRetourN0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAvisN2n0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRetourN1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAvisN2n1;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $initialStatusCode;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $nextStatusCodeExist;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $nextStatusCodeNew;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevelCode(): ?string
    {
        return $this->levelCode;
    }

    public function setLevelCode(string $levelCode): self
    {
        $this->levelCode = $levelCode;

        return $this;
    }

    public function getActionCode(): ?string
    {
        return $this->actionCode;
    }

    public function setActionCode(string $actionCode): self
    {
        $this->actionCode = $actionCode;

        return $this;
    }

    public function getIsCto(): ?bool
    {
        return $this->isCto;
    }

    public function setIsCto(bool $isCto): self
    {
        $this->isCto = $isCto;

        return $this;
    }

    public function getIsRetourN0(): ?bool
    {
        return $this->isRetourN0;
    }

    public function setIsRetourN0(bool $isRetourN0): self
    {
        $this->isRetourN0 = $isRetourN0;

        return $this;
    }

    public function getIsAvisN2N0(): ?bool
    {
        return $this->isAvisN2n0;
    }

    public function setIsAvisN2N0(bool $isAvisN2n0): self
    {
        $this->isAvisN2n0 = $isAvisN2n0;

        return $this;
    }

    public function getIsRetourN1(): ?bool
    {
        return $this->isRetourN1;
    }

    public function setIsRetourN1(bool $isRetourN1): self
    {
        $this->isRetourN1 = $isRetourN1;

        return $this;
    }

    public function getIsAvisN2N1(): ?bool
    {
        return $this->isAvisN2n1;
    }

    public function setIsAvisN2N1(bool $isAvisN2n1): self
    {
        $this->isAvisN2n1 = $isAvisN2n1;

        return $this;
    }

    public function getInitialStatusCode(): ?string
    {
        return $this->initialStatusCode;
    }

    public function setInitialStatusCode(string $initialStatusCode): self
    {
        $this->initialStatusCode = $initialStatusCode;

        return $this;
    }

    public function getNextStatusCodeExist(): ?string
    {
        return $this->nextStatusCodeExist;
    }

    public function setNextStatusCodeExist(string $nextStatusCodeExist): self
    {
        $this->nextStatusCodeExist = $nextStatusCodeExist;

        return $this;
    }

    public function getNextStatusCodeNew(): ?string
    {
        return $this->nextStatusCodeNew;
    }

    public function setNextStatusCodeNew(string $nextStatusCodeNew): self
    {
        $this->nextStatusCodeNew = $nextStatusCodeNew;

        return $this;
    }
}
