<?php
namespace MMierzynski\GusApi\Model\DTO\Request;

class Zaloguj
{
    public string $pKluczUzytkownika;

    function __construct(string $pKluczUzytkownika)
    {
        $this->pKluczUzytkownika = $pKluczUzytkownika;
    }
}