<?php
namespace MMierzynski\GusApi\Validator;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReportInputValidator 
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(mixed $input, array $constraints): array
    {
        foreach ($constraints as $constraint) {
            $errors = $this->validateProperty($input, $constraint);

            if (count($errors)) {
                return $errors;
            }
        }

        return [];
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateProperty(mixed $input, array $constraint): array {
        $errors = [];

        foreach ($constraint as $property => $constraintObject) {
            $this->isValidConstraintObject($constraintObject);
            $this->isValidInputPropertyName($input, $property);

            $violation = $this->validator->validate($input->$property, $constraint);
            if ($violation->count()) {
                $errors[$property] = $this->parseViolationList($violation);
            }
        }

        return $errors;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function isValidConstraintObject(mixed $constraintObject) {
        if (!$constraintObject instanceof Constraint) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid type of constraint object in constraints argument, expected "%s" found %s instead', 
                    Constraint::class, 
                    get_class($constraintObject)
                )
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function isValidInputPropertyName(mixed $input, string $property) {
        if (!property_exists($input, $property)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid property name in constraints argument', 
                )
            );
        }
    }

    private function parseViolationList(ConstraintViolationList $violation): array {
        return array_map(fn($error) => $error->getMessage(), iterator_to_array($violation->getIterator()));
    }
}