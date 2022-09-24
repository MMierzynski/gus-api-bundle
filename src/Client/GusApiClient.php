<?php
namespace MMierzynski\GusApi\Client;

use MMierzynski\GusApi\Config\Environment\EnvironmentInterface;
use SoapHeader;
use Symfony\Component\Serializer\SerializerInterface;

abstract class GusApiClient
{

    protected EnvironmentInterface $environmentConfig;

    protected \SoapClient $client;

    protected $context;

    protected SerializerInterface $serializer;

    /**
     * @return string
     */
    abstract public function login(): string;
    
    /**
     * getEnvironment
     *
     * @return EnvironmentInterface
     */
    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environmentConfig;
    }

    /**
     * @param array $classMap
     * @return SoapClient
     */
    protected function createSoapClient(array $classMap = []): SoapClient
    {
        return new SoapClient(
            $this->environmentConfig->getWsdlUrl(),
            [
                'trace' => 1,
                "stream_context" => $this->context,
                'soap_version' => SOAP_1_2,
                'style' => SOAP_DOCUMENT,
                'location' => $this->getEnvironment()->getAccessUrl(),
                'classmap' => $classMap
            ]
        );
    }

    
    /**
     * @param string $toUrl
     * @param string $actionUrl
     * @return array
     */
    protected function preapreHeaders(string $toUrl, string $actionUrl): array
    {
        return [
            new SoapHeader('http://www.w3.org/2005/08/addressing', 'To', $toUrl),
            new SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', $actionUrl),
        ];
    }


    /**
     * @param ?string $sid
     * @return void
     */
    protected function setContextOptions(?string $sid= null): void
    {
        stream_context_set_option($this->context, ['http' => [
            'header' => 'sid: '.$sid,
            'user_agent' => 'GUSAPI Symfony Client',
        ]]);
    }
}