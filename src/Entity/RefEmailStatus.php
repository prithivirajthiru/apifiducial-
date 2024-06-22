<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefEmailStatusRepository")
 */
class RefEmailStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

   /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $statusCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusActive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $actionId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }

    public function setStatusCode(?string $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusActive(): ?string
    {
        return $this->statusActive;
    }

    public function setStatusActive(?string $statusActive): self
    {
        $this->statusActive = $statusActive;

        return $this;
    }

    public function getActionId(): ?int
    {
        return $this->actionId;
    }

    public function setActionId(?int $actionId): self
    {
        $this->actionId = $actionId;

        return $this;
    }
}
