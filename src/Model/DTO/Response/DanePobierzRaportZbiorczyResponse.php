<?php 
namespace MMierzynski\GusApi\Model\DTO\Response;


class DanePobierzRaportZbiorczyResponse {
    public string $DanePobierzRaportZbiorczyResult;
    
    public function __construct(string $DanePobierzRaportZbiorczyResult)
    {
        $this->DanePobierzRaportZbiorczyResult = $DanePobierzRaportZbiorczyResult;
    }
}