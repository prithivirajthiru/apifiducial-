<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefTypecontactRepository")
 */
class RefTypecontact
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
    private $code_typecontact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_typecontact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_typecontact;

    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;

    // /**
    //  * @ORM\OneToMany(targetEntity="App\Entity\DataContact", mappedBy="type_contact")
    //  */
    // private $dataContacts;

    private $code_id;

   
    public function getCodeId(): ?int
    {
        return $this->code_id;
    }
    public function setCodeId(int $code_id): self
    {
        $this->code_id = $code_id;

        return $this;
    }
    public function __construct()
    {
        $this->dataContacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeTypecontact(): ?string
    {
        return $this->code_typecontact;
    }

    public function setCodeTypecontact(string $code_typecontact): self
    {
        $this->code_typecontact = $code_typecontact;

        return $this;
    }

    public function getDescTypecontact(): ?string
    {
        return $this->desc_typecontact;
    }

    public function setDescTypecontact(string $desc_typecontact): self
    {
        $this->desc_typecontact = $desc_typecontact;

        return $this;
    }

    public function getActiveTypecontact(): ?string
    {
        return $this->active_typecontact;
    }

    public function setActiveTypecontact(string $active_typecontact): self
    {
        $this->active_typecontact = $active_typecontact;

        return $this;
    }
     public function getRefLabel(): ?string
    {
        return $this->refLabel;
    }

    public function setRefLabel(string $refLabel): self
    {
        $this->refLabel = $refLabel;

        return $this;
    }
     public function getRefLabels(): ?array
    {
        return $this->refLabels;
    }

    public function setRefLabels(array $refLabels): self
    {
        $this->refLabels = $refLabels;

        return $this;
    }


    // /**
    //  * @return Collection|DataContact[]
    //  */
    // public function getDataContacts(): Collection
    // {
    //     return $this->dataContacts;
    // }

    // public function addDataContact(DataContact $dataContact): self
    // {
    //     if (!$this->dataContacts->contains($dataContact)) {
    //         $this->dataContacts[] = $dataContact;
    //         $dataContact->setTypeContact($this);
    //     }

    //     return $this;
    // }

    // public function removeDataContact(DataContact $dataContact): self
    // {
    //     if ($this->dataContacts->contains($dataContact)) {
    //         $this->dataContacts->removeElement($dataContact);
    //         // set the owning side to null (unless already changed)
    //         if ($dataContact->getTypeContact() === $this) {
    //             $dataContact->setTypeContact(null);
    //         }
    //     }

    //     return $this;
    // }
}
