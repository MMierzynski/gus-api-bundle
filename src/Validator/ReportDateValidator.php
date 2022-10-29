<?php

namespace MMierzynski\GusApi\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ReportDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ReportDate) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        $nowTimestamp = time();
        $reportTimestamp = strtotime($value);

        if (!$reportTimestamp) {
            $this->context->buildViolation($constraint->messageInvalidDateFormat)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

        if ($nowTimestamp <= $reportTimestamp) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        } 
    }
}
