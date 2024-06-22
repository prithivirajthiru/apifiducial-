<?php
namespace App\UtilsV3;


class Signature{
    
   private $id;
   private $name;
   private $desc_signature;
   private $type;
   private $data;
   private $active_signature;

   public function getId(): ?int
   {
       return $this->id;
   }
   public function setId(?int $id): self
   {
       $this->id = $id;

       return $this;
   }
   public function getName(): ?string
   {
       return $this->name;
   }

   public function setName(?string $name): self
   {
       $this->name = $name;

       return $this;
   }

   public function getDescSignature(): ?string
   {
       return $this->desc_signature;
   }

   public function setDescSignature(?string $desc_signature): self
   {
       $this->desc_signature = $desc_signature;

       return $this;
   }

   public function getType(): ?string
   {
       return $this->type;
   }

   public function setType(?string $type): self
   {
       $this->type = $type;

       return $this;
   }

   public function getData(): ?string
   {
       return $this->data;
   }

   public function setData(?string $data): self
   {
       $this->data = $data;

       return $this;
   }

   public function getActiveSignature(): ?string
   {
       return $this->active_signature;
   }

   public function setActiveSignature(?string $active_signature): self
   {
       $this->active_signature = $active_signature;

       return $this;
   }

}