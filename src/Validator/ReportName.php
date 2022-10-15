<?php

namespace MMierzynski\GusApi\Validator;

use MMierzynski\GusApi\Utils\ReportType;
use MMierzynski\GusApi\Utils\SummaryReportNames;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ReportName extends Constraint
{
    private string $reportType;

    public $message = 'The value "{{ value }}" is not valid report name';
    public string $messageInvalidType = 'Report name must be type of string';

    public function __construct(string $reportType, mixed $options = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);
        $this->reportType = $reportType;
    }

    public function getValidReportNames(): array
    {
        return match($this->reportType) {
            ReportType::TYPE_REGON_SUMMARY => SummaryReportNames::ALLOWED_REPORT_NAMES,
            ReportType::TYPE_REGON_FULL => [],
            default => []
        };
    }
}
