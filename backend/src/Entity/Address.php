<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\Column(length: 8)]
    private ?string $cep = null;

    #[ORM\Column(length: 255)]
    private ?string $logradouro = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complemento = null;

    #[ORM\Column(length: 255)]
    private ?string $bairro = null;

    #[ORM\Column(length: 255)]
    private ?string $localidade = null;

    #[ORM\Column(length: 2)]
    private ?string $uf = null;

    #[ORM\Column(length: 255)]
    private ?string $ibge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gia = null;

    #[ORM\Column(length: 2)]
    private ?string $ddd = null;

    #[ORM\Column(length: 255)]
    private ?string $siafi = null;

    // Getters e Setters
    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function setCep(string $cep): self
    {
        $this->cep = $cep;
        return $this;
    }

    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    public function setLogradouro(string $logradouro): static
    {
        $this->logradouro = $logradouro;

        return $this;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    public function setComplemento(?string $complemento): static
    {
        $this->complemento = $complemento;

        return $this;
    }

    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    public function setBairro(string $bairro): static
    {
        $this->bairro = $bairro;

        return $this;
    }

    public function getLocalidade(): ?string
    {
        return $this->localidade;
    }

    public function setLocalidade(string $localidade): static
    {
        $this->localidade = $localidade;

        return $this;
    }

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(string $uf): static
    {
        $this->uf = $uf;

        return $this;
    }

    public function getIbge(): ?string
    {
        return $this->ibge;
    }

    public function setIbge(string $ibge): static
    {
        $this->ibge = $ibge;

        return $this;
    }

    public function getGia(): ?string
    {
        return $this->gia;
    }

    public function setGia(?string $gia): static
    {
        $this->gia = $gia;

        return $this;
    }

    public function getDdd(): ?string
    {
        return $this->ddd;
    }

    public function setDdd(string $ddd): static
    {
        $this->ddd = $ddd;

        return $this;
    }

    public function getSiafi(): ?string
    {
        return $this->siafi;
    }

    public function setSiafi(string $siafi): static
    {
        $this->siafi = $siafi;

        return $this;
    }
}
