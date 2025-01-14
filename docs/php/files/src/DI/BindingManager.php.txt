<?php declare(strict_types=1);

namespace Artex\DIContainer\DI;

/**
 * BindingManager
 *
 * Manages contextual bindings for services, allowing specific implementations
 * to be resolved based on a given context.
 *
 * @package Artex\DIContainer\DI
 */
class BindingManager
{
    /**
     * The container of contextual bindings.
     *
     * @var array<string, array<string, callable>>
     */
    private array $bindings = [];

    /**
     * Bind a service implementation for a specific context.
     *
     * @param string $abstract The service identifier.
     * @param string $context The context identifier.
     * @param callable $concrete The concrete implementation for the context.
     * @return void
     */
    public function bind(string $abstract, string $context, callable $concrete): void
    {
        $this->bindings[$abstract][$context] = $concrete;
    }

    /**
     * Resolve a service implementation for a specific context.
     *
     * @param string $abstract The service identifier.
     * @param string $context The context identifier.
     * @return mixed The resolved implementation.
     * @throws \RuntimeException If no binding exists for the given context.
     */
    public function resolve(string $abstract, string $context): mixed
    {
        if (!isset($this->bindings[$abstract][$context])) {
            throw new \RuntimeException("No binding found for '{$abstract}' in context '{$context}'.");
        }

        return ($this->bindings[$abstract][$context])();
    }

    /**
     * Check if a binding exists for a specific context.
     *
     * @param string $abstract The service identifier.
     * @param string $context The context identifier.
     * @return bool True if the binding exists, false otherwise.
     */
    public function hasBinding(string $abstract, string $context): bool
    {
        return isset($this->bindings[$abstract][$context]);
    }

    /**
     * Remove a binding for a specific context.
     *
     * @param string $abstract The service identifier.
     * @param string $context The context identifier.
     * @return void
     */
    public function unbind(string $abstract, string $context): void
    {
        unset($this->bindings[$abstract][$context]);
    }

    /**
     * Clear all bindings for a given service.
     *
     * @param string $abstract The service identifier.
     * @return void
     */
    public function clearBindings(string $abstract): void
    {
        unset($this->bindings[$abstract]);
    }

    /**
     * Get all bindings for a given service.
     *
     * @param string $abstract The service identifier.
     * @return array<string, callable> All bindings for the service.
     */
    public function getBindings(string $abstract): array
    {
        return $this->bindings[$abstract] ?? [];
    }
}
