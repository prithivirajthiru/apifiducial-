<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataRequestFileRepository")
 */
class DataRequestFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefFile")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRequest")
     */
    private $request;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataAttorney")
     */
    private $attorney;
    private $iskbis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file_uuid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }


    public function getFile(): ?RefFile
    {
        return $this->file;
    }

    public function setFile(?RefFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getRequest(): ?DataRequest
    {
        return $this->request;
    }

    public function setRequest(?DataRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

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

    public function getIsKbis(): ?bool
    {
        return $this->iskbis;
    }

    public function setIsKbis(?bool $iskbis): self
    {
        $this->iskbis = $iskbis;

        return $this;
    }

    public function getFileUuid(): ?string
    {
        return $this->file_uuid;
    }

    public function setFileUuid(?string $file_uuid): self
    {
        $this->file_uuid = $file_uuid;

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
