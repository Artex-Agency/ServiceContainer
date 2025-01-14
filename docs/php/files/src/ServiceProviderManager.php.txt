<?php declare(strict_types=1);

namespace Artex\DIContainer;

use InvalidArgumentException;

/**
 * Service Provider Manager
 *
 * Handles the registration and bootstrapping of service providers.
 *
 * @package Artex\DIContainer
 */
class ServiceProviderManager
{
    /**
     * The container instance.
     *
     * @var ServiceContainer
     */
    private ServiceContainer $container;

    /**
     * The registered providers.
     *
     * @var array<string, ServiceProvider>
     */
    private array $providers = [];

    /**
     * The booted providers.
     *
     * @var array<string, bool>
     */
    private array $booted = [];

    /**
     * Create a new ServiceProviderManager instance.
     *
     * @param ServiceContainer $container The service container.
     */
    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Register a service provider.
     *
     * @param string $providerClass The fully qualified class name of the provider.
     * @return void
     * @throws InvalidArgumentException If the provider cannot be instantiated.
     */
    public function register(string $providerClass): void
    {
        if (!class_exists($providerClass)) {
            throw new InvalidArgumentException("Service provider class {$providerClass} does not exist.");
        }

        $provider = new $providerClass($this->container);

        if (!$provider instanceof ServiceProvider) {
            throw new InvalidArgumentException("Class {$providerClass} must extend ServiceProvider.");
        }

        $this->providers[$providerClass] = $provider;

        // If the provider is not deferred, register it immediately
        if (!$provider->isDeferred()) {
            $provider->register();
        }
    }

    /**
     * Boot all registered providers.
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->providers as $providerClass => $provider) {
            if (!isset($this->booted[$providerClass])) {
                $provider->boot();
                $this->booted[$providerClass] = true;
            }
        }
    }

    /**
     * Get a registered provider by class name.
     *
     * @param string $providerClass The class name of the provider.
     * @return ServiceProvider|null The provider instance or null if not registered.
     */
    public function getProvider(string $providerClass): ?ServiceProvider
    {
        return $this->providers[$providerClass] ?? null;
    }
}