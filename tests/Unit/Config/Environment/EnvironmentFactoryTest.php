<?php
declare(strict_types=1);

namespace MMierzynski\GusApi\Tests\Config\Environment;

use MMierzynski\GusApi\Exception\InvalidEnvironmentNameException;
use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Config\Environment\REGON\RegonTestEnvironment;
use PHPUnit\Framework\TestCase;

final class EnvironmentFactoryTest extends TestCase
{
    private EnvironmentFactory $factory;

    public function setUp(): void {
        $this->factory = new EnvironmentFactory();
    }

    public function testCreateEnvironmentWithValidDataShouldFinishedSuccessfully(): void
    {
        $acutal = $this->factory->createEnvironment('regon', 'test');

        $this->assertInstanceOf(RegonTestEnvironment::class, $acutal);
    }

    public function testCreateEnvironmentShouldThrowExceptoionOnWrongEnvName(): void
    {
        $this->expectException(InvalidEnvironmentNameException::class);
        $this->factory->createEnvironment('regon', 'wrongEnvName');
    }

    public function testCreateEnvironmentShouldThrowExceptoionOnEmptyEnvName(): void
    {
        $this->expectException(InvalidEnvironmentNameException::class);
        $this->factory->createEnvironment('regon', '');
    }
    
    public function testCreateEnvironmentShouldThrowExceptoionOnWrongApiName(): void
    {
        $this->expectException(InvalidEnvironmentNameException::class);
        $this->factory->createEnvironment('wrongApiName', 'test');
    }

    public function testCreateEnvironmentShouldThrowExceptoionOnEmptyApiName(): void
    {
        $this->expectException(InvalidEnvironmentNameException::class);
        $this->factory->createEnvironment('', 'test');
    }
}