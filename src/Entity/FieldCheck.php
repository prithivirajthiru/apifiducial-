<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FieldCheckRepository")
 */
class FieldCheck
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
    private $fieldName;

   

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rerurnCheck;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $resumeCheck;
  

  

    public function getId(): ?int
    {
        return $this->id;
    }
   

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(?string $fieldName): self
    {
        $this->fieldName = $fieldName;

        return $this;
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getRerurnCheck(): ?bool
    {
        return $this->rerurnCheck;
    }

    public function setRerurnCheck(?bool $rerurnCheck): self
    {
        $this->rerurnCheck = $rerurnCheck;

        return $this;
    }

    public function getResumeCheck(): ?bool
    {
        return $this->resumeCheck;
    }

    public function setResumeCheck(?bool $resumeCheck): self
    {
        $this->resumeCheck = $resumeCheck;

        return $this;
    }

  
}
