<?php 

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
class ContactV1{
    private $id;
    private $type_contact;
    private $value_contact;
   

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId( $id): self
    {
        $this->id = $id;

        return $this;
    }

     public function getTypeContact(): ?int
    {
        return $this->type_contact;
    }

    public function setTypeContact( $type_contact): self
    {
        $this->type_contact = $type_contact;

        return $this;
    }

    public function getValueContact(): ?string
    {
        return $this->value_contact;
    }

    public function setValueContact( $value_contact): self
    {
        $this->value_contact = $value_contact;

        return $this;
    }

}