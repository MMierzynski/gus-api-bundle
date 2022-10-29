<?php
namespace MMierzynski\GusApi\Model\DTO\Request;

class Login
{
    public string $pKluczUzytkownika;

    function __construct(string $pKluczUzytkownika)
    {
        $this->pKluczUzytkownika = $pKluczUzytkownika;
    }
}