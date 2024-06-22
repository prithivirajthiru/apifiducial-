<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefLanguageRepository")
 */
class RefLanguage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_language;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status_language;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RefLabel", mappedBy="lang_label", orphanRemoval=true)
     */
    private $refLabels;

    public function __construct()
    {
        $this->refLabels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeLanguage(): ?string
    {
        return $this->code_language;
    }

    public function setCodeLanguage(string $code_language): self
    {
        $this->code_language = $code_language;

        return $this;
    }

    public function getDescLanguage(): ?string
    {
        return $this->desc_language;
    }

    public function setDescLanguage(string $desc_language): self
    {
        $this->desc_language = $desc_language;

        return $this;
    }

    public function getStatusLanguage(): ?string
    {
        return $this->status_language;
    }

    public function setStatusLanguage(string $status_language): self
    {
        $this->status_language = $status_language;

        return $this;
    }

    /**
     * @return Collection|RefLabel[]
     */
    public function getRefLabels(): Collection
    {
        return $this->refLabels;
    }

    public function addRefLabel(RefLabel $refLabel): self
    {
        if (!$this->refLabels->contains($refLabel)) {
            $this->refLabels[] = $refLabel;
            $refLabel->setLangLabel($this);
        }

        return $this;
    }

    public function removeRefLabel(RefLabel $refLabel): self
    {
        if ($this->refLabels->contains($refLabel)) {
            $this->refLabels->removeElement($refLabel);
            // set the owning side to null (unless already changed)
            if ($refLabel->getLangLabel() === $this) {
                $refLabel->setLangLabel(null);
            }
        }

        return $this;
    }
}
