<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataRelanceRepository")
 */
class DataRelance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRequest")
     */
    private $dataRequest;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRelance;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\EmailAction")
    * @ORM\JoinColumn(nullable=true)
    */
   private $emailAction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataRequest(): ?DataRequest
    {
        return $this->dataRequest;
    }

    public function setDataRequest(?DataRequest $dataRequest): self
    {
        $this->dataRequest = $dataRequest;

        return $this;
    }

    public function getDateRelance(): ?\DateTimeInterface
    {
        return $this->dateRelance;
    }

    public function setDateRelance(?\DateTimeInterface $dateRelance): self
    {
        $this->dateRelance = $dateRelance;

        return $this;
    }

    public function getEmailAction(): ?EmailAction
    {
        return $this->emailAction;
    }

    public function setEmailAction(?EmailAction $emailAction): self
    {
        $this->emailAction = $emailAction;

        return $this;
    }
    
}
