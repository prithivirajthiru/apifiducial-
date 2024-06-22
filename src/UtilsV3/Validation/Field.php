<?php

namespace App\UtilsV3\Validation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Field{


private $id;

    private $label;

 
    private $code_field;

    private $desc_field;

    private $refLabel;

    private $field_id;

    
    private $box_id;

    
    private $is_mandatory;

    private $active_field;
    /**
     * @var RefLabel
     */
    private  $refLabels ;
    
    private $code_id;

    private $alert_status;

    private $document;
    private $change_access;
    private $descBack;
  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCodeField(): ?string
    {
        return $this->code_field;
    }

    public function setCodeField(?string $code_field): self
    {
        $this->code_field = $code_field;

        return $this;
    }

    public function getDescField(): ?string
    {
        return $this->desc_field;
    }

    public function setDescField(?string $desc_field): self
    {
        $this->desc_field = $desc_field;

        return $this;
    }
    public function getFieldId(): ?string
    {
        return $this->field_id;
    }

    public function setFieldId(string $field_id): self
    {
        $this->field_id = $field_id;

        return $this;
    }

    public function getBoxId(): ?string
    {
        return $this->box_id;
    }

    public function setBoxId(?string $box_id): self
    {
        $this->box_id = $box_id;

        return $this;
    }

    public function getIsMandatory(): ?string
    {
        return $this->is_mandatory;
    }

    public function setIsMandatory(?string $is_mandatory): self
    {
        $this->is_mandatory = $is_mandatory;

        return $this;
    }

    public function getActiveField(): ?string
    {
        return $this->active_field;
    }

    public function setActiveField(?string $active_field): self
    {
        $this->active_field = $active_field;

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

    public function getCodeId(): ?int
    {
        return $this->code_id;
    }
    public function setCodeId(int $code_id): self
    {
        $this->code_id = $code_id;

        return $this;
    }
    public function getAlertStatus(): ?int
    {
        return $this->alert_status;
    }

    public function setAlertStatus(?int $alert_status): self
    {
        $this->alert_status = $alert_status;

        return $this;
    }

    public function getDocument(): ?int
    {
        return $this->document;
    }

    public function setDocument(?int $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getChangeAccess(): ?string
    {
        return $this->change_access;
    }

    public function setChangeAccess(?string $change_access): self
    {
        $this->change_access = $change_access;

        return $this;
    }

    public function getDescBack(): ?string
    {
        return $this->descBack;
    }

    public function setDescBack(?string $descBack): self
    {
        $this->descBack = $descBack;

        return $this;
    }

}