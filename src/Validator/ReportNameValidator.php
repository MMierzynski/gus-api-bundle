<?php

namespace MMierzynski\GusApi\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ReportNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ReportName) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        } 

        if (!is_string($value)) {
            $this->context->buildViolation($constraint->messageInvalidType)
            ->addViolation();

            return;
        }

        if (!in_array($value, $constraint->getValidReportNames())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
