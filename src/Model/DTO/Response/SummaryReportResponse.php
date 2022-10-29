<?php 
namespace MMierzynski\GusApi\Model\DTO\Response;


class SummaryReportResponse {
    public string $DanePobierzRaportZbiorczyResult;
    
    public function __construct(string $DanePobierzRaportZbiorczyResult)
    {
        $this->DanePobierzRaportZbiorczyResult = $DanePobierzRaportZbiorczyResult;
    }
}