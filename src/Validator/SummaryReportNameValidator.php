<?php
namespace MMierzynski\GusApi\Validator;

use MMierzynski\GusApi\Utils\SummaryReportNames;

class SummaryReportNameValidator implements ValidationInterface
{
    public function validate(mixed $subject): bool
    {
        if (in_array($subject, SummaryReportNames::ALLOWED_REPORT_NAMES)) {
            return true;
        }

        return false;
    }
}