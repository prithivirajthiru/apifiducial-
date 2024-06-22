<?php 
namespace App\UtilsV2;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\RefCivility;
use App\Entity\RefCompanytype;
use App\Entity\RefLegalform;
use App\Entity\RefEpa;
use App\Entity\RefCountry;
use App\Entity\RefWhoclient;
use App\Entity\DataAttorney;
use App\Entity\RefWheresupplier;
use App\Entity\RefWhereclient;

class Scenario1{
    private $client_id;
    private $whoclient;
    private $civility;
    private $typeclient;
    private $companytype;
    private $isleader;
    private $contacts;
    private $email;
    private $Attorney;
    private $siren;
    private $companyname_client;
    private $caption_client;
    private $address_client;
    private $zipcode_client;
    private $city_client;
    private $turnover_client;
    private $turnoveryear_client;
    private $turnovertype_client;
    private $legalform;
    private $epa;
    private $actdesc_client;
    private $other_whereclient;
    private $other_wheresupplier;
    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    public function setClientId($client_id): self
    {
        $this->client_id = $client_id;

        return $this;
    }
    public function getCivility(): ?RefCivility
    {
        return $this->civility;
    }

    public function setCivility(RefCivility $civility): self
    {
        $this->civility = $civility;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

     public function getTypeClient(): ?int
    {
        return $this->typeclient;
    }

    public function setTypeClient($typeclient): self
    {
        $this->typeclient = $typeclient;

        return $this;
    }

     public function getCompanyType(): ?RefCompanytype
    {
        return $this->companytype;
    }

    public function setCompanyType(RefCompanytype $companytype): self
    {
        $this->companytype = $companytype;

        return $this;
    }


    public function getIsleader(): ?bool
    {
        return $this->isleader;
    }

    public function setIsleader(?bool $isleader): self
    {
        $this->isleader = $isleader;

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

    public function getTurnoveryearClient(): int
    {
        return $this->turnoveryear_client;
    }

    public function setTurnoveryearClient(int $turnoveryear_client): self
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
    
    public function getLegalform(): ?RefLegalform
    {
        return $this->legalform;
    }

    public function setLegalform(RefLegalform $legalform): self
    {
        $this->legalform = $legalform;

        return $this;
    }

    public function getEpa(): ?RefEpa 
    {
        return $this->epa;
    }

    public function setEpa(RefEpa $epa): self
    {
        $this->epa = $epa;

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

    public function getWhoclient(): ?RefWhoclient
    {
        return $this->whoclient;
    }

    public function setWhoclient(RefWhoclient $whoclient): self
    {
        $this->whoclient = $whoclient;

        return $this;
    }
 
    public function getAttorney():?Attorney
    {
        return $this->Attorney;
    }

    public function setAttorney(Attorney $Attorney): self
    {
        $this->Attorney = $Attorney;

        return $this;
    }
    //  public function getContacts():array
    // {
    //     return $this->contacts;
    // }

    // public function setContacts(array $contacts): self
    // {
    //     $this->contacts = $contacts;

    //     return $this;
    // }


}
class Cont{

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
Class Attorney{
      private $other_wc_countrylist;
      private $other_ws_Countrylist;
      private $whereclientlist;
      private $wheresupplierlist;
      private $civility_attorney;
      private $name_attorney;
      private $surname_attorney;
      private $datebirth_attorney;
      private $placebirth_attorney;
      private $nationality_attorney;
      private $fiscalcountry_attorney;
      private $american_attorney;
     
  
    
    
    public function getWhereSupplierList():?array
    {
        return $this->wheresupplierlist;
    }

    public function setWhereSupplierList(?array $wheresupplierlist): self
    {
        $this->wheresupplierlist = $wheresupplierlist;

        return $this;
    }
    public function getWhereClientList():?array
    {
        return $this->whereclientlist;
    }

    public function setWhereClientList(?array $whereclientlist): self
    {
        $this->whereclientlist = $whereclientlist;

        return $this;
    }
    public function getOtherWSCountryList():?array
    {
        return $this->other_ws_Countrylist;
    }

    public function setOtherWSCountryList(?array $other_ws_Countrylist): self
    {
        $this->other_ws_Countrylist = $other_ws_Countrylist;

        return $this;
    }
    public function getOtherWCCountryList():?array
    {
        return $this->other_wc_countrylist;
    }

    public function setOtherWCCountryList(?array $other_wc_countrylist): self
    {
        $this->other_wc_countrylist = $other_wc_countrylist;

        return $this;
    }

    public function getCivilityAttorney(): ?string
    {
        return $this->civility_attorney;
    }

    public function setCivilityAttorney(?string $civility_attorney): self
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

    public function getDatebirthAttorney(): ?int
    {
        return $this->datebirth_attorney;
    }

    public function setDatebirthAttorney(int $datebirth_attorney): self
    {
        $this->datebirth_attorney = $datebirth_attorney;

        return $this;
    }

    public function getPlacebirthAttorney(): ?RefCountry
    {
        return $this->placebirth_attorney;
    }

    public function setPlacebirthAttorney(RefCountry $placebirth_attorney): self
    {
        $this->placebirth_attorney = $placebirth_attorney;

        return $this;
    }
     public function getNationalityAttorney(): ?RefCountry
    {
        return $this->nationality_attorney;
    }

    public function setNationalityAttorney(RefCountry $nationality_attorney): self
    {
        $this->nationality_attorney = $nationality_attorney;

        return $this;
    }
    
     public function getFiscalcountryAttorney(): ?RefCountry
    {
        return $this->fiscalcountry_attorney;
    }

    public function setFiscalcountryAttorney(RefCountry $fiscalcountry_attorney): self
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
    }
