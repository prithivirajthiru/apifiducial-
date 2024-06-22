<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataEloquaCdoRepository")
 */
class DataEloquaCdo
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
    private $emailAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $eloquaId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSend;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $offreSituation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $offreFormule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etape;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonSociale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRequest")
     */
    private $request;
    private $requestId;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $loginUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlsource;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $birthName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEloquaId(): ?int
    {
        return $this->eloquaId;
    }

    public function setEloquaId(?int $eloquaId): self
    {
        $this->eloquaId = $eloquaId;

        return $this;
    }

    public function getIsSend(): ?bool
    {
        return $this->isSend;
    }

    public function setIsSend(?bool $isSend): self
    {
        $this->isSend = $isSend;

        return $this;
    }

    public function getOffreSituation(): ?string
    {
        return $this->offreSituation;
    }

    public function setOffreSituation(?string $offreSituation): self
    {
        $this->offreSituation = $offreSituation;

        return $this;
    }

    public function getOffreFormule(): ?string
    {
        return $this->offreFormule;
    }

    public function setOffreFormule(?string $offreFormule): self
    {
        $this->offreFormule = $offreFormule;

        return $this;
    }

    public function getEtape(): ?string
    {
        return $this->etape;
    }

    public function setEtape(?string $etape): self
    {
        $this->etape = $etape;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(?string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(?string $raisonSociale): self
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getRequest(): ?DataRequest
    {
        return $this->request;
    }

    public function setRequest(?DataRequest $request): self
    {
        $this->request = $request;

        return $this;
    }
    public function getRequestId(): ?int
    {
        return $this->requestId;
    }

    public function setRequestId($requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }

    public function getLoginUrl(): ?string
    {
        return $this->loginUrl;
    }

    public function setLoginUrl(?string $loginUrl): self
    {
        $this->loginUrl = $loginUrl;

        return $this;
    }

    public function getUrlsource(): ?string
    {
        return $this->urlsource;
    }

    public function setUrlsource(?string $urlsource): self
    {
        $this->urlsource = $urlsource;

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
