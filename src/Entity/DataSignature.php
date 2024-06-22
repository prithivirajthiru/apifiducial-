<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataSignatureRepository")
 */
class DataSignature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DataRequest")
     */
    private $request_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transaction_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $transaction_date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $transaction_status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transaction_url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestId(): ?DataRequest
    {
        return $this->request_id;
    }

    public function setRequestId(?DataRequest $request_id): self
    {
        $this->request_id = $request_id;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(?string $transaction_id): self
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transaction_date;
    }

    public function setTransactionDate(?\DateTimeInterface $transaction_date): self
    {
        $this->transaction_date = $transaction_date;

        return $this;
    }

    public function getTransactionStatus(): ?int
    {
        return $this->transaction_status;
    }

    public function setTransactionStatus(?int $transaction_status): self
    {
        $this->transaction_status = $transaction_status;

        return $this;
    }

    public function getTransactionUrl(): ?string
    {
        return $this->transaction_url;
    }

    public function setTransactionUrl(?string $transaction_url): self
    {
        $this->transaction_url = $transaction_url;

        return $this;
    }
}
