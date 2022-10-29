<?php

namespace MMierzynski\GusApi\Exception;

use Throwable;

class InputValidationException extends \Exception
{
    private const MESSAGE_PATTERN = 'Ivalid input object. Validation errors: %s';

    public function __construct(array $errors = [], int $code = 0, ?Throwable $previous = null) {
        $message = sprintf(self::MESSAGE_PATTERN, json_encode($errors));
        parent::__construct($message, $code, $previous);
    }
}