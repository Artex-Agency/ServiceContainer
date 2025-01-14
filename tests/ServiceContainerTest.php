<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Artex\DIContainer\ServiceContainer;

class ServiceContainerTest extends TestCase
{
    public function testRegisterAndRetrieveSingleton(): void
    {
        $container = new ServiceContainer();
        $container->set('singleton', fn() => new \stdClass(), true);

        $instance1 = $container->get('singleton');
        $instance2 = $container->get('singleton');

        $this->assertSame($instance1, $instance2);
    }

    public function testRegisterAndRetrieveTransient(): void
    {
        $container = new ServiceContainer();
        $container->set('transient', fn() => new \stdClass(), false);

        $instance1 = $container->get('transient');
        $instance2 = $container->get('transient');

        $this->assertNotSame($instance1, $instance2);
    }

    public function testDeferredServiceResolution(): void
    {
        $container = new ServiceContainer();
        $container->defer('deferred', fn() => new \stdClass());

        $this->assertTrue($container->has('deferred'));
        $instance = $container->get('deferred');
        $this->assertInstanceOf(\stdClass::class, $instance);
    }
}
