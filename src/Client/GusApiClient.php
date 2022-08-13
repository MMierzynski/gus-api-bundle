<?php
namespace MMierzynski\GusApi\Client;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class GusApiClient
{
    function __construct(private string $environment)
    {
    }
    
    /**
     * getEnvironment
     *
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }
}