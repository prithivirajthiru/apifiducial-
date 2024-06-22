<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataFidRepository")
 */
class DataFid
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefFid")
     */
    private $fid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $yesOrNo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?DataClient
    {
        return $this->client;
    }

    public function setClient(?DataClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getFid(): ?RefFid
    {
        return $this->fid;
    }

    public function setFid(?RefFid $fid): self
    {
        $this->fid = $fid;

        return $this;
    }
    public function getYesOrNo(): ?string
    {
        return $this->yesOrNo;
    }

    public function setYesOrNo(?string $yesOrNo): self
    {
        $this->yesOrNo = $yesOrNo;

        return $this;
    }
}
