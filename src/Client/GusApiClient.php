<?php
namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentInterface;
use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;

class GusApiClient
{

    protected EnvironmentInterface $environmentConfig;
    
    /**
     * getEnvironment
     *
     * @return EnvironmentInterface
     */
    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environmentConfig;
    }
}