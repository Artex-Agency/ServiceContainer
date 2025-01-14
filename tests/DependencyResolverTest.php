<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Artex\DIContainer\DependencyResolver;
use Artex\DIContainer\ServiceContainer;
use \Tests\Fixtures\DependentClass;
use \Tests\Fixtures\ClassWithOptionalDependency;
use \Tests\Fixtures\SomeDependency;

class DependencyResolverTest extends TestCase
{
    public function testResolveClassWithDependencies(): void
    {
        $container = new ServiceContainer();
        $resolver = new DependencyResolver($container);

        $container->set(SomeDependency::class, fn() => new SomeDependency());
        $container->set(DependentClass::class, fn($c) => $resolver->resolve(DependentClass::class));

        $instance = $container->get(DependentClass::class);

        $this->assertInstanceOf(DependentClass::class, $instance);
        $this->assertInstanceOf(SomeDependency::class, $instance->dependency);
    }

    public function testResolveWithOptionalParameter(): void
    {
        $container = new ServiceContainer();
        $resolver = new DependencyResolver($container);

        // Register the dependency explicitly
        $container->set(SomeDependency::class, fn() => new SomeDependency());

        $container->set(ClassWithOptionalDependency::class, fn($c) => $resolver->resolve(ClassWithOptionalDependency::class));
        $instance = $container->get(ClassWithOptionalDependency::class);

        $this->assertInstanceOf(ClassWithOptionalDependency::class, $instance);
        $this->assertInstanceOf(SomeDependency::class, $instance->optionalDependency);
    }
}
