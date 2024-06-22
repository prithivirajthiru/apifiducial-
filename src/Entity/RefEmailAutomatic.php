<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefEmailAutomaticRepository")
 */
class RefEmailAutomatic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statuscode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EmailAction")
     * @ORM\JoinColumn(nullable=true)
     */
    private $emailAction;

    
    public function getId(): ?int
    {
        return $this->id;
    }

   
    public function getStatuscode(): ?string
    {
        return $this->statuscode;
    }

    public function setStatuscode(?string $statuscode): self
    {
        $this->statuscode = $statuscode;

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
