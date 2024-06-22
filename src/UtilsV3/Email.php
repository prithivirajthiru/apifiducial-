<?php

namespace App\UtilsV3;


class Email{
    private $id;
    private $email_id;
    private $password;
    private $username;
    private $smtp_ip;
    private $smpt_port;
    private $active_emailid;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getEmailId(): ?string
    {
        return $this->email_id;
    }

    public function setEmailId(?string $email_id): self
    {
        $this->email_id = $email_id;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getSmtpIp(): ?string
    {
        return $this->smtp_ip;
    }

    public function setSmtpIp(?string $smtp_ip): self
    {
        $this->smtp_ip = $smtp_ip;

        return $this;
    }

    public function getSmptPort(): ?string
    {
        return $this->smpt_port;
    }

    public function setSmptPort(?string $smpt_port): self
    {
        $this->smpt_port = $smpt_port;

        return $this;
    }
    public function getActiveEmailid(): ?string
    {
        return $this->active_emailid;
    }

    public function setActiveEmailid(?string $active_emailid): self
    {
        $this->active_emailid = $active_emailid;

        return $this;
    }
}

