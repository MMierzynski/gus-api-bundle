<?php
namespace MMierzynski\GusApi\Model\DTO\Response;

class ZalogujResponse 
{
    public string $ZalogujResult;

    function __construct(string $ZalogujResult)
    {
        $this->ZalogujResult = $ZalogujResult;
    }
}