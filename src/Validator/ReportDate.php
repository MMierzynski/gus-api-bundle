<?php

namespace MMierzynski\GusApi\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ReportDate extends Constraint
{
    public $message = 'The value "{{ value }}" is not valid report date. Expected date from the past';
    public $messageInvalidDateFormat = 'The value "{{ value }}" is not valid date format';
}
