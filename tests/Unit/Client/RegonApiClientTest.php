<?php
declare(strict_types=1);

namespace MMierzynski\GusApi\Tests\Unit\Client;

use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Client\SoapClient;
use MMierzynski\GusApi\Exception\InvalidUserCredentialsException;
use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

final class RegonApiClientTest extends TestCase
{
    public function test_login_request_with_valid_api_keys(): void
    {
        $expectedUserKey = 'user_session_key';


        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createMock(ParameterBag::class);
        $parameterBagStub->method('get')
            ->with('gus_api.regon.api_key')
            ->willReturn('valid_api_key');

        /** @var SoapClient|MockObject $stub */
        $stub = $this->getMockFromWsdl(__DIR__.'/../UslugaBIRzewnPubl-ver11-test.wsdl.xml');
        $stub->method('__soapCall')
            ->withAnyParameters()
            ->willReturn(new ZalogujResponse($expectedUserKey));

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $stub);
        
        
        $actalApiKey = $regonApiClient->login();
        

        $this->assertEquals($expectedUserKey, $actalApiKey);
    }

    public function test_login_request_with_invalid_api_keys(): void
    {
        $expectedUserKey = '';


        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createMock(ParameterBag::class);
        $parameterBagStub->method('get')
            ->with('gus_api.regon.api_key')
            ->willReturn('valid_api_key');

        /** @var SoapClient|MockObject $stub */
        $stub = $this->getMockFromWsdl(__DIR__.'/../UslugaBIRzewnPubl-ver11-test.wsdl.xml');
        $stub->method('__soapCall')
            ->withAnyParameters()
            ->willReturn(new ZalogujResponse($expectedUserKey));

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $stub);
        
        
        $this->expectException(InvalidUserCredentialsException::class);
        $regonApiClient->login();
    }
}