<?php 

namespace App\Utils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ContactInfo{
    private $client;
    private $contacts;
   
   
  
    public function getClient(): ?int
    {
        return $this->client;
    }

    public function setClient(?string $client): self
    {
        $this->client = $client;

        return $this;
    }

   
     public function getContacts():array
    {
        return $this->contacts;
    }

    public function setContacts(array $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }
   

}
class Contact{

    private $type_contact;
    private $value_contact;

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