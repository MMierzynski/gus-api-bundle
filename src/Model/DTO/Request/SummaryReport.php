<?php
namespace MMierzynski\GusApi\Model\DTO\Request;

use MMierzynski\GusApi\Validator\SummaryReportName;
use Symfony\Component\Validator\Constraints\NotBlank;

class SummaryReport {
    public string $pDataRaportu;
    public string $pNazwaRaportu;

    public function __construct(string $pDataRaportu, string $pNazwaRaportu)
    {
        $this->pDataRaportu = $pDataRaportu;
        $this->pNazwaRaportu = $pNazwaRaportu;
    }
}