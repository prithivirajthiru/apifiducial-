<?php

namespace App\UtilsV3\ACLV1;
use App\Entity\RefAction;

class Rule{

private $id;
private $action;
private $role;
private $desc_rule;
private $status;
private $version;
private $visibility;
private $page;




public function getId(): ?int
{
    return $this->id;
}
public function setId(?int $id): self
{
    $this->id = $id;

    return $this;
}
public function getAction(): ?int
{
    return $this->action;
}

public function setAction(?int $action): self
{
    $this->action = $action;

    return $this;
}

public function getRole(): ?int
{
    return $this->role;
}

public function setRole(?int $role): self
{
    $this->role = $role;

    return $this;
}

public function getDescRule(): ?string
{
    return $this->desc_rule;
}

public function setDescRule(?string $desc_rule): self
{
    $this->desc_rule = $desc_rule;

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

public function getVesion(): ?string
{
    return $this->version;
}

public function setVesion(?string $version): self
{
    $this->version = $version;

    return $this;
}

public function getVisibility(): ?bool
{
    return $this->visibility;
}

public function setVisibility(?bool $visibility): self
{
    $this->visibility = $visibility;

    return $this;
}

public function getPage(): ?int
{
    return $this->page;
}

public function setPage(?int $page): self
{
    $this->page = $page;

    return $this;
}
}