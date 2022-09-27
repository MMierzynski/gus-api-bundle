<?php
namespace MMierzynski\GusApi\Validator;

interface ValidationInterface
{
    public function validate(mixed $subject): bool;
}