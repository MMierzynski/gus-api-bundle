<?php
namespace MMierzynski\GusApi\Model\DTO;

use phpDocumentor\Reflection\Types\Null_;

class Report 
{
    private string $reportName;

    private array $reportData;

    public function getReportName(): string|null
    {
        return $this->reportName;
    }
    
    public function setReportName(string $name): self
    {
        $this->reportName = $name;

        return $this;
    }

    public function getReportData(): array|null
    {
        return $this->reportData;
    }
    
    public function setReportData(array $report): self
    {
        $this->reportData = $report;

        return $this;
    }
}