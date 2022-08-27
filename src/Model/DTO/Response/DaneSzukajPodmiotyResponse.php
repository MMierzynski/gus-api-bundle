<?php 
namespace MMierzynski\GusApi\Model\DTO\Response;


class DaneSzukajPodmiotyResponse {
    public string $DaneSzukajPodmiotyResult;
    
    public function __construct(string $DaneSzukajPodmiotyResult)
    {
        $this->DaneSzukajPodmiotyResult = $DaneSzukajPodmiotyResult;
    }
}