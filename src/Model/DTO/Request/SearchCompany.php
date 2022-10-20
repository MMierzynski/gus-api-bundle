<?php
namespace MMierzynski\GusApi\Model\DTO\Request;


class SearchCompany 
{
    public ParametryWyszukiwania $pParametryWyszukiwania;
    
    public function __construct(ParametryWyszukiwania $pParametryWyszukiwania)
    {
        $this->pParametryWyszukiwania = $pParametryWyszukiwania;
    }
}