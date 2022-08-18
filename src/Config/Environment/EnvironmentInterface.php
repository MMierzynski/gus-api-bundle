<?php
namespace MMierzynski\GusApi\Config\Environment;

interface EnvironmentInterface
{
    public function getEnvName(): string;
    public function getApiName(): string;
    public function getWsdlUrl(): string;
    public function getAccessUrl(): string;
}