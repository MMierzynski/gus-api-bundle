<?php 
namespace MMierzynski\GusApi\Model\DTO\Response;


class SearchCompanyResponse {
    public string $DaneSzukajPodmiotyResult;
    
    public function __construct(string $DaneSzukajPodmiotyResult)
    {
        $this->DaneSzukajPodmiotyResult = $DaneSzukajPodmiotyResult;
    }
}