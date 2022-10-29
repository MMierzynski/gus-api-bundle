<?php
declare(strict_types=1);

namespace MMierzynski\GusApi\Tests\Unit\Client;

use DateTimeImmutable;
use Exception;
use MMierzynski\GusApi\Exception\InputValidationException;
use MMierzynski\GusApi\Exception\InvalidUserCredentialsException;
use MMierzynski\GusApi\Exception\ReportException;
use MMierzynski\GusApi\Model\DTO\CompanyDetails;
use MMierzynski\GusApi\Model\DTO\Report;
use MMierzynski\GusApi\Model\DTO\Request\SearchCompanyParams;
use MMierzynski\GusApi\Model\DTO\Response\GetValueResponse;
use MMierzynski\GusApi\Model\DTO\Response\LoginResponse;
use MMierzynski\GusApi\Tests\Support\Builder\RegonApiClientBuilder;
use MMierzynski\GusApi\Tests\Support\SampleDataProvider;
use PHPUnit\Framework\TestCase;

final class RegonApiClientTest extends TestCase
{
    use SampleDataProvider;

    public function test_login_request_with_valid_api_keys(): void
    {
        $expectedUserKey = 'user_session_key';
        
        // arrange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBagStub()
            ->stubParameterBagMethod(methodName:'get', parameter:'gus_api.regon.api_key', return:'valid_api_key')
            ->setSoapClientStub()
            ->stubSoapCallMethod(null, new LoginResponse($expectedUserKey))
            ->setValidator()
            ->setDeserializer()
            ->build();
        
        // act
        $actalApiKey = $regonApiClient->login();
        
        // assert
        $this->assertEquals($expectedUserKey, $actalApiKey);
    }

    public function test_login_request_with_invalid_api_keys_throws_exception(): void
    {
        // arrange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBagStub()
            ->stubParameterBagMethod(methodName:'get', parameter:'gus_api.regon.api_key', return:'invalid_api_key')
            ->setSoapClientStub()
            ->stubSoapCallMethod(null, new LoginResponse(''))
            ->setValidator()
            ->setDeserializer()
            ->build();
        
        // act
        // assert
        $this->expectException(InvalidUserCredentialsException::class);
        $regonApiClient->login();
    }


    public function test_check_is_user_logged_in(): void
    {
        // arrange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBag()
            ->setSoapClientStub()
            ->stubSoapCallMethod(null, new GetValueResponse('1'))
            ->setDeserializer()
            ->setValidator()
            ->build();

        // act
        $actual = $regonApiClient->isUserLogged();

        // assert
        $this->assertIsBool($actual);
        $this->assertTrue($actual);
    }


    public function test_search_company_when_company_exists_in_gus(): void
    {
        // arange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBag()
            ->setSoapClientStub()
            ->stubSoapCallMethod(parameter: null, return: $this->getSampleSeachCompanyResponseObject())
            ->setValidator()
            ->setDeserializer()
            ->build();

        $searchParams = new SearchCompanyParams(Regon: '123456789');

        // act
        $result = $regonApiClient->searchForCompany('test_access_key', $searchParams);

        // assert
        $this->assertInstanceOf(CompanyDetails::class, $result);
        $this->assertEquals('Test Company', $result->getName());
    }

    public function test_search_company_when_company_not_exists_in_gus(): void
    {
        // arange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBag()
            ->setSoapClientStub()
            ->stubSoapCallMethod(parameter: null, return: $this->getSampleSeachCompanyResponseObject(useEmpty: true))
            ->setValidator()
            ->setDeserializer()
            ->build();

        $searchParams = new SearchCompanyParams(Regon: '123456789');

        // act
        // assert
        $this->expectException(Exception::class);
        $regonApiClient->searchForCompany('test_access_key', $searchParams);
    }

    public function test_get_full_report_when_company_exists_in_gus(): void 
    {
        // arange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBag()
            ->setSoapClientStub()
            ->stubSoapCallMethod(parameter: null, return: $this->getSampleFullReportResponseObject())
            ->setValidator()
            ->setDeserializer()
            ->build();

        // act
        $result = $regonApiClient->getFullReport('test_access_key', '123456789', 'BIR11OsPrawna');

        // assert
        $this->assertInstanceOf(Report::class, $result);
        $this->assertEquals('BIR11OsPrawna', $result->getReportName());
        $this->assertIsArray($result->getReportData());
    }


    public function test_get_full_report_when_error_occurs_throws_exception(): void 
    {
        // arange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBag()
            ->setSoapClientStub()
            ->stubSoapCallMethod(null, $this->getSampleFullReportResponseObject(withError: true))
            ->setValidator()
            ->setDeserializer()
            ->build();

        // act
        // assert
        $this->expectException(ReportException::class);
        $regonApiClient->getFullReport('test_access_key', '123456789', 'BIR11OsPrawna');
    }


    public function test_get_summary_report_with_valid_date_from_gus(): void
    {
        // arange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBag()
            ->setDeserializer()
            ->setSoapClientStub()
            ->stubSoapCallMethod(null, $this->getSampleSummaryReportResponseObject())
            ->setValidator()
            ->build();

        // act
        $result = $regonApiClient->getSummaryReport('test_access_key', 'BIR11NowePodmiotyPrawneOrazDzialalnosciOsFizycznych', new DateTimeImmutable());

        //assert
        $this->assertInstanceOf(Report::class, $result);
        $this->assertEquals('BIR11NowePodmiotyPrawneOrazDzialalnosciOsFizycznych', $result->getReportName());
        $this->assertIsArray($result->getReportData());
    }

    public function test_get_summary_report_with_future_date_from_gus(): void
    {
        // arange
        $regonApiClientBuilder = new RegonApiClientBuilder($this);
        $regonApiClient = $regonApiClientBuilder->setEnvName('test')
            ->setParamaterBag()
            ->setDeserializer()
            ->setSoapClientStub()
            ->stubSoapCallMethod(null, $this->getSampleSummaryReportResponseObject())
            ->setValidator()
            ->build();

        // act
        //assert
        $this->expectException(InputValidationException::class);
        $regonApiClient->getSummaryReport(
            'test_access_key', 
            'BIR11NowePodmiotyPrawneOrazDzialalnosciOsFizycznych', 
            new DateTimeImmutable('tomorrow')
        );
    }
}