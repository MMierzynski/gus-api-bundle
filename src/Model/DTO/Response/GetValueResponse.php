<?php
namespace MMierzynski\GusApi\Model\DTO\Response;

class GetValueResponse {
    public string $GetValueResult;
    
    public function __construct(string $GetValueResult)
    {
        $this->GetValueResult = $GetValueResult;
    }
}