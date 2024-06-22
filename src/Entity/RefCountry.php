<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefCountryRepository")
 */
class RefCountry
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
    private $code_country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desc_country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active_country;


    private $refLabel;

    /**
     * @var RefLabel
     */
    private  $refLabels ;
    
    private $code_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country_code_label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $is_european;


   
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
        // $this->dataCountryWhereclients = new ArrayCollection();
        // $this->dataCountryWheresuppliers = new ArrayCollection();
        // $this->dataClients = new ArrayCollection();
        // $this->dataAttorneys = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCountry(): ?string
    {
        return $this->code_country;
    }

    public function setCodeCountry(string $code_country): self
    {
        $this->code_country = $code_country;

        return $this;
    }

    public function getDescCountry(): ?string
    {
        return $this->desc_country;
    }

    public function setDescCountry(string $desc_country): self
    {
        $this->desc_country = $desc_country;

        return $this;
    }

    public function getActiveCountry(): ?string
    {
        return $this->active_country;
    }

    public function setActiveCountry(string $active_country): self
    {
        $this->active_country = $active_country;

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

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(?string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getCountryCodeLabel(): ?string
    {
        return $this->country_code_label;
    }

    public function setCountryCodeLabel(?string $country_code_label): self
    {
        $this->country_code_label = $country_code_label;

        return $this;
    }

    public function getIsEuropean(): ?string
    {
        return $this->is_european;
    }

    public function setIsEuropean(?string $is_european): self
    {
        $this->is_european = $is_european;

        return $this;
    }

}
