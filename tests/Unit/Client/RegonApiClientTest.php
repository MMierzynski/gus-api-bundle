<?php
declare(strict_types=1);

namespace MMierzynski\GusApi\Tests\Unit\Client;

use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Config\Environment\REGON\RegonTestEnvironment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RegonApiClientTest extends TestCase
{
    public function testCreateValidClientObject(): void
    {
        /** @var MockObject */
        $envFactoryMock = $this->createMock(EnvironmentFactory::class);
        $envFactoryMock->expects($this->once())
            ->method('createEnvironment')
            ->willReturn(new RegonTestEnvironment());

        $actual = new RegonApiClient('test', $envFactoryMock);

        $this->assertNotNull($actual->getEnvironment());
        $this->assertInstanceOf(RegonTestEnvironment::class, $actual->getEnvironment());
    }
}