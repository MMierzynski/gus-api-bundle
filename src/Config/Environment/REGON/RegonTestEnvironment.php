<?php
namespace MMierzynski\GusApi\Config\Environment\REGON;

use MMierzynski\GusApi\Config\Environment\EnvironmentInterface;

class RegonTestEnvironment implements EnvironmentInterface
{
    function getApiName(): string
    {
        return 'api regon';
    }

    function getEnvName(): string
    {
        return 'test';
    }

    public function getWsdlUrl(): string
    {
        return 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl-ver11-test.wsdl';
    }

    function getAccessUrl(): string
    {
        return 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc';
    }
}