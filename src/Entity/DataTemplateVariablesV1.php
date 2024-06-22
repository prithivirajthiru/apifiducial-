<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataTemplateVariablesV1Repository")
 */
class DataTemplateVariablesV1
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
    private $tempvs_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tempvs_label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    
    private $tempvs_desc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tempvs_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tempvs_status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTempvsCode(): ?string
    {
        return $this->tempvs_code;
    }

    public function setTempvsCode(?string $tempvs_code): self
    {
        $this->tempvs_code = $tempvs_code;

        return $this;
    }

    public function getTempvsLabel(): ?string
    {
        return $this->tempvs_label;
    }

    public function setTempvsLabel(?string $tempvs_label): self
    {
        $this->tempvs_label = $tempvs_label;

        return $this;
    }

   

    public function getTempvsDesc(): ?string
    {
        return $this->tempvs_desc;
    }

    public function setTempvsDesc(?string $tempvs_desc): self
    {
        $this->tempvs_desc = $tempvs_desc;

        return $this;
    }

    public function getTempvsKey(): ?string
    {
        return $this->tempvs_key;
    }

    public function setTempvsKey(?string $tempvs_key): self
    {
        $this->tempvs_key = $tempvs_key;

        return $this;
    }

    public function getTempvsStatus(): ?string
    {
        return $this->tempvs_status;
    }

    public function setTempvsStatus(?string $tempvs_status): self
    {
        $this->tempvs_status = $tempvs_status;

        return $this;
    }
}
