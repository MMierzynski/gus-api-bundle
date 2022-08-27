<?php
namespace MMierzynski\GusApi\Config\Environment\REGON;

use MMierzynski\GusApi\Config\Environment\EnvironmentInterface;

class RegonProductionEnvironment implements EnvironmentInterface
{
    function getApiName(): string
    {
        return 'regon';
    }

    function getEnvName(): string
    {
        return 'prod';
    }
    
    public function getWsdlUrl(): string
    {
        return 'https://wyszukiwarkaregon.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl-ver11-prod.wsdl';
    }

    function getAccessUrl(): string
    {
        return 'https://wyszukiwarkaregon.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc';
    }
}