<?php

namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Model\DTO\Request\GetValue;
use MMierzynski\GusApi\Model\DTO\Request\LoginModelInterface;
use MMierzynski\GusApi\Model\DTO\Request\Zaloguj;
use MMierzynski\GusApi\Model\DTO\Response\GetValueResponse;
use MMierzynski\GusApi\Model\DTO\Response\LoginResponseInterface;
use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;
use SoapFault;
use SoapHeader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RegonApiClient extends GusApiClient
{
    public function __construct(private string $envName, private EnvironmentFactory $envFactory, private ParameterBagInterface $parameters)
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
                    'GetValueResponse' => GetValueResponse::class,
                ]
            ]
        );
    }

    public function login(): ?LoginResponseInterface
    {
        $apiKey = $this->parameters->get('gus_api.regon.api_key');

        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/Zaloguj'
        );
        
        $this->setContextOptions();

        try {
            $response = $this->client->__soapCall(
                'Zaloguj', 
                [new Zaloguj($apiKey)],
                [],
                $headers
            );    
        } catch (SoapFault $soapException) {
            dd($soapException);
            return null;
        }

        return $response;
    }

    public function isUserLogged(): bool
    {
        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/2014/07/IUslugaBIR/GetValue'
        );
        
        $this->setContextOptions();

        $isUserLogged = $this->client->__soapCall(
            'GetValue', 
            [new GetValue('StatusSesji')], 
            [], 
            $headers);

        return (bool)$isUserLogged->GetValueResult;
    }

    protected function preapreHeaders(string $toUrl, string $actionUrl): array
    {
        return [
            new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', $toUrl),
            new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', $actionUrl),
        ];
    }

    protected function setContextOptions(?string $sid= null): void
    {
        stream_context_set_option($this->context, ['http' => [
            'header' => 'sid: '.$sid,
            'user_agent' => 'GUSAPI Symfony Client',
        ]]);
    }
}