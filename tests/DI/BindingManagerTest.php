<?php declare(strict_types=1);

namespace Tests\DI;

use PHPUnit\Framework\TestCase;
use Artex\DIContainer\DI\BindingManager;

class BindingManagerTest extends TestCase
{
    public function testBindAndResolve(): void
    {
        $manager = new BindingManager();

        $manager->bind('service', 'context', fn() => 'Hello, Context!');
        $resolved = $manager->resolve('service', 'context');

        $this->assertSame('Hello, Context!', $resolved);
    }

    public function testHasBinding(): void
    {
        $manager = new BindingManager();

        $manager->bind('service', 'context', fn() => 'Hello, Context!');
        $this->assertTrue($manager->hasBinding('service', 'context'));
        $this->assertFalse($manager->hasBinding('service', 'missing-context'));
    }

    public function testUnbind(): void
    {
        $manager = new BindingManager();

        $manager->bind('service', 'context', fn() => 'Hello, Context!');
        $manager->unbind('service', 'context');

        $this->assertFalse($manager->hasBinding('service', 'context'));
    }

    public function testClearBindings(): void
    {
        $manager = new BindingManager();

        $manager->bind('service', 'context1', fn() => 'Hello, Context 1!');
        $manager->bind('service', 'context2', fn() => 'Hello, Context 2!');
        $manager->clearBindings('service');

        $this->assertFalse($manager->hasBinding('service', 'context1'));
        $this->assertFalse($manager->hasBinding('service', 'context2'));
    }
}
