<?php

namespace App\UtilsV3;

class UtilDataComment
{
   
    private $id;

    private $client;

    private $login_id;

    private $comment;

    private $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?int
    {
        return $this->client;
    }

    public function setClient(?int $client): self
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
