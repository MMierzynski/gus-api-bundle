<?php
namespace MMierzynski\GusApi\Query;

class RegonLoginQuery implements QueryInterface
{
    public function __construct()
    {
        
    }
    
    public function getActionName(): string
    {
        return 'Zaloguj';
    }

    public function getActionUrl(): string
    {
        return 'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/Zaloguj';
    }
}