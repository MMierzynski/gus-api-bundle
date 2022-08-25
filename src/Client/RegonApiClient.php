<?php

namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;

class RegonApiClient extends GusApiClient
{
    public function __construct(private string $envName, private EnvironmentFactory $envFactory)
    {
        $this->environmentConfig = $this->envFactory->createEnvironment('regon', $envName);

        $this->context = stream_context_create();

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
}