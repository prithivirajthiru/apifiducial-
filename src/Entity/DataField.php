<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataFieldRepository")
 */
class DataField
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefField")
     */
    private $ref_field;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $iscorrect;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userlogin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRequest")
     */
    private $datarequest;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefField(): ?RefField
    {
        return $this->ref_field;
    }

    public function setRefField(?RefField $ref_field): self
    {
        $this->ref_field = $ref_field;

        return $this;
    }

    public function getIscorrect(): ?bool
    {
        return $this->iscorrect;
    }

    public function setIscorrect(?bool $iscorrect): self
    {
        $this->iscorrect = $iscorrect;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getUserlogin(): ?string
    {
        return $this->userlogin;
    }

    public function setUserlogin(?string $userlogin): self
    {
        $this->userlogin = $userlogin;

        return $this;
    }

    public function getDatarequest(): ?DataRequest
    {
        return $this->datarequest;
    }

    public function setDatarequest(?DataRequest $datarequest): self
    {
        $this->datarequest = $datarequest;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
