<?php

namespace App\Entity;
use App\UtilsV3\Contact;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataAttorneyRepository")
 */
class DataAttorney
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $birthName;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datebirth_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placebirth_attorney;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCountry", inversedBy="dataAttorneys")
     */
    private $countrybirth_attorney;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCountry", inversedBy="dataAttorneys")
     */
    private $nationality_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zipcode_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city_attorney;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCountry", inversedBy="dataAttorneys")
     */
    private $residentcountry_attorney;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataClient", inversedBy="dataAttorneys")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCountry", inversedBy="dataAttorneys")
     */
    private $fiscalcountry_attorney;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $american_attorney;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefFunction", inversedBy="dataAttorneys")
     */
    private $function;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siren_attorney;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $part_attorney;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percentage_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $active_attorney;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $iscompany;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isrepresentative;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefCivility")
     */
    private $civility_attorney;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ismandatory_attorney;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isshareholder_attorney;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RefLegalform")
     */
    private $legalform;

    private $contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fiscalnumber_attorney;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $is_receiving_share;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $register_no;

    private $file;
    private $strdob;
    private $lrStringAddress;
    private $lrValidationAddress;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amountAttorney;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDepotDoneAttorney;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDateAttorney;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $receivedAmountAttorney;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isokAmountAttorney;

   

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getCivilityAttorney(): ?RefCivility
    {
        return $this->civility_attorney;
    }

    public function setCivilityAttorney(?RefCivility $civility_attorney): self
    {
        $this->civility_attorney = $civility_attorney;

        return $this;
    }

    public function getNameAttorney(): ?string
    {
        return $this->name_attorney;
    }

    public function setNameAttorney(?string $name_attorney): self
    {
        $this->name_attorney = $name_attorney;

        return $this;
    }

    public function getSurnameAttorney(): ?string
    {
        return $this->surname_attorney;
    }

    public function setSurnameAttorney(?string $surname_attorney): self
    {
        $this->surname_attorney = $surname_attorney;

        return $this;
    }

    public function getDatebirthAttorney(): ?\DateTimeInterface
    {
        return $this->datebirth_attorney;
    }

    public function setDatebirthAttorney(?\DateTimeInterface $datebirth_attorney): self
    {
        $this->datebirth_attorney = $datebirth_attorney;

        return $this;
    }

    public function getPlacebirthAttorney(): ?string
    {
        return $this->placebirth_attorney;
    }

    public function setPlacebirthAttorney(?string $placebirth_attorney): self
    {
        $this->placebirth_attorney = $placebirth_attorney;

        return $this;
    }

    public function getCountrybirthAttorney(): ?RefCountry
    {
        return $this->countrybirth_attorney;
    }

    public function setCountrybirthAttorney(?RefCountry $countrybirth_attorney): self
    {
        $this->countrybirth_attorney = $countrybirth_attorney;

        return $this;
    }

    public function getNationalityAttorney(): ?RefCountry
    {
        return $this->nationality_attorney;
    }

    public function setNationalityAttorney(?RefCountry $nationality_attorney): self
    {
        $this->nationality_attorney = $nationality_attorney;

        return $this;
    }

    public function getAddressAttorney(): ?string
    {
        return $this->address_attorney;
    }

    public function setAddressAttorney(?string $address_attorney): self
    {
        $this->address_attorney = $address_attorney;

        return $this;
    }

    public function getZipcodeAttorney(): ?string
    {
        return $this->zipcode_attorney;
    }

    public function setZipcodeAttorney(?string $zipcode_attorney): self
    {
        $this->zipcode_attorney = $zipcode_attorney;

        return $this;
    }
    public function getCityAttorney(): ?string
    {
        return $this->city_attorney;
    }

    public function setCityAttorney(?string $city_attorney): self
    {
        $this->city_attorney = $city_attorney;

        return $this;
    }

   
    public function getResidentcountryAttorney(): ?RefCountry
    {
        return $this->residentcountry_attorney;
    }

    public function setResidentcountryAttorney(?RefCountry $residentcountry_attorney): self
    {
        $this->residentcountry_attorney = $residentcountry_attorney;

        return $this;
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

    public function getFiscalcountryAttorney(): ?RefCountry
    {
        return $this->fiscalcountry_attorney;
    }

    public function setFiscalcountryAttorney(?RefCountry $fiscalcountry_attorney): self
    {
        $this->fiscalcountry_attorney = $fiscalcountry_attorney;

        return $this;
    }

    public function getAmericanAttorney(): ?bool
    {
        return $this->american_attorney;
    }

    public function setAmericanAttorney(?bool $american_attorney): self
    {
        $this->american_attorney = $american_attorney;

        return $this;
    }

    public function getFunction(): ?RefFunction
    {
        return $this->function;
    }

    public function setFunction(?RefFunction $function): self
    {
        $this->function = $function;

        return $this;
    }

    public function getSirenAttorney(): ?string
    {
        return $this->siren_attorney;
    }

    public function setSirenAttorney(?string $siren_attorney): self
    {
        $this->siren_attorney = $siren_attorney;

        return $this;
    }

    public function getPartAttorney(): ?int
    {
        return $this->part_attorney;
    }

    public function setPartAttorney(?int $part_attorney): self
    {
        $this->part_attorney = $part_attorney;

        return $this;
    }

    public function getPercentageAttorney(): ?float
    {
        return $this->percentage_attorney;
    }

    public function setPercentageAttorney(?float $percentage_attorney): self
    {
        $this->percentage_attorney = $percentage_attorney;

        return $this;
    }

    public function getActiveAttorney(): ?string
    {
        return $this->active_attorney;
    }

    public function setActiveAttorney(?string $active_attorney): self
    {
        $this->active_attorney = $active_attorney;

        return $this;
    }

    public function getIscompany(): ?bool
    {
        return $this->iscompany;
    }

    public function setIscompany(?bool $iscompany): self
    {
        $this->iscompany = $iscompany;

        return $this;
    }

    public function getIsrepresentative(): ?bool
    {
        return $this->isrepresentative;
    }

    public function setIsrepresentative(?bool $isrepresentative): self
    {
        $this->isrepresentative = $isrepresentative;

        return $this;
    }

    public function getIsmandatoryAttorney(): ?bool
    {
        return $this->ismandatory_attorney;
    }

    public function setIsmandatoryAttorney(?bool $ismandatory_attorney): self
    {
        $this->ismandatory_attorney = $ismandatory_attorney;

        return $this;
    }

    public function getIsshareholderAttorney(): ?bool
    {
        return $this->isshareholder_attorney;
    }

    public function setIsshareholderAttorney(?bool $isshareholder_attorney): self
    {
        $this->isshareholder_attorney = $isshareholder_attorney;

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

    public function getContact(): ?array 
    {
        return $this->contact;
    }

    public function setContact(?array  $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getFiscalnumberAttorney(): ?string
    {
        return $this->fiscalnumber_attorney;
    }

    public function setFiscalnumberAttorney(?string $fiscalnumber_attorney): self
    {
        $this->fiscalnumber_attorney = $fiscalnumber_attorney;

        return $this;
    }

    public function getIsReceivingShare(): ?string
    {
        return $this->is_receiving_share;
    }

    public function setIsReceivingShare(?string $is_receiving_share): self
    {
        $this->is_receiving_share = $is_receiving_share;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(?string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getRegisterNo(): ?string
    {
        return $this->register_no;
    }

    public function setRegisterNo(?string $register_no): self
    {
        $this->register_no = $register_no;

        return $this;
    }

    public function getFile(): ?DataRequestFile
    {
        return $this->file;
    }

    public function setFile(?DataRequestFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getlrStringAddress(): ?string
    {
        return $this->lrStringAddress;
    }

    public function setlrStringAddress(?string $lrStringAddress): self
    {
        $this->lrStringAddress = $lrStringAddress;

        return $this;
    }

    public function getlrValidationAddress(): ?bool
    {
        return $this->lrValidationAddress;
    }

    public function setlrValidationAddress(?bool $lrValidationAddress): self
    {
        $this->lrValidationAddress = $lrValidationAddress;

        return $this;
    }

    public function getStrDob(): ?string
    {
        return $this->strdob;
    }

    public function setStrDob(?string $strdob): self
    {
        $this->strdob = $strdob;

        return $this;
    }

    public function getAmountAttorney(): ?float
    {
        return $this->amountAttorney;
    }

    public function setAmountAttorney(?float $amountAttorney): self
    {
        $this->amountAttorney = $amountAttorney;

        return $this;
    }

    public function getIsDepotDoneAttorney(): ?bool
    {
        return $this->isDepotDoneAttorney;
    }

    public function setIsDepotDoneAttorney(?bool $isDepotDoneAttorney): self
    {
        $this->isDepotDoneAttorney = $isDepotDoneAttorney;

        return $this;
    }

    public function getDateDateAttorney(): ?\DateTimeInterface
    {
        return $this->dateDateAttorney;
    }

    public function setDateDateAttorney(?\DateTimeInterface $dateDateAttorney): self
    {
        $this->dateDateAttorney = $dateDateAttorney;

        return $this;
    }

    public function getReceivedAmountAttorney(): ?float
    {
        return $this->receivedAmountAttorney;
    }

    public function setReceivedAmountAttorney(?float $receivedAmountAttorney): self
    {
        $this->receivedAmountAttorney = $receivedAmountAttorney;

        return $this;
    }

    public function getIsokAmountAttorney(): ?bool
    {
        return $this->isokAmountAttorney;
    }

    public function setIsokAmountAttorney(?bool $isokAmountAttorney): self
    {
        $this->isokAmountAttorney = $isokAmountAttorney;

        return $this;
    }

    public function getBirthName(): ?string
    {
        return $this->birthName;
    }

    public function setBirthName(?string $birthName): self
    {
        $this->birthName = $birthName;

        return $this;
    }

}
