<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataCommentRepository")
 */
class DataComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient")
     */
    private $client;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $login_id;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLoginId(): ?string
    {
        return $this->login_id;
    }

    public function setLoginId(?string $login_id): self
    {
        $this->login_id = $login_id;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }
}
