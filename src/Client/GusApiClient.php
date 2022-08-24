<?php
namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentInterface;
use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;

class GusApiClient
{

    protected EnvironmentInterface $environmentConfig;

    protected string $type;

    /*function __construct(private string $environment)
    {
        $envFactory = new EnvironmentFactory();
        $this->environmentConfig = $envFactory->createEnvironment('regon', $environment); 
    }*/
    
    /**
     * getEnvironment
     *
     * @return EnvironmentInterface
     */
    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environmentConfig;
    }

    public function getType(): string
    {
        return $this->type;
    }
}