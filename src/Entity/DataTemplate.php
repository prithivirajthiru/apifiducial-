<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataTemplateRepository")
 */
class DataTemplate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $template_data;

    /**
     * @ORM\Column(type="string", length=10001, nullable=true)
     */
    private $html;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $css;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EmailAction")
     */
    private $action_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cc_template;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subject_template;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefEmailid")
     */
    private $from_template;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bcc_template;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefSignature")
     */
    private $signature;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $attachmentTemplate;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemplateData(): ?array
    {
        return $this->template_data;
    }

    public function setTemplateData(array $template_data): self
    {
        $this->template_data = $template_data;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(?string $css): self
    {
        $this->css = $css;

        return $this;
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

    public function getActionId(): ?EmailAction
    {
        return $this->action_id;
    }

    public function setActionId(?EmailAction $action_id): self
    {
        $this->action_id = $action_id;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCcTemplate(): ?string
    {
        return $this->cc_template;
    }

    public function setCcTemplate(?string $cc_template): self
    {
        $this->cc_template = $cc_template;

        return $this;
    }

    public function getSubjectTemplate(): ?string
    {
        return $this->subject_template;
    }

    public function setSubjectTemplate(?string $subject_template): self
    {
        $this->subject_template = $subject_template;

        return $this;
    }

    public function getFromTemplate(): ?RefEmailid
    {
        return $this->from_template;
    }

    public function setFromTemplate(?RefEmailid $from_template): self
    {
        $this->from_template = $from_template;

        return $this;
    }

    

    public function getBccTemplate(): ?string
    {
        return $this->bcc_template;
    }

    public function setBccTemplate(?string $bcc_template): self
    {
        $this->bcc_template = $bcc_template;

        return $this;
    }

    public function getSignature(): ?RefSignature
    {
        return $this->signature;
    }

    public function setSignature(?RefSignature $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getAttachmentTemplate(): ?string
    {
        return $this->attachmentTemplate;
    }

    public function setAttachmentTemplate(?string $attachmentTemplate): self
    {
        $this->attachmentTemplate = $attachmentTemplate;

        return $this;
    }

  
}
