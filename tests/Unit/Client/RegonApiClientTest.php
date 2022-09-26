<?php
declare(strict_types=1);

namespace MMierzynski\GusApi\Tests\Unit\Client;

use Exception;
use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Client\SoapClient;
use MMierzynski\GusApi\Exception\InvalidUserCredentialsException;
use MMierzynski\GusApi\Model\DTO\CompanyDetails;
use MMierzynski\GusApi\Model\DTO\Report;
use MMierzynski\GusApi\Model\DTO\Request\ParametryWyszukiwania;
use MMierzynski\GusApi\Model\DTO\Response\DanePobierzPelnyRaportResponse;
use MMierzynski\GusApi\Model\DTO\Response\DaneSzukajPodmiotyResponse;
use MMierzynski\GusApi\Model\DTO\Response\GetValueResponse;
use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;
use MMierzynski\GusApi\Serializer\ResponseDeserializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class RegonApiClientTest extends TestCase
{
    public function test_login_request_with_valid_api_keys(): void
    {
        $expectedUserKey = 'user_session_key';

        // arrange
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBagInterface::class);
        $parameterBagStub->method('get')
            ->with('gus_api.regon.api_key')
            ->willReturn('valid_api_key');
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, new ZalogujResponse($expectedUserKey));

        $responseDeserializer = new ResponseDeserializer();

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $responseDeserializer, $soapClientStub);
        
        // act
        $actalApiKey = $regonApiClient->login();
        
        // assert
        $this->assertEquals($expectedUserKey, $actalApiKey);
    }

    public function test_login_request_with_invalid_api_keys_throws_exception(): void
    {
        // arrange
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBagInterface::class);
        $parameterBagStub->method('get')
            ->with('gus_api.regon.api_key')
            ->willReturn('invalid_api_key');

        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, new ZalogujResponse(''));

        $responseDeserializer = new ResponseDeserializer();

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $responseDeserializer, $soapClientStub);
        
        // act
        // assert
        $this->expectException(InvalidUserCredentialsException::class);
        $regonApiClient->login();
    }


    public function test_check_is_user_logged_in(): void
    {
        // arrange
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBagInterface::class);
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, new GetValueResponse('1'));

        $responseDeserializer = new ResponseDeserializer();

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $responseDeserializer, $soapClientStub);

        // act
        $actual = $regonApiClient->isUserLogged();

        // assert
        $this->assertIsBool($actual);
        $this->assertTrue($actual);
    }


    public function test_search_company_when_company_exists_in_gus(): void
    {
        // arange
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBagInterface::class);
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, $this->getSampleSeachCompanyResponseObject());

        $responseDeserializer = new ResponseDeserializer();

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $responseDeserializer, $soapClientStub);

        $searchParams = new ParametryWyszukiwania(Regon: '123456789');

        // act
        $result = $regonApiClient->searchForCompany('test_access_key', $searchParams);

        // assert
        $this->assertInstanceOf(CompanyDetails::class, $result);
        $this->assertEquals('Test Company', $result->getName());
    }

    public function test_search_company_when_company_not_exists_in_gus(): void
    {
        // arange
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBagInterface::class);
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, $this->getSampleSeachCompanyResponseObject(useEmpty: true));

        $responseDeserializer = new ResponseDeserializer();

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $responseDeserializer, $soapClientStub);

        $searchParams = new ParametryWyszukiwania(Regon: '123456789');

        // act
        // assert
        $this->expectException(Exception::class);
        $regonApiClient->searchForCompany('test_access_key', $searchParams);
    }

    public function test_get_full_report_when_company_exists_in_gus(): void 
    {
        // arange
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBagInterface::class);
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, $this->getSampleFullReportResponseObject());

        $responseDeserializer = new ResponseDeserializer();

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $responseDeserializer, $soapClientStub);

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
        /** @var ParameterBag|MockObject $parameterBagStub */
        $parameterBagStub = $this->createStub(ParameterBagInterface::class);
        
        /** @var SoapClient|MockObject $soapClientStub */
        $soapClientStub = $this->prepareSoapClient(null, $this->getSampleFullReportResponseObject(withError: true));

        $responseDeserializer = new ResponseDeserializer();

        $regonApiClient = new RegonApiClient('test', $parameterBagStub, $responseDeserializer, $soapClientStub);

        // act
        // assert
        $this->expectException(Exception::class);
        $regonApiClient->getFullReport('test_access_key', '123456789', 'BIR11OsPrawna');
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

    private function getSampleSeachCompanyResponseObject(bool $useEmpty = false): DaneSzukajPodmiotyResponse
    {
        $responseData = "<root>
            <dane>
              <Regon>021215833</Regon>
              <Nip>8992689516</Nip>
              <StatusNip />
              <Nazwa>Test Company</Nazwa>
              <Wojewodztwo>DOLNOŚLĄSKIE</Wojewodztwo>
              <Powiat>m. Wrocław</Powiat>
              <Gmina>Wrocław-Stare Miasto</Gmina>
              <Miejscowosc>Wrocław</Miejscowosc>
              <KodPocztowy>53-505</KodPocztowy>
              <Ulica>ul. Test-Krucza</Ulica>
              <NrNieruchomosci>15</NrNieruchomosci>
              <NrLokalu />
              <Typ>P</Typ>
              <SilosID>6</SilosID>
              <DataZakonczeniaDzialalnosci />
              <MiejscowoscPoczty>Wrocław</MiejscowoscPoczty>
            </dane>
          </root>";

        if ($useEmpty) {
            $responseData = '';
        }

        return new DaneSzukajPodmiotyResponse($responseData);
    }

    private function getSampleFullReportResponseObject(bool $withError = false): DanePobierzPelnyRaportResponse
    {
        $responseData = "<root>
            <dane>
              <praw_regon9>000331501</praw_regon9>
              <praw_nip>5261040828</praw_nip>
              <praw_statusNip />
              <praw_nazwa>GŁÓWNY URZĄD STATYSTYCZNY</praw_nazwa>
              <praw_nazwaSkrocona>GUS</praw_nazwaSkrocona>
              <praw_numerWRejestrzeEwidencji />
              <praw_dataWpisuDoRejestruEwidencji>1975-12-15</praw_dataWpisuDoRejestruEwidencji>
              <praw_dataPowstania>1975-12-15</praw_dataPowstania>
              <praw_dataRozpoczeciaDzialalnosci>1975-12-15</praw_dataRozpoczeciaDzialalnosci>
              <praw_dataWpisuDoRegon />
              <praw_dataZawieszeniaDzialalnosci />
              <praw_dataWznowieniaDzialalnosci />
              <praw_dataZaistnieniaZmiany>2009-02-20</praw_dataZaistnieniaZmiany>
              <praw_dataZakonczeniaDzialalnosci />
              <praw_dataSkresleniaZRegon />
              <praw_dataOrzeczeniaOUpadlosci />
              <praw_dataZakonczeniaPostepowaniaUpadlosciowego />
              <praw_adSiedzKraj_Symbol>PL</praw_adSiedzKraj_Symbol>
              <praw_adSiedzWojewodztwo_Symbol>14</praw_adSiedzWojewodztwo_Symbol>
              <praw_adSiedzPowiat_Symbol>65</praw_adSiedzPowiat_Symbol>
              <praw_adSiedzGmina_Symbol>108</praw_adSiedzGmina_Symbol>
              <praw_adSiedzKodPocztowy>00925</praw_adSiedzKodPocztowy>
              <praw_adSiedzMiejscowoscPoczty_Symbol>0919810</praw_adSiedzMiejscowoscPoczty_Symbol>
              <praw_adSiedzMiejscowosc_Symbol>0919810</praw_adSiedzMiejscowosc_Symbol>
              <praw_adSiedzUlica_Symbol>10013</praw_adSiedzUlica_Symbol>
              <praw_adSiedzNumerNieruchomosci>208</praw_adSiedzNumerNieruchomosci>
              <praw_adSiedzNumerLokalu />
              <praw_adSiedzNietypoweMiejsceLokalizacji />
              <praw_numerTelefonu>6083000</praw_numerTelefonu>
              <praw_numerWewnetrznyTelefonu />
              <praw_numerFaksu>0226083863</praw_numerFaksu>
              <praw_adresEmail>dgsek@stat.gov.pl</praw_adresEmail>
              <praw_adresStronyinternetowej>www.stat.gov.pl</praw_adresStronyinternetowej>
              <praw_adSiedzKraj_Nazwa>POLSKA</praw_adSiedzKraj_Nazwa>
              <praw_adSiedzWojewodztwo_Nazwa>MAZOWIECKIE</praw_adSiedzWojewodztwo_Nazwa>
              <praw_adSiedzPowiat_Nazwa>m. st. Warszawa</praw_adSiedzPowiat_Nazwa>
              <praw_adSiedzGmina_Nazwa>Śródmieście</praw_adSiedzGmina_Nazwa>
              <praw_adSiedzMiejscowosc_Nazwa>Warszawa</praw_adSiedzMiejscowosc_Nazwa>
              <praw_adSiedzMiejscowoscPoczty_Nazwa>Warszawa</praw_adSiedzMiejscowoscPoczty_Nazwa>
              <praw_adSiedzUlica_Nazwa>ul. Test-Krucza</praw_adSiedzUlica_Nazwa>
              <praw_podstawowaFormaPrawna_Symbol>2</praw_podstawowaFormaPrawna_Symbol>
              <praw_szczegolnaFormaPrawna_Symbol>01</praw_szczegolnaFormaPrawna_Symbol>
              <praw_formaFinansowania_Symbol>2</praw_formaFinansowania_Symbol>
              <praw_formaWlasnosci_Symbol>111</praw_formaWlasnosci_Symbol>
              <praw_organZalozycielski_Symbol>050000000</praw_organZalozycielski_Symbol>
              <praw_organRejestrowy_Symbol />
              <praw_rodzajRejestruEwidencji_Symbol>000</praw_rodzajRejestruEwidencji_Symbol>
              <praw_podstawowaFormaPrawna_Nazwa>JEDNOSTKA ORGANIZACYJNA NIEMAJĄCA OSOBOWOŚCI PRAWNEJ</praw_podstawowaFormaPrawna_Nazwa>
              <praw_szczegolnaFormaPrawna_Nazwa>ORGANY WŁADZY,ADMINISTRACJI RZĄDOWEJ</praw_szczegolnaFormaPrawna_Nazwa>
              <praw_formaFinansowania_Nazwa>JEDNOSTKA BUDŻETOWA</praw_formaFinansowania_Nazwa>
              <praw_formaWlasnosci_Nazwa>WŁASNOŚĆ SKARBU PAŃSTWA</praw_formaWlasnosci_Nazwa>
              <praw_organZalozycielski_Nazwa>PREZES GŁÓWNEGO URZĘDU STATYSTYCZNEGO</praw_organZalozycielski_Nazwa>
              <praw_organRejestrowy_Nazwa />
              <praw_rodzajRejestruEwidencji_Nazwa>PODMIOTY UTWORZONE Z MOCY USTAWY</praw_rodzajRejestruEwidencji_Nazwa>
              <praw_liczbaJednLokalnych>0</praw_liczbaJednLokalnych>
            </dane>
          </root>";

        if ($withError) {
            $responseData = "<root>
            <dane>
              <ErrorCode>5</ErrorCode>
              <ErrorMessagePl>Nieprawidłowa lub pusta nazwa raportu.</ErrorMessagePl>
              <ErrorMessageEn>Invalid or empty report name.</ErrorMessageEn>
              <pRegon>021215833</pRegon>
              <Typ_podmiotu />
              <Raport>BIR11OsPrawnrr</Raport>
            </dane>
          </root>";
        }

        return new DanePobierzPelnyRaportResponse($responseData);
    }
}