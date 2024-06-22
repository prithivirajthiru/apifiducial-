<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefCivilityRepository")
 */
class RefCivility
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
    private $code_civility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_civility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $active_civility;


    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;


    private $code_id;

   
    public function getCodeId(): ?int
    {
        return $this->code_id;
    }
    public function setCodeId(int $code_id): self
    {
        $this->code_id = $code_id;

        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCivility(): ?string
    {
        return $this->code_civility;
    }

    public function setCodeCivility(?string $code_civility): self
    {
        $this->code_civility = $code_civility;

        return $this;
    }

    public function getDescCivility(): ?string
    {
        return $this->desc_civility;
    }

    public function setDescCivility(?string $desc_civility): self
    {
        $this->desc_civility = $desc_civility;

        return $this;
    }

    public function getActiveCivility(): ?string
    {
        return $this->active_civility;
    }

    public function setActiveCivility(?string $active_civility): self
    {
        $this->active_civility = $active_civility;

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
}
