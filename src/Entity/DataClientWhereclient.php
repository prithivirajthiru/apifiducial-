<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataClientWhereclientRepository")
 */
class DataClientWhereclient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient", inversedBy="dataClientWhereclients")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DataClientWhereclient", mappedBy="client")
     */
    private $dataClientWhereclients;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefWhereclient", inversedBy="dataClientWhereclients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $whereclient;

    public function __construct()
    {
        $this->dataClientWhereclients = new ArrayCollection();
    }

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

    /**
     * @return Collection|self[]
     */
    public function getDataClientWhereclients(): Collection
    {
        return $this->dataClientWhereclients;
    }

    public function addDataClientWhereclient(self $dataClientWhereclient): self
    {
        if (!$this->dataClientWhereclients->contains($dataClientWhereclient)) {
            $this->dataClientWhereclients[] = $dataClientWhereclient;
            $dataClientWhereclient->setClient($this);
        }

        return $this;
    }

    public function removeDataClientWhereclient(self $dataClientWhereclient): self
    {
        if ($this->dataClientWhereclients->contains($dataClientWhereclient)) {
            $this->dataClientWhereclients->removeElement($dataClientWhereclient);
            // set the owning side to null (unless already changed)
            if ($dataClientWhereclient->getClient() === $this) {
                $dataClientWhereclient->setClient(null);
            }
        }

        return $this;
    }

    public function getWhereclient(): ?RefWhereclient
    {
        return $this->whereclient;
    }

    public function setWhereclient(?RefWhereclient $whereclient): self
    {
        $this->whereclient = $whereclient;

        return $this;
    }
}
