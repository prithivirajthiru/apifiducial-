<?php

namespace App\UtilsV3\Scenario2;

class Contact1 {

private $type_contact;
private $value_contact;
private $telecode;

public function getTypeContact(): ?int
{
    return $this->type_contact;
}

public function setTypeContact($type_contact): self
{
    $this->type_contact = $type_contact;
    return $this;
}

public function getValueContact(): ?string
{
    return $this->value_contact;
}

public function setValueContact($value_contact): self
{
    $this->value_contact = $value_contact;
    return $this;
}
public function getTelecode(): ?string
{
    return $this->telecode;
}

public function setTelecode(?string $telecode): self
{
    $this->telecode = $telecode;

    return $this;
}

}
