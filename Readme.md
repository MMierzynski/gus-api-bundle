# Gus Api Bundle

## Description
Symfony 6 Bundle that allows to get information from REGON GUS API

## Installation
```composer require mmierzynski/gus-api-bundle```

## Usage

### Configuration

```yaml
# config/packages/gus_api.yaml

gus_api:
  regon:
    env: test
    api_key: 'abcde12345abcde12345'
```
- env - **[Required]** GUS API environment. Accepted values: **_test_**, **_prod_**
- api_key - **[Required]** Your API key. For test env you can use: abcde12345abcde12345  

### Usage

#### Login user

```php
use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Exception\InvalidUserCredentialsException;

public function index(RegonApiClient $client): JsonResponse
{
    if (!$client->isUserLogged()){
        try{
            $accessKey = $client->login();
        } catch (InvalidUserCredentialsException $credentialsException) {
            // do something
        }
    }

    ...
}
```

#### Search for company

```php
use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Model\DTO\Request\SearchCompanyParams;

public function index(RegonApiClient $client): JsonResponse
{
    ...
    $searchParams = new SearchCompanyParams(Nip:'8992689516');
    $company = $client->searchForCompany($accessKey, $searchParams);
    ...
}

```


#### Get full report

```php
use MMierzynski\GusApi\Client\RegonApiClient;

public function index(RegonApiClient $client): JsonResponse
{
    ...
    $fullReport = $client->getFullReport(
        $accessKey, 
        '000331501', 
        'BIR11OsPrawna'
    );
    ...
}

```


#### Get summary report

```php
use MMierzynski\GusApi\Client\RegonApiClient;

public function index(RegonApiClient $client): JsonResponse
{
    ...
    $reportDate = (new DateTimeImmutable())->setDate(2014, 11, 7);
    $summaryReport = $client->getSummaryReport(
        $accessKey, 
        'BIR11NowePodmiotyPrawneOrazDzialalnosciOsFizycznych', 
        $reportDate
    );
    ...
}

```