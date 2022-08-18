<?php
namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentInterface;
use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;

class GusApiClient
{
    private EnvironmentInterface $environmentConfig;

    function __construct(private string $environment)
    {
        $envFactory = new EnvironmentFactory();
        $this->environmentConfig = $envFactory->createEnvironment('regon', $environment); 
    }
    
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