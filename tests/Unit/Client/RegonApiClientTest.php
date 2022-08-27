<?php
declare(strict_types=1);

namespace MMierzynski\GusApi\Tests\Unit\Client;

use MMierzynski\GusApi\Client\RegonApiClient;
use MMierzynski\GusApi\Config\Environment\EnvironmentFactory;
use MMierzynski\GusApi\Config\Environment\REGON\RegonTestEnvironment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

final class RegonApiClientTest extends TestCase
{
    public function testCreateValidClientObject(): void
    {
        /** @var MockObject */
        $envFactoryMock = $this->createMock(EnvironmentFactory::class);
        $envFactoryMock->expects($this->once())
            ->method('createEnvironment')
            ->willReturn(new RegonTestEnvironment());

        $parameterBagMock = $this->createMock(ParameterBag::class);


        $actual = new RegonApiClient('test', $envFactoryMock, $parameterBagMock);

        $this->assertNotNull($actual->getEnvironment());
        $this->assertInstanceOf(RegonTestEnvironment::class, $actual->getEnvironment());
    }
}