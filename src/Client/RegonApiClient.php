<?php

namespace MMierzynski\GusApi\Client;

use DateTime;
use DateTimeImmutable;
use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Exception\InputValidationException;
use MMierzynski\GusApi\Exception\InvalidReportDateException;
use MMierzynski\GusApi\Exception\InvalidUserCredentialsException;
use MMierzynski\GusApi\Model\DTO\CompanyDetails;
use MMierzynski\GusApi\Model\DTO\Report;
use MMierzynski\GusApi\Model\DTO\Request\DanePobierzPelnyRaport;
use MMierzynski\GusApi\Model\DTO\Request\DanePobierzRaportZbiorczy;
use MMierzynski\GusApi\Model\DTO\Request\DaneSzukajPodmioty;
use MMierzynski\GusApi\Model\DTO\Request\GetValue;
use MMierzynski\GusApi\Model\DTO\Request\ParametryWyszukiwania;
use MMierzynski\GusApi\Model\DTO\Request\Zaloguj;
use MMierzynski\GusApi\Model\DTO\Response\DanePobierzPelnyRaportResponse;
use MMierzynski\GusApi\Model\DTO\Response\DanePobierzRaportZbiorczyResponse;
use MMierzynski\GusApi\Model\DTO\Response\DaneSzukajPodmiotyResponse;
use MMierzynski\GusApi\Model\DTO\Response\GetValueResponse;
use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;
use MMierzynski\GusApi\Serializer\ResponseDeserializer;
use MMierzynski\GusApi\Utils\ReportType;
use MMierzynski\GusApi\Validator\ReportDate;
use MMierzynski\GusApi\Validator\ReportName;
use MMierzynski\GusApi\Validator\ReportInputValidator;
use SoapFault;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegonApiClient extends GusApiClient
{
    public function __construct(
        string $envName, 
        private ParameterBagInterface $parameters, 
        private ResponseDeserializer $deserializer,
        private ReportInputValidator $reportInputValidator,
        ?\SoapClient $client = null
    ) 
    {
        $envFactory = new EnvironmentFactory();
        $this->environmentConfig = $envFactory->createEnvironment('regon', $envName);
        $this->context = stream_context_create([]);

        if (!$client) {
            $client = $this->createSoapClient([
                'ZalogujResponse' => ZalogujResponse::class,
                'GetValueResponse' => GetValueResponse::class,
                'DaneSzukajPodmiotyResponse' => DaneSzukajPodmiotyResponse::class,
                'DanePobierzPelnyRaportResponse' => DanePobierzPelnyRaportResponse::class,
                'DanePobierzRaportZbiorczyResponse' => DanePobierzRaportZbiorczyResponse::class
            ]);
        }

        $this->client = $client;
    }

    /**
     * @throws SoapFault
     * @throws InvalidUserCredentialsException
     */
    public function login(): string
    {
        $apiKey = $this->parameters->get('gus_api.regon.api_key');

        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/Zaloguj'
        );
        
        $this->setContextOptions();

        /** @var ZalogujResponse $response */
        $response = $this->client->__soapCall(
            'Zaloguj', 
            [new Zaloguj($apiKey)],
            [],
            $headers
        );    

        if (empty($response->getAccessKey())) {
            throw new InvalidUserCredentialsException();
        }

        return $response->getAccessKey();
    }

    public function isUserLogged(): bool
    {
        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/2014/07/IUslugaBIR/GetValue'
        );
        
        $this->setContextOptions();

        $isUserLogged = $this->client->__soapCall(
            'GetValue', 
            [new GetValue('StatusSesji')], 
            [], 
            $headers);
        

        return (bool)$isUserLogged->GetValueResult;
    }

    public function searchForCompany(string $sid, ParametryWyszukiwania $searchParams)
    {
        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/DaneSzukajPodmioty'
        );
        
        $this->setContextOptions($sid);
        /** @var DaneSzukajPodmiotyResponse $response */
        $response = $this->client->__soapCall(
            'DaneSzukajPodmioty', 
            [new DaneSzukajPodmioty($searchParams)], 
            [], 
            $headers
        );
    
        return $this->deserializer->deserialize(
            $response->DaneSzukajPodmiotyResult, 
            CompanyDetails::class
        );
    }

    public function getFullReport(string $sid, string $regon, string $reportName): Report 
    {
        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/DanePobierzPelnyRaport'
        );

        $fullReportInput = new DanePobierzPelnyRaport($regon, $reportName);

        $errors = $this->reportInputValidator->validate(
            $fullReportInput, 
            [
                ['pNazwaRaportu' => new NotBlank()],
                ['pNazwaRaportu' => new ReportName(ReportType::TYPE_REGON_FULL)]
            ]
        );

        if (count($errors) > 0 ) {
            throw new InputValidationException($errors);
        }

        $this->setContextOptions($sid);

        $response = $this->client->__soapCall(
            'DanePobierzPelnyRaport',
            [$fullReportInput],
            [],
            $headers
        );

        $report = $this->deserializer->deserialize(
            $response->DanePobierzPelnyRaportResult,
            Report::class
        );

        $report->setReportName($reportName);

        return $report;
    }

    public function getSummaryReport(string $sid, string $reportName, DateTimeImmutable $reportDate) 
    {
        $headers = $this->preapreHeaders(
            $this->getEnvironment()->getAccessUrl(),
            'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/DanePobierzRaportZbiorczy'
        );

        $this->setContextOptions($sid);

        $date = date('Y-m-d', $reportDate->getTimestamp());

        $summaryReportInput = new DanePobierzRaportZbiorczy($date, $reportName);

        $errors = $this->reportInputValidator->validate(
            $summaryReportInput, 
            [
                ['pNazwaRaportu' => new NotBlank()],
                ['pNazwaRaportu' => new ReportName(ReportType::TYPE_REGON_SUMMARY)],
                ['pDataRaportu' => new NotBlank()],
                ['pDataRaportu' => new Date()],
                ['pDataRaportu' => new ReportDate()]
            ]
        );

        if (count($errors) > 0 ) {
            throw new InputValidationException($errors);
        }

        $response = $this->client->__soapCall(
            'DanePobierzRaportZbiorczy',
            [$summaryReportInput],
            [],
            $headers
        );

        $report = $this->deserializer->deserialize($response->DanePobierzRaportZbiorczyResult, Report::class);
        $report->setReportName($reportName);
        return $report;
    }
}