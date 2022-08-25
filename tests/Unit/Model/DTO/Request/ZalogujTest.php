<?php
namespace MMierzynski\GusApi\Tests\Unit\Model\DTO\Request;

use MMierzynski\GusApi\Model\DTO\Request\Zaloguj;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;

class ZalogujTest extends TestCase
{
    public function testClassContainsRequiredProperties(): void
    {
        $reflectionClass = new ReflectionClass(Zaloguj::class);
        $actualProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        
        $this->assertIsArray($actualProperties);
        $this->assertCount(1, $actualProperties);
        $this->assertClassHasAttribute('pKluczUzytkownika', Zaloguj::class);
    }
}