<?php

namespace App\UtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
class CheckEmail{
    private $email_id;
    private $password;
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

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    } 
}