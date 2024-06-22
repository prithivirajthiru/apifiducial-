<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataRequestRepository")
 */
class DataRequest
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date_request;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateupd_request;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DataUserspace", mappedBy="id_request", cascade={"persist", "remove"})
     */
    private $dataUserspace;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DataRequestRequeststatus", mappedBy="id_request", orphanRemoval=true)
     */
    private $dataRequestRequeststatuses;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DataClient", inversedBy="dataRequest", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefRequeststatus")
     */
    private $requeststatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $requestRef;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstOpening;

    public function __construct()
    {
        $this->dataRequestRequeststatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
   
    public function getDateRequest(): ?\DateTimeInterface
    {
        return $this->date_request;
    }

    public function setDateRequest(\DateTimeInterface $date_request): self
    {
        $this->date_request = $date_request;

        return $this;
    }

    public function getDateupdRequest(): ?\DateTimeInterface
    {
        return $this->dateupd_request;
    }

    public function setDateupdRequest(\DateTimeInterface $dateupd_request): self
    {
        $this->dateupd_request = $dateupd_request;

        return $this;
    }

    public function getDataUserspace(): ?DataUserspace
    {
        return $this->dataUserspace;
    }

    public function setDataUserspace(DataUserspace $dataUserspace): self
    {
        $this->dataUserspace = $dataUserspace;

        // set the owning side of the relation if necessary
        if ($this !== $dataUserspace->getIdRequest()) {
            $dataUserspace->setIdRequest($this);
        }

        return $this;
    }

    /**
     * @return Collection|DataRequestRequeststatus[]
     */
    public function getDataRequestRequeststatuses(): Collection
    {
        return $this->dataRequestRequeststatuses;
    }

    public function addDataRequestRequeststatus(DataRequestRequeststatus $dataRequestRequeststatus): self
    {
        if (!$this->dataRequestRequeststatuses->contains($dataRequestRequeststatus)) {
            $this->dataRequestRequeststatuses[] = $dataRequestRequeststatus;
            $dataRequestRequeststatus->setIdRequest($this);
        }

        return $this;
    }

    public function removeDataRequestRequeststatus(DataRequestRequeststatus $dataRequestRequeststatus): self
    {
        if ($this->dataRequestRequeststatuses->contains($dataRequestRequeststatus)) {
            $this->dataRequestRequeststatuses->removeElement($dataRequestRequeststatus);
            // set the owning side to null (unless already changed)
            if ($dataRequestRequeststatus->getIdRequest() === $this) {
                $dataRequestRequeststatus->setIdRequest(null);
            }
        }

        return $this;
    }

    public function getClient(): ?DataClient
    {
        return $this->client;
    }

    public function setClient(DataClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getRequeststatus(): ?RefRequeststatus
    {
        return $this->requeststatus;
    }

    public function setRequeststatus(?RefRequeststatus $requeststatus): self
    {
        $this->requeststatus = $requeststatus;

        return $this;
    }

    public function getRequestRef(): ?int
    {
        return $this->requestRef;
    }

    public function setRequestRef(?int $requestRef): self
    {
        $this->requestRef = $requestRef;

        return $this;
    }

    public function getFirstOpening(): ?string
    {
        return $this->firstOpening;
    }

    public function setFirstOpening(?string $firstOpening): self
    {
        $this->firstOpening = $firstOpening;

        return $this;
    }
}
