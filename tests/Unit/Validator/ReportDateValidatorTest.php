<?php
namespace MMierzynski\GusApi\Tests\Unit\Validator;

use DateTime;
use MMierzynski\GusApi\Validator\ReportDate;
use MMierzynski\GusApi\Validator\ReportDateValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ReportDateValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new ReportDateValidator();
    }

    public function test_null_is_valid(): void
    {
        //act
        $this->validator->validate(null, new ReportDate());
        
        // assert
        $this->assertNoViolation();
    }

    public function test_validate_invalid_date_string_returns_violation(): void
    {
        // arrange
        $date_string = 'invalid_date_string';
        $constraint = new ReportDate();

        // act
        $this->validator->validate($date_string, $constraint);

        //assert
        $this->buildViolation($constraint->messageInvalidDateFormat)
            ->setParameter('{{ value }}', $date_string)
            ->assertRaised();
    }

    public function test_future_date_returns_violation(): void
    {
        //arrange
        $date = (new DateTime('tomorrow'))->format('Y-m-d');
        $constraint = new ReportDate();

        //act 
        $this->validator->validate($date, $constraint);

        //assert
        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $date)
            ->assertRaised();
    }

    public function test_valida_date_returns_no_violations(): void
    {
        //arrange
        $date = (new DateTime('now'))->format('Y-m-d');
        $constraint = new ReportDate();

        //act 
        $this->validator->validate($date, $constraint);

        // assert
        $this->assertNoViolation();
    }
}