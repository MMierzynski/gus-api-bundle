<?php
namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentInterface;

abstract class GusApiClient
{

    protected EnvironmentInterface $environmentConfig;

    protected \SoapClient $client;

    protected $context;
    
    /**
     * getEnvironment
     *
     * @return EnvironmentInterface
     */
    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environmentConfig;
    }

    abstract public function login(): ?string; 
}