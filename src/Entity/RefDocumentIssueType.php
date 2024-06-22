<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefDocumentIssueTypeRepository")
 */
class RefDocumentIssueType
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_document_issue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_documentissue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $active_documentissue;

    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCodeDocumentIssue(): ?string
    {
        return $this->code_document_issue;
    }

    public function setCodeDocumentIssue(?string $code_document_issue): self
    {
        $this->code_document_issue = $code_document_issue;

        return $this;
    }

    public function getDescDocumentissue(): ?string
    {
        return $this->desc_documentissue;
    }

    public function setDescDocumentissue(?string $desc_documentissue): self
    {
        $this->desc_documentissue = $desc_documentissue;

        return $this;
    }

    public function getActiveDocumentissue(): ?string
    {
        return $this->active_documentissue;
    }

    public function setActiveDocumentissue(?string $active_documentissue): self
    {
        $this->active_documentissue = $active_documentissue;

        return $this;
    }
    public function getRefLabel(): ?string
    {
        return $this->refLabel;
    }

    public function setRefLabel(string $refLabel): self
    {
        $this->refLabel = $refLabel;

        return $this;
    }
     public function getRefLabels(): ?array
    {
        return $this->refLabels;
    }

    public function setRefLabels(array $refLabels): self
    {
        $this->refLabels = $refLabels;

        return $this;
    }
}
