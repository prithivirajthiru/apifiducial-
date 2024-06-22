<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataFieldIssueRepository")
 */
class DataFieldIssue
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
     * @ORM\ManyToOne(targetEntity="App\Entity\RefType")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $field_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $current_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_fieldissue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    private $client_id;

    private $type_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $field_lable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefField")
     */
    private $field;
    private $field_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataAttorney")
     */
    private $attorney;
    private $attorney_id;
    private $field_map;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $clientCorrection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;
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

    public function getType(): ?RefType
    {
        return $this->type;
    }

    public function setType(?RefType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFieldName(): ?string
    {
        return $this->field_name;
    }

    public function setFieldName(?string $field_name): self
    {
        $this->field_name = $field_name;

        return $this;
    }

    public function getCurrentValue(): ?string
    {
        return $this->current_value;
    }

    public function setCurrentValue(?string $current_value): self
    {
        $this->current_value = $current_value;

        return $this;
    }

    public function getDescFieldissue(): ?string
    {
        return $this->desc_fieldissue;
    }

    public function setDescFieldissue(?string $desc_fieldissue): self
    {
        $this->desc_fieldissue = $desc_fieldissue;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): self
    {
        $this->version = $version;

        return $this;
    }
    public function getClientId(): ?int
    {
        return $this->client_id;
    }
    public function setClientId(?int $client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getTypeId(): ?int
    {
        return $this->type_id;
    }
    public function setTypeId(?int $type_id): self
    {
        $this->type_id = $type_id;

        return $this;
    }

    public function getFieldLable(): ?string
    {
        return $this->field_lable;
    }

    public function setFieldLable(?string $field_lable): self
    {
        $this->field_lable = $field_lable;

        return $this;
    }

    public function getField(): ?RefField
    {
        return $this->field;
    }

    public function setField(?RefField $field): self
    {
        $this->field = $field;

        return $this;
    }
    public function getFieldId(): ?int
    {
        return $this->field_id;
    }

    public function setFieldId(?int $field_id): self
    {
        $this->field_id = $field_id;

        return $this;
    }

    public function getAttorney(): ?DataAttorney
    {
        return $this->attorney;
    }

    public function setAttorney(?DataAttorney $attorney): self
    {
        $this->attorney = $attorney;

        return $this;
    }

    public function getAttorneyId(): ?int
    {
        return $this->attorney_id;
    }

    public function setAttorneyId(?int $attorney_id): self
    {
        $this->attorney_id = $attorney_id;

        return $this;
    }
    public function getFieldMap(): ?string
    {
        return $this->field_map;
    }

    public function setFieldMap(?string $field_map): self
    {
        $this->field_map = $field_map;

        return $this;
    }

    public function getClientCorrection(): ?bool
    {
        return $this->clientCorrection;
    }

    public function setClientCorrection(?bool $clientCorrection): self
    {
        $this->clientCorrection = $clientCorrection;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
