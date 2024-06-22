<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefTableRepository")
 */
class RefTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_table;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prefix_table;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sufix_table;

    /**
     * @ORM\Column(type="integer")
     */
    private $length_table;

    /**
     * @ORM\Column(type="integer")
     */
    private $current_value_table;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeTable(): ?string
    {
        return $this->code_table;
    }

    public function setCodeTable(string $code_table): self
    {
        $this->code_table = $code_table;

        return $this;
    }

    public function getPrefixTable(): ?string
    {
        return $this->prefix_table;
    }

    public function setPrefixTable(string $prefix_table): self
    {
        $this->prefix_table = $prefix_table;

        return $this;
    }

    public function getSufixTable(): ?string
    {
        return $this->sufix_table;
    }

    public function setSufixTable(?string $sufix_table): self
    {
        $this->sufix_table = $sufix_table;

        return $this;
    }

    public function getLengthTable(): ?int
    {
        return $this->length_table;
    }

    public function setLengthTable(int $length_table): self
    {
        $this->length_table = $length_table;

        return $this;
    }

    public function getCurrentValueTable(): ?int
    {
        return $this->current_value_table;
    }

    public function setCurrentValueTable(int $current_value_table): self
    {
        $this->current_value_table = $current_value_table;

        return $this;
    }
}
