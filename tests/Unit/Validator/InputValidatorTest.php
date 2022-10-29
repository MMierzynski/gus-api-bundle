<?php
namespace MMierzynski\GusApi\Tests\Unit\Validator;

use InvalidArgumentException;
use MMierzynski\GusApi\Model\DTO\Request\Login;
use MMierzynski\GusApi\Validator\InputValidator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InputValidatorTest extends TestCase
{
    public function test_validation_with_empty_constraints(): void 
    {
        
        // arrange
        $validationInterfaceStub = $this->createValidationStub([]);
        $validator = new InputValidator($validationInterfaceStub);
        $input = new Login('test_key');

        // act
        $result = $validator->validate($input, []);

        //assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_validation_with_unexisting_input_property_in_constraint(): void 
    {
        // arrange
        $validationInterfaceStub = $this->createValidationStub([]);
        $validator = new InputValidator($validationInterfaceStub);
        $input = new Login('test_key');

        // act 
        // assert
        $this->expectException(InvalidArgumentException::class);
        $validator->validate($input, [
            ['notexisting_property' => new NotBlank()]
        ]);
    }

    /**
     * @dataProvider invalidConstraintTypeProvider
     */
    public function test_validation_with_invalid_constraint_type_in_list(mixed $constriantObject): void 
    {
        // arrange
        $validationInterfaceStub = $this->createValidationStub([]);
        $validator = new InputValidator($validationInterfaceStub);
        $input = new Login('test_key');

        // act 
        // assert
        $this->expectException(InvalidArgumentException::class);
        $validator->validate($input, [
            ['notexisting_property' => $constriantObject]
        ]);
    }

    public function test_validation_with_one_passing_constaint(): void 
    {
        // arrange
        $validationInterfaceStub = $this->createValidationStub([null]);
        $validator = new InputValidator($validationInterfaceStub);
        $input = new Login('test_key');

        // act 
        $result = $validator->validate($input, [
            ['pKluczUzytkownika' => new NotBlank()]
        ]);

        // assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_validation_with_both_constraints_passing(): void 
    {
        // arrange
        $validationInterfaceStub = $this->createValidationStub([null, null]);
        $validator = new InputValidator($validationInterfaceStub);
        $input = new Login('test_key');

        // act 
        $result = $validator->validate($input, [
            ['pKluczUzytkownika' => new NotNull()],
            ['pKluczUzytkownika' => new NotBlank()]
        ]);

        // assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_validation_with_second_constaint_failing(): void 
    {
        // arrange
        $validationInterfaceStub = $this->createValidationStub(
            [
                null, 
                'This value should not be blank.'
            ]
        );
        $validator = new InputValidator($validationInterfaceStub);
        $input = new Login('');

        // act 
        $result = $validator->validate($input, [
            ['pKluczUzytkownika' => new NotNull()],
            ['pKluczUzytkownika' => new NotBlank()]
        ]);

        // assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('pKluczUzytkownika', $result);
        
        $violation = $result['pKluczUzytkownika'];
        $this->assertIsArray($violation);
        $this->assertCount(1, $violation);
        $this->assertEquals('This value should not be blank.', $violation[0]);
    }

    public function test_validation_with_both_constaints_failing(): void 
    {
        // arrange
        $validationInterfaceStub = $this->createValidationStub(
            [
                'This value should not be blank.',
                'This value is too short.'
            ]
        );
        $validator = new InputValidator($validationInterfaceStub);
        $input = new Login('');

        // act 
        $result = $validator->validate($input, [
            ['pKluczUzytkownika' => new NotBlank()],
            ['pKluczUzytkownika' => new Length(10)]
        ]);

        // assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('pKluczUzytkownika', $result);
        
        $violation = $result['pKluczUzytkownika'];
        $this->assertIsArray($violation);
        $this->assertCount(1, $violation);
        $this->assertEquals('This value should not be blank.', $violation[0]);
    }


    public function invalidConstraintTypeProvider(): array 
    {
        return [
            [new stdClass()],
            [new Login('test')],
            [[]],
            [123]
        ];
    }

    private function createValidationStub(?array $messages): ValidatorInterface|MockObject {
        /** @var MockObject|ValidatorInterface */
        $validator = $this->createMock(ValidatorInterface::class);

        $invocationMocker = $validator->method('validate');

        if (!empty($parameters)) {
            $invocationMocker->with($parameters);
        } else {
            $invocationMocker->withAnyParameters();
        }
        
        $return = [];

        foreach ($messages as $message) { 
            $violationList = $message ? [$this->createViolation($message)] : [];
            $return[] = new ConstraintViolationList($violationList);
        }
        
        $invocationMocker->willReturnOnConsecutiveCalls(...$return);
    
        
        return $validator;
    }

    private function createViolation(string $message): ConstraintViolation
    {
        return new ConstraintViolation(
            message: $message,
            messageTemplate: $message,
            parameters: [],
            root: '',
            propertyPath: '',
            invalidValue: ''
        );
    }
}