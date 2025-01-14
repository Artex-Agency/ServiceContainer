<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Artex\DIContainer\ServiceProvider;
use Artex\DIContainer\ServiceContainer;

class ServiceProviderTest extends TestCase
{
    public function testRegisterServices(): void
    {
        $container = new ServiceContainer();
    
        $provider = new class($container) extends ServiceProvider {
            public function register(): void
            {
                $this->container->set('service', fn() => new \stdClass());
            }
        };
    
        $provider->register();
    
        $this->assertTrue($container->has('service'));
        $this->assertInstanceOf(\stdClass::class, $container->get('service'));
    }
}
