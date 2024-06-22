<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefVariableRepository")
 */
class RefVariable
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
    private $variablename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $variablelable;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $query;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preparedvalue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $script;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $objecttype;

    private $value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sample_value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isEmail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVariablename(): ?string
    {
        return $this->variablename;
    }

    public function setVariablename(?string $variablename): self
    {
        $this->variablename = $variablename;

        return $this;
    }

    public function getVariablelable(): ?string
    {
        return $this->variablelable;
    }

    public function setVariablelable(?string $variablelable): self
    {
        $this->variablelable = $variablelable;

        return $this;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getPreparedvalue(): ?string
    {
        return $this->preparedvalue;
    }

    public function setPreparedvalue(?string $preparedvalue): self
    {
        $this->preparedvalue = $preparedvalue;

        return $this;
    }

    public function getScript(): ?string
    {
        return $this->script;
    }

    public function setScript(?string $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function getObjecttype(): ?string
    {
        return $this->objecttype;
    }

    public function setObjecttype(?string $objecttype): self
    {
        $this->objecttype = $objecttype;

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

    public function getSampleValue(): ?string
    {
        return $this->sample_value;
    }

    public function setSampleValue(?string $sample_value): self
    {
        $this->sample_value = $sample_value;

        return $this;
    }

    public function getIsEmail(): ?bool
    {
        return $this->isEmail;
    }

    public function setIsEmail(?bool $isEmail): self
    {
        $this->isEmail = $isEmail;

        return $this;
    }

}
