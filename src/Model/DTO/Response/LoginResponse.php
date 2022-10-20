<?php
namespace MMierzynski\GusApi\Model\DTO\Response;

class LoginResponse implements LoginResponseInterface
{
    public string $ZalogujResult;

    function __construct(string $ZalogujResult)
    {
        $this->ZalogujResult = $ZalogujResult;
    }

    public function getAccessKey(): string
    {
        return $this->ZalogujResult;
    }
}