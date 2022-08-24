<?php

namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;

class RegonApiClient extends GusApiClient
{
    public function __construct(string $envName)
    {
        $this->type = 'regon';

        $envFactory = new EnvironmentFactory();
        $this->environmentConfig = $envFactory->createEnvironment($this->type, $envName); 
    }
}