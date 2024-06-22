<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataUserRoleRepository")
 */
class DataUserRole
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $user_login;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRole")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active_user_role;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $nomUserRole;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $prenomUserRole;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateaddUserRole;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datamodUserRole;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserLogin(): ?string
    {
        return $this->user_login;
    }

    public function setUserLogin(string $user_login): self
    {
        $this->user_login = $user_login;

        return $this;
    }

    public function getRole(): ?DataRole
    {
        return $this->role;
    }

    public function setRole(?DataRole $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getActiveUserRole(): ?bool
    {
        return $this->active_user_role;
    }

    public function setActiveUserRole(?bool $active_user_role): self
    {
        $this->active_user_role = $active_user_role;

        return $this;
    }

    public function getNomUserRole(): ?string
    {
        return $this->nomUserRole;
    }

    public function setNomUserRole(?string $nomUserRole): self
    {
        $this->nomUserRole = $nomUserRole;

        return $this;
    }

    public function getPrenomUserRole(): ?string
    {
        return $this->prenomUserRole;
    }

    public function setPrenomUserRole(?string $prenomUserRole): self
    {
        $this->prenomUserRole = $prenomUserRole;

        return $this;
    }

    public function getDateaddUserRole(): ?\DateTimeInterface
    {
        return $this->dateaddUserRole;
    }

    public function setDateaddUserRole(?\DateTimeInterface $dateaddUserRole): self
    {
        $this->dateaddUserRole = $dateaddUserRole;

        return $this;
    }

    public function getDatamodUserRole(): ?\DateTimeInterface
    {
        return $this->datamodUserRole;
    }

    public function setDatamodUserRole(?\DateTimeInterface $datamodUserRole): self
    {
        $this->datamodUserRole = $datamodUserRole;

        return $this;
    }
}
