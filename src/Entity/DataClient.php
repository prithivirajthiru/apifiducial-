<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataClientRepository")
 */
class DataClient
{
    
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyname_client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caption_client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zipcode_client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city_client;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $turnover_client;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $turnoveryear_client;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $turnovertype_client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iban_client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bic_client;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shareamount_client;

  
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DataRequest", mappedBy="client", cascade={"persist", "remove"})
     */
    private $dataRequest;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefBank", inversedBy="dataClients")
     */
    private $bank;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefLegalform", inversedBy="dataClients")
     */
    private $legalform;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefEpa", inversedBy="dataClients")
     */
    private $epa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCountry", inversedBy="dataClients")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCompanytype", inversedBy="dataClients")
     */
    private $companytype;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $actdesc_client;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $otherbanque_client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefTypeclient", inversedBy="dataClients")
     */
    private $typeclient;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $other_whereclient;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $other_wheresupplier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siren;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCivility")
     */
    private $civility;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefWhoclient")
     */
    private $whoclient;
    private $requestStatus;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nomore25;
    
    
    public function __construct()
    {
        $this->dataClientWheresuppliers = new ArrayCollection();
        $this->dataCountryWhereclients = new ArrayCollection();
        $this->dataCountryWheresuppliers = new ArrayCollection();
        $this->dataContacts = new ArrayCollection();
      
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getCompanynameClient(): ?string
    {
        return $this->companyname_client;
    }

    public function setCompanynameClient(?string $companyname_client): self
    {
        $this->companyname_client = $companyname_client;

        return $this;
    }

    public function getCaptionClient(): ?string
    {
        return $this->caption_client;
    }

    public function setCaptionClient(?string $caption_client): self
    {
        $this->caption_client = $caption_client;

        return $this;
    }

    public function getAddressClient(): ?string
    {
        return $this->address_client;
    }

    public function setAddressClient(?string $address_client): self
    {
        $this->address_client = $address_client;

        return $this;
    }

    public function getZipcodeClient(): ?string
    {
        return $this->zipcode_client;
    }

    public function setZipcodeClient(?string $zipcode_client): self
    {
        $this->zipcode_client = $zipcode_client;

        return $this;
    }
   
    public function getCityClient(): ?string
    {
        return $this->city_client;
    }

    public function setCityClient(?string $city_client): self
    {
        $this->city_client = $city_client;

        return $this;
    }

    public function getTurnoverClient(): ?float
    {
        return $this->turnover_client;
    }

    public function setTurnoverClient(?float $turnover_client): self
    {
        $this->turnover_client = $turnover_client;

        return $this;
    }

    public function getTurnoveryearClient(): ?\DateTimeInterface
    {
        return $this->turnoveryear_client;
    }

    public function setTurnoveryearClient(?\DateTimeInterface $turnoveryear_client): self
    {
        $this->turnoveryear_client = $turnoveryear_client;

        return $this;
    }

    public function getTurnovertypeClient(): ?bool
    {
        return $this->turnovertype_client;
    }

    public function setTurnovertypeClient(?bool $turnovertype_client): self
    {
        $this->turnovertype_client = $turnovertype_client;

        return $this;
    }

    public function getIbanClient(): ?string
    {
        return $this->iban_client;
    }

    public function setIbanClient(?string $iban_client): self
    {
        $this->iban_client = $iban_client;

        return $this;
    }

    public function getBicClient(): ?string
    {
        return $this->bic_client;
    }

    public function setBicClient(?string $bic_client): self
    {
        $this->bic_client = $bic_client;

        return $this;
    }

    public function getShareamountClient(): ?float
    {
        return $this->shareamount_client;
    }

    public function setShareamountClient(?float $shareamount_client): self
    {
        $this->shareamount_client = $shareamount_client;

        return $this;
    }

    
    public function getDataRequest(): ?DataRequest
    {
        return $this->dataRequest;
    }

    public function setDataRequest(DataRequest $dataRequest): self
    {
        $this->dataRequest = $dataRequest;

        // set the owning side of the relation if necessary
        if ($this !== $dataRequest->getClient()) {
            $dataRequest->setClient($this);
        }

        return $this;
    }

    public function getBank(): ?RefBank
    {
        return $this->bank;
    }

    public function setBank(?RefBank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getLegalform(): ?RefLegalform
    {
        return $this->legalform;
    }

    public function setLegalform(?RefLegalform $legalform): self
    {
        $this->legalform = $legalform;

        return $this;
    }

    public function getEpa(): ?RefEpa
    {
        return $this->epa;
    }

    public function setEpa(?RefEpa $epa): self
    {
        $this->epa = $epa;

        return $this;
    }

    public function getCountry(): ?RefCountry
    {
        return $this->country;
    }

    public function setCountry(?RefCountry $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCompanytype(): ?RefCompanytype
    {
        return $this->companytype;
    }

    public function setCompanytype(?RefCompanytype $companytype): self
    {
        $this->companytype = $companytype;

        return $this;
    }

    public function getActdescClient(): ?string
    {
        return $this->actdesc_client;
    }

    public function setActdescClient(?string $actdesc_client): self
    {
        $this->actdesc_client = $actdesc_client;

        return $this;
    }

    public function getOtherbanqueClient(): ?bool
    {
        return $this->otherbanque_client;
    }

    public function setOtherbanqueClient(?bool $otherbanque_client): self
    {
        $this->otherbanque_client = $otherbanque_client;

        return $this;
    }

    public function getTypeclient(): ?RefTypeclient
    {
        return $this->typeclient;
    }

    public function setTypeclient(?RefTypeclient $typeclient): self
    {
        $this->typeclient = $typeclient;

        return $this;
    }

    public function getOtherWhereclient(): ?bool
    {
        return $this->other_whereclient;
    }

    public function setOtherWhereclient(?bool $other_whereclient): self
    {
        $this->other_whereclient = $other_whereclient;

        return $this;
    }

    public function getOtherWheresupplier(): ?bool
    {
        return $this->other_wheresupplier;
    }

    public function setOtherWheresupplier(?bool $other_wheresupplier): self
    {
        $this->other_wheresupplier = $other_wheresupplier;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }


    
    public function getCivility(): ?RefCivility
    {
        return $this->civility;
    }

    public function setCivility(?RefCivility $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getWhoclient(): ?RefWhoclient
    {
        return $this->whoclient;
    }

    public function setWhoclient(?RefWhoclient $whoclient): self
    {
        $this->whoclient = $whoclient;

        return $this;
    }

    public function getrequestStatus(): ?array
    {
        return $this->requestStatus;
    }

    public function setrequestStatus(?array $requestStatus): self
    {
        $this->requestStatus = $requestStatus;

        return $this;
    }

    public function getNomore25(): ?bool
    {
        return $this->nomore25;
    }

    public function setNomore25(?bool $nomore25): self
    {
        $this->nomore25 = $nomore25;

        return $this;
    }


}
