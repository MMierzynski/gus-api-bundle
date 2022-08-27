<?php
namespace MMierzynski\GusApi\Model\DTO\Request;


class DaneSzukajPodmioty {
    public ParametryWyszukiwania $pParametryWyszukiwania;
    
    public function __construct(ParametryWyszukiwania $pParametryWyszukiwania)
    {
        $this->pParametryWyszukiwania = $pParametryWyszukiwania;
    }
}