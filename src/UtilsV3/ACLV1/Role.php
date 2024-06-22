<?php

namespace App\UtilsV3\ACLV1;
use App\Entity\RefAction;

class Role{

private $id;

private $name;

private $desc_role;

private $version;

private $status;

private $rule;

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

public function getDescRole(): ?string
{
    return $this->desc_role;
}

public function setDescRole(?string $desc_role): self
{
    $this->desc_role = $desc_role;

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

public function getStatus(): ?string
{
    return $this->status;
}

public function setStatus(?string $status): self
{
    $this->status = $status;

    return $this;
}
public function getRule(): ?array
{
    return $this->rule;
}

public function setRule(?array $rule): self
{
    $this->rule = $rule;

    return $this;
}
}