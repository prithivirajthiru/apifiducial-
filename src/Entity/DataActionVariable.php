<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataActionVariableRepository")
 */
class DataActionVariable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EmailAction")
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefVariable")
     */
    private $variable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?EmailAction
    {
        return $this->action;
    }

    public function setAction(?EmailAction $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getVariable(): ?RefVariable
    {
        return $this->variable;
    }

    public function setVariable(?RefVariable $variable): self
    {
        $this->variable = $variable;

        return $this;
    }
}
