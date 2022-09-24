<?php
namespace MMierzynski\GusApi\Model\DTO;


class CompanyDetails
{
    private string $regon;
    
    private string $nip;
    
    private ?string $nipStatus;
    
    private string $name;
    
    private string $voivodeship;
    
    private string $county;
    
    private string $community;
    
    private string $city;
    
    private string $zip;
    
    private string $street;
    
    private string $houseNo;
    
    private ?string $flatNo;
    
    private string $type;
    
    private string $silosId;
    
    private ?string $terminationDate;
    
    private string $postCity;


    public function getRegon(): string
    {
        return $this->regon;
    }

    public function setRegon(string $regon): self
    {
        $this->regon = $regon;

        return $this;
    }

    public function getNip(): string
    {
        return $this->nip;
    }

    public function setNip(string $nip): self
    {
        $this->nip = $nip;

        return $this;
    }

    public function getNipStatus(): string
    {
        return $this->nipStatus;
    }

    public function setNipStatus(string $nipStatus): self
    {
        $this->nipStatus = $nipStatus;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getVoivodeship(): string
    {
        return $this->voivodeship;
    }

    public function setVoivodeship(string $voivodeship): self
    {
        $this->voivodeship = $voivodeship;

        return $this;
    }
    public function getCounty(): string
    {
        return $this->county;
    }

    public function setCounty(string $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getCommunity(): string
    {
        return $this->community;
    }

    public function setCommunity(string $community): self
    {
        $this->community = $community;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;

        return $this;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNo(): string
    {
        return $this->houseNo;
    }

    public function setHouseNo(string $houseNo): self
    {
        $this->houseNo = $houseNo;

        return $this;
    }

    public function getFlatNo(): string
    {
        return $this->flatNo;
    }

    public function setFlatNo(string $flatNo): self
    {
        $this->flatNo = $flatNo;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSilosId(): string
    {
        return $this->silosId;
    }

    public function setSilosId(string $silosId): self
    {
        $this->silosId = $silosId;

        return $this;
    }

    public function getTerminationDate(): string
    {
        return $this->terminationDate;
    }

    public function setTerminationDate(string $terminationDate): self
    {
        $this->terminationDate = $terminationDate;

        return $this;
    }

    public function getPostCity(): string
    {
        return $this->postCity;
    }

    public function setPostCity(string $postCity): self
    {
        $this->postCity = $postCity;

        return $this;
    }
}
