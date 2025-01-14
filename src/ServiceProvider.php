<?php declare(strict_types=1);

namespace Artex\DIContainer;

use Artex\DIContainer\ServiceContainer;

/**
 * Abstract Service Provider
 *
 * A blueprint for creating service providers to register services and perform
 * additional bootstrapping in the container.
 *
 * @package     Artex\DIContainer
 * @version     1.2.0
 * @author      James Gober <james@artexmail.com>
 * @link        https://artexsoftware.com
 * @license     Apache 2.0 
 * @copyright   © 2025 Artex Agency Inc.
 */
abstract class ServiceProvider
{
    /**
     * The service container instance.
     *
     * @var ServiceContainer
     */
    protected ServiceContainer $container;

    /**
     * Indicates whether this provider's services are deferred.
     *
     * @var bool
     */
    protected bool $deferred = false;

    /**
     * Create a new service provider instance.
     *
     * @param ServiceContainer $container The service container.
     */
    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Register services with the container.
     *
     * This method should define bindings and service definitions in the container.
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Perform any additional bootstrapping after services are registered.
     *
     * @return void
     */
    public function boot(): void
    {
        // Optional boot logic can be implemented in concrete providers.
    }

    /**
     * Determine if the provider's services should be deferred.
     *
     * @return bool True if services are deferred, otherwise false.
     */
    public function isDeferred(): bool
    {
        return $this->deferred;
    }
}