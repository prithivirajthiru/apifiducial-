<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailActionRepository")
 */
class EmailAction
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_emailaction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $is_automatic;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_statuschange;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $from_status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $to_status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $no_of_days;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $attachmentEmail;

    private $templateName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescEmailaction(): ?string
    {
        return $this->desc_emailaction;
    }

    public function setDescEmailaction(?string $desc_emailaction): self
    {
        $this->desc_emailaction = $desc_emailaction;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIsAutomatic(): ?string
    {
        return $this->is_automatic;
    }

    public function setIsAutomatic(?string $is_automatic): self
    {
        $this->is_automatic = $is_automatic;

        return $this;
    }

    public function getIsStatuschange(): ?bool
    {
        return $this->is_statuschange;
    }

    public function setIsStatuschange(?bool $is_statuschange): self
    {
        $this->is_statuschange = $is_statuschange;

        return $this;
    }

    public function getFromStatus(): ?int
    {
        return $this->from_status;
    }

    public function setFromStatus(?int $from_status): self
    {
        $this->from_status = $from_status;

        return $this;
    }

    public function getToStatus(): ?int
    {
        return $this->to_status;
    }

    public function setToStatus(?int $to_status): self
    {
        $this->to_status = $to_status;

        return $this;
    }

    public function getNoOfDays(): ?int
    {
        return $this->no_of_days;
    }

    public function setNoOfDays(?int $no_of_days): self
    {
        $this->no_of_days = $no_of_days;

        return $this;
    }

    public function getRoot(): ?int
    {
        return $this->root;
    }

    public function setRoot(?int $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function getAttachmentEmail(): ?string
    {
        return $this->attachmentEmail;
    }

    public function setAttachmentEmail(?string $attachmentEmail): self
    {
        $this->attachmentEmail = $attachmentEmail;

        return $this;
    }
    public function getTemplateName(): ?string
    {
        return $this->templateName;
    }

    public function setTemplateName(?string $templateName): self
    {
        $this->templateName = $templateName;

        return $this;
    }
}
