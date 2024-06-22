<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataContactRepository")
 */
class DataContact
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
    private $value_contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefTypecontact", inversedBy="dataContacts")
     */
    private $type_contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient", inversedBy="dataContacts")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataAttorney")
     */
    private $attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telecode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValueContact(): ?string
    {
        return $this->value_contact;
    }

    public function setValueContact(?string $value_contact): self
    {
        $this->value_contact = $value_contact;

        return $this;
    }

    public function getTypeContact(): ?RefTypecontact
    {
        return $this->type_contact;
    }

    public function setTypeContact(?RefTypecontact $type_contact): self
    {
        $this->type_contact = $type_contact;

        return $this;
    }

    public function getClient(): ?DataClient
    {
        return $this->client;
    }

    public function setClient(?DataClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAttorney(): ?DataAttorney
    {
        return $this->attorney;
    }

    public function setAttorney(?DataAttorney $attorney): self
    {
        $this->attorney = $attorney;

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
