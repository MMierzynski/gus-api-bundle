<?php
namespace MMierzynski\GusApi\Tests\Support\Builder;

use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Client\SoapClient as ClientSoapClient;
use MMierzynski\GusApi\Serializer\ResponseDeserializer;
use MMierzynski\GusApi\Validator\InputValidator;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SoapClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegonApiClientBuilder 
{
    private ParameterBagInterface|MockObject $parameterBag;
    private ResponseDeserializer $deserializer;
    private SoapClient|MockObject $soapClient;
    private InputValidator $inputValidator;
    private string $envName;

    public function __construct(private TestCase $testCase)
    {
    }

    public function setParamaterBag(): self
    {
        $this->parameterBag = new ParameterBag();
        return $this;
    }

    public function setParamaterBagStub(): self
    {
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);

        return $this;
    }

    public function stubParameterBagMethod(string $methodName, ?string $parameter, mixed $return): self 
    {
        if (!$this->parameterBag) {
            throw new \Exception("ParamaterBag not initialized");
        }

        $this->stubMethod($this->parameterBag, $methodName, $parameter, $return);

        return $this;
    }

    public function setSoapClientStub(): self
    {
        $this->soapClient = $this->createMock(ClientSoapClient::class);

        return $this;
    }

    public function stubSoapCallMethod(?array $parameter, mixed $return): self 
    {
        if (!$this->soapClient) {
            throw new \Exception("SoapClient not initialized");
        }

        $this->stubMethod($this->soapClient, '__soapCall', $parameter, $return);

        return $this;
    }

    public function stubValidator(?array $parameter, mixed $return): self
    {
        /** @var ValidatorInterface|MockObject */
        $validatorStub = $this->createMock(ValidatorInterface::class);
        $this->stubMethod($validatorStub, 'validate', $parameter, $return);
        
        $this->inputValidator = new InputValidator($validatorStub);

        return $this;
    }

    public function setDeserializer(): self 
    {
        $this->deserializer = new ResponseDeserializer();

        return $this;
    }

    public function setEnvName(string $envName): self 
    {
        $this->envName = $envName;

        return $this;
    }


    public function build(): RegonApiClient 
    {
        return new RegonApiClient(
            $this->envName, 
            $this->parameterBag, 
            $this->deserializer, 
            $this->inputValidator, 
            $this->soapClient
        );
    }

    private function createMock(string $className): MockObject
    {
        return (new MockBuilder($this->testCase, $className))
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
    }

    private function stubMethod(MockObject $stub, string $methodName, mixed $parameter, mixed $return): void 
    {
        $invocationMocker = $stub->method($methodName);

        if (!empty($parameter)) {
            $invocationMocker->with($parameter);
        } else {
            $invocationMocker->withAnyParameters();
        }    
        
        if ($return) {
            $invocationMocker->willReturn($return);
        }
    }
}