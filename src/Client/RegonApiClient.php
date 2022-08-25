<?php

namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;

class RegonApiClient extends GusApiClient
{
    public function __construct(private string $envName, private EnvironmentFactory $envFactory)
    {
        $this->environmentConfig = $this->envFactory->createEnvironment('regon', $envName); 
    }
}