<?php
namespace MMierzynski\GusApi\Tests\Unit\Validator;

use MMierzynski\GusApi\Utils\FullReportNames;
use MMierzynski\GusApi\Utils\ReportType;
use MMierzynski\GusApi\Utils\SummaryReportNames;
use MMierzynski\GusApi\Validator\ReportName;
use MMierzynski\GusApi\Validator\ReportNameValidator;
use stdClass;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ReportNameValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new ReportNameValidator();
    }

    public function test_validate_with_non_string_value_returns_violation(): void
    {
        // arrange
        $constraint = new ReportName(ReportType::TYPE_REGON_FULL);
        $invalidInputValue = new stdClass();

        // act
        $this->validator->validate($invalidInputValue, $constraint);

        //assert
        $this->buildViolation($constraint->messageInvalidType)
            ->assertRaised();
        
    }

    /**
     * @dataProvider reportNameProvider
     */
    public function test_validate_with_invalid_report_name_returns_violation(string $reportType): void
    {
        // arrange 
        $constraint = new ReportName($reportType);
        $invalidInputValue = 'invalid_report_name';

        // act
        $this->validator->validate($invalidInputValue, $constraint);

        // assert
        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $invalidInputValue)
            ->assertRaised();
    }

    /**
     * @dataProvider validReportNameProvider
     */
    public function test_validate_with_valid_report_name(string $reportType, string $reportName): void
    {
        // arrange
        $constraint = new ReportName($reportType);

        // act
        $this->validator->validate($reportName, $constraint);

        // assert
        $this->assertNoViolation();
    }


    public function reportNameProvider(): array
    {
        return [
            [ReportType::TYPE_REGON_FULL],
            [ReportType::TYPE_REGON_SUMMARY]
        ];
    }

    public function validReportNameProvider(): array
    {
        return [
            [ReportType::TYPE_REGON_FULL, FullReportNames::ALLOWED_REPORT_NAMES[0]],
            [ReportType::TYPE_REGON_SUMMARY, SummaryReportNames::ALLOWED_REPORT_NAMES[0]]
        ];
    }
}