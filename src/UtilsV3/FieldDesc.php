<?php

namespace App\UtilsV3;


class FieldDesc{

    private $field_name;
    private $current_value;
    private $desc_fieldissue;

   


    public function getFieldName(): ?string
    {
        return $this->field_name;
    }

    public function setFieldName(?string $field_name): self
    {
        $this->field_name = $field_name;

        return $this;
    }
    public function getCurrentValue(): ?string
    {
        return $this->current_value;
    }

    public function setCurrentValue(?string $current_value): self
    {
        $this->current_value = $current_value;

        return $this;
    }
    public function getDescFieldIssue(): ?string
    {
        return $this->desc_fieldissue;
    }

    public function setDescFieldIssue(?string $desc_fieldissue): self
    {
        $this->desc_fieldissue = $desc_fieldissue;

        return $this;
    }

   


}

class Category{

    private $company_filelds_issue;
    private $shareholders_fields_issue;
    private $files_issue;
   

    public function getShareholdersFieldsIssue(): ?array
    {
        return $this->shareholders_fields_issue;
    }

    public function setShareholdersFieldsIssue(?array $shareholders_fields_issue): self
    {
        $this->shareholders_fields_issue = $shareholders_fields_issue;

        return $this;
    }

    public function getCompanyFileldsIssue(): ?array
    {
        return $this->company_filelds_issue;
    }

    public function setCompanyFileldsIssue(?array $company_filelds_issue): self
    {
        $this->company_filelds_issue = $company_filelds_issue;

        return $this;
    }

    public function getFilesIssue(): ?array
    {
        return $this->files_issue;
    }

    public function setFilesIssue(?array $files_issue): self
    {
        $this->files_issue = $files_issue;

        return $this;
    }
   

}