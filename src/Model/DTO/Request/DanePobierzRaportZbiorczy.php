<?php
namespace MMierzynski\GusApi\Model\DTO\Request;


class DanePobierzRaportZbiorczy {
    public string $pDataRaportu;
    public string $pNazwaRaportu;

    public function __construct(string $pDataRaportu, string $pNazwaRaportu)
    {
        $this->pDataRaportu = $pDataRaportu;
        $this->pNazwaRaportu = $pNazwaRaportu;
    }
}