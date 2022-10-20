<?php
namespace MMierzynski\GusApi\Model\DTO\Request;


class SearchCompany 
{
    public SearchCompanyParams $pParametryWyszukiwania;
    
    public function __construct(SearchCompanyParams $pParametryWyszukiwania)
    {
        $this->pParametryWyszukiwania = $pParametryWyszukiwania;
    }
}