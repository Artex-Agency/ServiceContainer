<?php declare(strict_types=1);

namespace Artex\DIContainer\DI;

use Artex\DIContainer\ServiceContainer;

class ContextualBindingManager
{
    private array $bindings = [];
    private ServiceContainer $container;

    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Bind a service implementation for a specific context.
     *
     * @param string $abstract The abstract type.
     * @param string $context The context identifier.
     * @param callable $concrete The service factory.
     * @return void
     */
    public function bindContext(string $abstract, string $context, callable $concrete): void
    {
        $this->bindings[$abstract][$context] = $concrete;
    }

    /**
     * Resolve a service with a specific context.
     *
     * @param string $abstract The abstract type.
     * @param string $context The context identifier.
     * @return mixed The resolved service.
     */
    public function resolveWithContext(string $abstract, string $context): mixed
    {
        if (!isset($this->bindings[$abstract][$context])) {
            throw new RuntimeException("No binding found for {$abstract} in context {$context}.");
        }

        return $this->bindings[$abstract][$context]($this->container);
    }
}