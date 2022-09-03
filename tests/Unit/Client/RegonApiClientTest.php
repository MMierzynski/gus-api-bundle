<?php
declare(strict_types=1);

namespace MMierzynski\GusApi\Tests\Unit\Client;

use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Client\SoapClient;
use MMierzynski\GusApi\Exception\InvalidUserCredentialsException;
use MMierzynski\GusApi\Model\DTO\Request\GetValue;
use MMierzynski\GusApi\Model\DTO\Response\GetValueResponse;
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
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, new ZalogujResponse($expectedUserKey));

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $soapClientStub);
        
        
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

        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, new ZalogujResponse($expectedUserKey));

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $soapClientStub);
        

        $this->expectException(InvalidUserCredentialsException::class);
        $regonApiClient->login();
    }


    public function test_check_is_user_logged_in(): void
    {
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBag::class);

        $soapCallParams = [
            'GetValue', 
            [new GetValue('StatusSesji')], 
            []
        ];
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, new GetValueResponse('1'));

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $soapClientStub);


        $actual = $regonApiClient->isUserLogged();


        $this->assertIsBool($actual);
        $this->assertTrue($actual);
    }

    private function prepareSoapClient(?array $params, mixed $return): MockObject
    {
        
        $soapClientStub = $this->getMockFromWsdl(__DIR__.'/../UslugaBIRzewnPubl-ver11-test.wsdl.xml');
        $invocationMocker = $soapClientStub->method('__soapCall');

        if (!empty($params)) {
            $invocationMocker->with($params);
        } else {
            $invocationMocker->withAnyParameters();
        }    
        
        if ($return) {
            $invocationMocker->willReturn($return);
        }

        
        return $soapClientStub;
    }
}