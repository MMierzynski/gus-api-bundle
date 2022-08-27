<?php

namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Model\DTO\Request\LoginModelInterface;
use MMierzynski\GusApi\Model\DTO\Request\Zaloguj;
use MMierzynski\GusApi\Model\DTO\Response\LoginResponseInterface;
use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;
use SoapFault;
use SoapHeader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RegonApiClient extends GusApiClient
{
    public function __construct(private string $envName, private EnvironmentFactory $envFactory, ParameterBagInterface $parameters)
    {
        $this->environmentConfig = $this->envFactory->createEnvironment('regon', $envName);

        $this->context = stream_context_create([]);

        $this->client = new SoapClient(
            $this->environmentConfig->getWsdlUrl(),
            [
                'trace' => 1,
                "stream_context" => $this->context,
                'soap_version' => SOAP_1_2,
                'style' => SOAP_DOCUMENT,
                'location' => $this->getEnvironment()->getAccessUrl(),
                'classmap' => [
                    'ZalogujResponse' => ZalogujResponse::class,
                ]
            ]
        );
    }

    public function login(): ?LoginResponseInterface
    {
        $headers = [
            new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', $this->getEnvironment()->getAccessUrl()),
            new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/Zaloguj'),
        ];

        stream_context_set_option($this->context, ['http' => [
            'header' => 'sid: '.null,
            'user_agent' => 'GUSAPI Symfony Client',
        ]]);

        try {
            $response = $this->client->__soapCall(
                'Zaloguj', 
                [new Zaloguj('abcde12345abcde12345')],
                [],
                $headers
            );    
        } catch (SoapFault $soapException) {
            dd($soapException);
            return null;
        }

        return $response;
    }
}