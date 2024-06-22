<?php

namespace App\UtilsV3\ACL;
use App\Entity\RefAction;

class Page{

    private $id;

    private $name;

    private $desc_page;

    private $type;

    private $status;

    private $version;

    private $actions;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getDescPage(): ?string
    {
        return $this->desc_page;
    }

    public function setDescPage(?string $desc_page): self
    {
        $this->desc_page = $desc_page;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getActions(): ?array
    {
        return $this->actions;
    }
    public function setActions(?array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

}