<?php 
namespace MMierzynski\GusApi\Model\DTO\Response;


class FullReportResponse {
    public string $DanePobierzPelnyRaportResult;
    
    public function __construct(string $DanePobierzPelnyRaportResult)
    {
        $this->DanePobierzPelnyRaportResult = $DanePobierzPelnyRaportResult;
    }
}