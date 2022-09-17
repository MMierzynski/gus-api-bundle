<?php 
namespace MMierzynski\GusApi\Model\DTO\Response;


class DanePobierzPelnyRaportResponse {
    public string $DanePobierzPelnyRaportResult;
    
    public function __construct(string $DanePobierzPelnyRaportResult)
    {
        $this->DanePobierzPelnyRaportResult = $DanePobierzPelnyRaportResult;
    }
}