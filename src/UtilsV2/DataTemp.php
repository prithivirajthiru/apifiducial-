<?php
namespace App\UtilsV2;

use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\EmailAction;

 class DataTemp
    {
   
    private $id;
    private $template_data;
    private $html;
    private $css;
    private $title;
    private $action_id;
    private $code;
    private $status;
    private $from_template;
    private $cc_template;
    private $bcc_template;
    private $subject_template;
    private $signature;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTemplateData()
    {
        return $this->template_data;
    }

    public function setTemplateData($template_data): self
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

    public function getActionId(): ?int
    {
        return $this->action_id;
    }

    public function setActionId(?int $action_id): self
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
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    public function getFromTemplate(): ?int
    {
        return $this->from_template;
    }

    public function setFromTemplate(?int $from_template): self
    {
        $this->from_template = $from_template;

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
    public function getBccTemplate(): ?string
    {
        return $this->bcc_template;
    }

    public function setBccTemplate(?string $bcc_template): self
    {
        $this->bcc_template = $bcc_template;

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
    public function getSignature(): ?int
    {
        return $this->signature;
    }
    public function setSignature(int $signature): self
    {
        $this->signature = $signature;

        return $this;
    }
    
 }