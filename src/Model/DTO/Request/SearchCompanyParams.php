<?php
namespace MMierzynski\GusApi\Model\DTO\Request;


class SearchCompanyParams 
{
    public string $Krs;
    public string $Krsy;
    public string $Nip;
    public string $Nipy;
    public string $Regon;
    public string $Regony14zn;
    public string $Regony9zn;

    public function __construct(string $Krs = '',
    string $Krsy = '',
    string $Nip = '',
    string $Nipy = '',
    string $Regon = '',
    string $Regony14zn = '',
    string $Regony9zn = '')
    {
        $this->Krs = $Krs;
        $this->Krsy = $Krsy;
        $this->Nip = $Nip;
        $this->Nipy = $Nipy;
        $this->Regon = $Regon;
        $this->Regony14zn = $Regony14zn;
        $this->Regony9zn = $Regony9zn;
    }
}