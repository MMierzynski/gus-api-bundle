<?php

namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Exception\InvalidUserCredentialsException;
use MMierzynski\GusApi\Model\DTO\Request\DaneSzukajPodmioty;
use MMierzynski\GusApi\Model\DTO\Request\GetValue;
use MMierzynski\GusApi\Model\DTO\Request\ParametryWyszukiwania;
use MMierzynski\GusApi\Model\DTO\Request\Zaloguj;
use MMierzynski\GusApi\Model\DTO\Response\DaneSzukajPodmiotyResponse;
use MMierzynski\GusApi\Model\DTO\Response\GetValueResponse;
use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;
use SoapFault;
use SoapHeader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RegonApiClient extends GusApiClient
{
    public function __construct(string $envName, private ParameterBagInterface $parameters, ?\SoapClient $client = null)
    {
        $envFactory = new EnvironmentFactory();
        $this->environmentConfig = $envFactory->createEnvironment('regon', $envName);
        $this->context = stream_context_create([]);

        if (!$client) {
            $client = $this->createSoapClient();
        }

        $this->client = $client;
    }

    /**
     * @throws SoapFault
     * @throws InvalidUserCredentialsException
     */
    public function login(): string
    {
        $apiKey = $this->parameters->get('gus_api.regon.api_key');

        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/Zaloguj'
        );
        
        $this->setContextOptions();

        /** @var ZalogujResponse $response */
        $response = $this->client->__soapCall(
            'Zaloguj', 
            [new Zaloguj($apiKey)],
            [],
            $headers
        );    

        if (empty($response->getAccessKey())) {
            throw new InvalidUserCredentialsException();
        }

        return $response->getAccessKey();
    }

    public function isUserLogged(): bool
    {
        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/2014/07/IUslugaBIR/GetValuer'
        );
        
        $this->setContextOptions();

        $isUserLogged = $this->client->__soapCall(
            'GetValue', 
            [new GetValue('StatusSesji')], 
            [], 
            $headers);
        

        return (bool)$isUserLogged->GetValueResult;
    }

    public function searchForCompany(string $sid, ParametryWyszukiwania $searchParams)
    {
        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/DaneSzukajPodmioty'
        );
        
        $this->setContextOptions($sid);

        $response = $this->client->__soapCall(
            'DaneSzukajPodmioty', 
            [new DaneSzukajPodmioty($searchParams)], 
            [], 
            $headers
        );
    }

    public function createSoapClient(): SoapClient
    {
        return new SoapClient(
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
                    'DaneSzukajPodmiotyResponse' => DaneSzukajPodmiotyResponse::class,
                ]
            ]
        );
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