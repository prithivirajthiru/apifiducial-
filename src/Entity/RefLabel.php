<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefLabelRepository")
 */
class RefLabel
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
    private $code_label;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefLanguage", inversedBy="refLabels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lang_label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label_label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeLabel(): ?string
    {
        return $this->code_label;
    }

    public function setCodeLabel(string $code_label): self
    {
        $this->code_label = $code_label;

        return $this;
    }

    public function getLangLabel(): ?RefLanguage
    {
        return $this->lang_label;
    }

    public function setLangLabel(?RefLanguage $lang_label): self
    {
        $this->lang_label = $lang_label;

        return $this;
    }

    public function getLabelLabel(): ?string
    {
        return $this->label_label;
    }

    public function setLabelLabel(string $label_label): self
    {
        $this->label_label = $label_label;

        return $this;
    }

    public function getActiveLabel(): ?string
    {
        return $this->active_label;
    }

    public function setActiveLabel(string $active_label): self
    {
        $this->active_label = $active_label;

        return $this;
    }
}
