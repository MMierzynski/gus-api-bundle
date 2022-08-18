<?php
namespace MMierzynski\GusApi\Config\Environment;

use MMierzynski\GusApi\Config\Environment\REGON\RegonProductionEnvironment;
use MMierzynski\GusApi\Config\Environment\REGON\RegonTestEnvironment;
use MMierzynski\GusApi\Exception\InvalidEnvironmentNameException;

class EnvironmentFactory
{
    public function createEnvironment(string $apiName, string $envName): EnvironmentInterface
    {
        $apiEnv = $apiName.'_'.$envName;
        return match($apiEnv){
            'regon_test' => new RegonTestEnvironment(),
            'regon_prod' => new RegonProductionEnvironment(),
            default => throw new InvalidEnvironmentNameException(sprintf('Invalid environment configuration for GUS API: "%s" and ENV NAME: "%s"', $apiName, $envName))
        };
    }
}
