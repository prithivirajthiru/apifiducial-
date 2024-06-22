<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoxRepository")
 */
class Box
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $boxId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $attorney;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBoxId(): ?string
    {
        return $this->boxId;
    }

    public function setBoxId(?string $boxId): self
    {
        $this->boxId = $boxId;

        return $this;
    }

    public function getAttorney(): ?int
    {
        return $this->attorney;
    }

    public function setAttorney(?int $attorney): self
    {
        $this->attorney = $attorney;

        return $this;
    }
}
