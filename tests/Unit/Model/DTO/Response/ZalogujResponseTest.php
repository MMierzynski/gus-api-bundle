<?php
namespace MMierzynski\GusApi\Tests\Unit\Model\DTO\Response;

use MMierzynski\GusApi\Model\DTO\Response\ZalogujResponse;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;


class ZalogujResponseTest extends TestCase
{
    public function testClassContainsRequiredProperties(): void
    {
        $reflectionClass = new ReflectionClass(ZalogujResponse::class);
        $actualProperties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        
        $this->assertIsArray($actualProperties);
        $this->assertCount(1, $actualProperties);
        $this->assertClassHasAttribute('ZalogujResult', ZalogujResponse::class);
    }
}