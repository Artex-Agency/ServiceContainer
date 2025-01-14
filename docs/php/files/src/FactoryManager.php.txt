<?php declare(strict_types=1);

namespace Artex\DIContainer;

/**
 * Factory Manager
 *
 * Allows services to be instantiated via custom factory callbacks.
 *
 * @package Artex\DIContainer
 */
class FactoryManager
{
    /**
     * Registered factories.
     *
     * @var array<string, callable>
     */
    private array $factories = [];

    /**
     * Register a factory for a given service.
     *
     * @param string $abstract The service identifier.
     * @param callable $factory The factory callback.
     * @return void
     */
    public function registerFactory(string $abstract, callable $factory): void
    {
        $this->factories[$abstract] = $factory;
    }

    /**
     * Create an instance using the registered factory.
     *
     * @param string $abstract The service identifier.
     * @param mixed ...$params Additional parameters for the factory.
     * @return mixed
     */
    public function create(string $abstract, ...$params): mixed
    {
        if (!isset($this->factories[$abstract])) {
            throw new \RuntimeException("No factory registered for {$abstract}.");
        }

        return call_user_func($this->factories[$abstract], ...$params);
    }
}