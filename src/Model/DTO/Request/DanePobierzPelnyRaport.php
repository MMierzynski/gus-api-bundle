<?php
namespace MMierzynski\GusApi\Model\DTO\Request;


class DanePobierzPelnyRaport {
    public string $pRegon;
    public string $pNazwaRaportu;

    public function __construct(string $pRegon, string $pNazwaRaportu)
    {
        $this->pRegon = $pRegon;
        $this->pNazwaRaportu = $pNazwaRaportu;
    }
}