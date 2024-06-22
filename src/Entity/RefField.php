<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefFieldRepository")
 */
class RefField
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
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_field;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_field;

    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $field_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $box_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $is_mandatory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $active_field;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefAlertstatus")
     */
    private $alert_status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefDocument")
     */
    private $document;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sample_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $change_access;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orderId;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $column_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $back_field;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fieldGroup;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sequenceField;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fieldOrder;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $innerGroup;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rowId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descBack;
    
   
    public function getId(): ?int
    {
        return $this->id;
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

    public function getActiveField(): ?string
    {
        return $this->active_field;
    }

    public function setActiveField(?string $active_field): self
    {
        $this->active_field = $active_field;

        return $this;
    }

    public function getAlertStatus(): ?RefAlertstatus
    {
        return $this->alert_status;
    }

    public function setAlertStatus(?RefAlertstatus $alert_status): self
    {
        $this->alert_status = $alert_status;

        return $this;
    }

    public function getDocument(): ?RefDocument
    {
        return $this->document;
    }

    public function setDocument(?RefDocument $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getSampleId(): ?int
    {
        return $this->sample_id;
    }

    public function setSampleId(?int $sample_id): self
    {
        $this->sample_id = $sample_id;

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

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getColumnName(): ?string
    {
        return $this->column_name;
    }

    public function setColumnName(?string $column_name): self
    {
        $this->column_name = $column_name;

        return $this;
    }

    public function getBackField(): ?string
    {
        return $this->back_field;
    }

    public function setBackField(?string $back_field): self
    {
        $this->back_field = $back_field;

        return $this;
    }

    public function getFieldGroup(): ?string
    {
        return $this->fieldGroup;
    }

    public function setFieldGroup(?string $fieldGroup): self
    {
        $this->fieldGroup = $fieldGroup;

        return $this;
    }

    public function getSequenceField(): ?string
    {
        return $this->sequenceField;
    }

    public function setSequenceField(?string $sequenceField): self
    {
        $this->sequenceField = $sequenceField;

        return $this;
    }

    public function getFieldOrder(): ?string
    {
        return $this->fieldOrder;
    }

    public function setFieldOrder(?string $fieldOrder): self
    {
        $this->fieldOrder = $fieldOrder;

        return $this;
    }

    public function getInnerGroup(): ?string
    {
        return $this->innerGroup;
    }

    public function setInnerGroup(?string $innerGroup): self
    {
        $this->innerGroup = $innerGroup;

        return $this;
    }

    public function getRowId(): ?int
    {
        return $this->rowId;
    }

    public function setRowId(?int $rowId): self
    {
        $this->rowId = $rowId;

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
