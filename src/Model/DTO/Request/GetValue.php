<?php
namespace MMierzynski\GusApi\Model\DTO\Request;

class GetValue
{
    public string $pNazwaParametru;
    public function __construct(string $pNazwaParametru)
    {
        $this->pNazwaParametru = $pNazwaParametru;
    }
}