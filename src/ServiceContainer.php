<?php declare(strict_types=1);

namespace Artex\DIContainer;

use Closure;
use Psr\Container\ContainerInterface;
use Artex\DIContainer\DI\ContextualBindingManager;
use RuntimeException;
use InvalidArgumentException;

#    ¸___¸____¸________¸___¸_¸  ¸__
#   ╱    ┊  __ ╲_¸  ¸_╱  __]  ╲╱  ╱
#  |  !  ┊     ╱ ┊  ┊ ┊  __]}    { 
#  |__Λ__|__\__╲ [__] |____]__╱╲__╲ 
#  ARTEX SOFTWARE ⦙⦙⦙⦙⦙⦙ PHP ESSENTIALS
# ---------------------------------------------------
/**
 * Service Container
 * 
 * PSR-11 compliant service container supporting lazy 
 * loading, singletons, transient services, deferred 
 * services, tagged services, and lifecycle hooks.
 *  
 * @link  https://github.com/artex-agency/di-container
 * 
 * @package     Artex\DIContainer
 * @version     1.0.0
 * @author      James Gober <james@artexmail.com>
 * @link        https://artexsoftware.com
 * @license     Apache 2.0 
 * @copyright   © 2025 Artex Agency Inc.
 */
class ServiceContainer implements ContainerInterface
{
    /**
     * Singleton instance of the service container.
     *
     * @var ServiceContainer|null
     */
    private static ?ServiceContainer $instance = null;

    /**
     * Service definitions.
     *
     * @var array<string, array{concrete: callable|object, shared: bool}>
     */
    private array $services = [];

    /**
     * Cached singleton instances.
     *
     * @var array<string, mixed>
     */
    private array $instances = [];

    /**
     * Deferred service definitions.
     *
     * @var array<string, callable>
     */
    private array $deferred = [];

    /**
     * Tagged services.
     *
     * @var array<string, array<string, bool>>
     */
    private array $tags = [];

    /**
     * Managers for extended functionality.
     *
     * @var FactoryManager
     */
    private FactoryManager $factoryManager;

    /**
     * @var MiddlewareManager
     */
    private MiddlewareManager $middlewareManager;

    /**
     * @var ScopeManager
     */
    private ScopeManager $scopeManager;

    /**
     * @var ContextualBindingManager
     */
    private ContextualBindingManager $contextualBindingManager;

    /**
     * Constructor
     *
     * Initializes all managers and components.
     */
    public function __construct()
    {
        $this->factoryManager = new FactoryManager();
        $this->middlewareManager = new MiddlewareManager();
        $this->scopeManager = new ScopeManager();
        $this->contextualBindingManager = new ContextualBindingManager($this);
    }

    /**
     * Get the singleton instance of the container.
     *
     * @return ServiceContainer
     */
    public static function getInstance(): ServiceContainer
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Defer the registration of a service until it is first accessed.
     *
     * @param string $id The service identifier.
     * @param callable $factory The service factory.
     * @return void
     */
    public function defer(string $id, callable $factory): void
    {
        $this->deferred[$id] = $factory;
    }

    /**
     * Register a service in the container.
     *
     * @param string $id Service identifier.
     * @param callable|object $concrete The service definition or instance.
     * @param bool $shared Whether the service is a singleton.
     * @return void
     */
    public function set(string $id, callable|object $concrete, bool $shared = true): void
    {
        $this->services[$id] = ['concrete' => $concrete, 'shared' => $shared];
    }

    /**
     * Retrieve a service by its identifier.
     *
     * @param string $id Service identifier.
     * @return mixed The resolved service instance.
     * @throws RuntimeException If the service is not registered.
     */
    public function get(string $id): mixed
    {
        // Resolve deferred service if it exists
        if (isset($this->deferred[$id])) {
            $this->set($id, $this->deferred[$id]($this), true);
            unset($this->deferred[$id]);
        }

        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->services[$id])) {
            throw new RuntimeException("Service '{$id}' is not registered.");
        }

        $definition = $this->services[$id];
        $instance = is_callable($definition['concrete'])
            ? $definition['concrete']($this)
            : $definition['concrete'];

        if ($definition['shared']) {
            $this->instances[$id] = $instance;
        }

        return $this->applyMiddleware($id, $instance);
    }

    /**
     * Check if a service is registered or deferred.
     *
     * @param string $id Service identifier.
     * @return bool True if the service is registered or deferred, false otherwise.
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]) || isset($this->deferred[$id]);
    }

    /**
     * Remove a service from the container.
     *
     * @param string $id The service identifier.
     * @return void
     */
    public function remove(string $id): void
    {
        unset($this->services[$id], $this->instances[$id], $this->deferred[$id]);
    }

    /**
     * Clear all registered services, instances, and deferred services.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->services = [];
        $this->instances = [];
        $this->deferred = [];
        $this->tags = [];
    }

    /**
     * Apply middleware to a resolved service instance.
     *
     * @param string $abstract The service identifier.
     * @param mixed $instance The resolved instance.
     * @return mixed The modified instance.
     */
    private function applyMiddleware(string $abstract, $instance): mixed
    {
        return $this->middlewareManager->applyMiddleware($abstract, $instance);
    }

    /**
     * Add middleware for service resolution.
     *
     * @param callable $middleware Middleware callback.
     * @return void
     */
    public function addMiddleware(callable $middleware): void
    {
        $this->middlewareManager->addMiddleware($middleware);
    }

    /**
     * Register a factory for a service.
     *
     * @param string $abstract The service identifier.
     * @param callable $factory The factory callback.
     * @return void
     */
    public function registerFactory(string $abstract, callable $factory): void
    {
        $this->factoryManager->registerFactory($abstract, $factory);
    }

    /**
     * Create a service using a factory.
     *
     * @param string $abstract The service identifier.
     * @param mixed ...$params Additional factory parameters.
     * @return mixed The created service.
     */
    public function createFromFactory(string $abstract, ...$params): mixed
    {
        $instance = $this->factoryManager->create($abstract, ...$params);
        return $this->applyMiddleware($abstract, $instance);
    }

    /**
     * Define a service scope.
     *
     * @param string $scope The scope identifier.
     * @param callable $resolver Resolver for the scope.
     * @return void
     */
    public function defineScope(string $scope, callable $resolver): void
    {
        $this->scopeManager->defineScope($scope, $resolver);
    }

    /**
     * Resolve a service within a specific scope.
     *
     * @param string $scope The scope identifier.
     * @param string $abstract The service identifier.
     * @return mixed The scoped service instance.
     */
    public function resolveInScope(string $scope, string $abstract): mixed
    {
        return $this->scopeManager->resolveInScope($scope, $abstract);
    }

    /**
     * Bind a service implementation for a specific context.
     *
     * @param string $abstract The service identifier.
     * @param string $context The context identifier.
     * @param callable $concrete The service factory.
     * @return void
     */
    public function bindContext(string $abstract, string $context, callable $concrete): void
    {
        $this->contextualBindingManager->bindContext($abstract, $context, $concrete);
    }

    /**
     * Resolve a service for a specific context.
     *
     * @param string $abstract The service identifier.
     * @param string $context The context identifier.
     * @return mixed The resolved service instance.
     */
    public function resolveWithContext(string $abstract, string $context): mixed
    {
        return $this->contextualBindingManager->resolveWithContext($abstract, $context);
    }

    /**
     * Tag a service with a specific tag.
     *
     * @param string $id Service identifier.
     * @param string $tag The tag to assign.
     * @return void
     */
    public function tag(string $id, string $tag): void
    {
        $this->tags[$tag][$id] = true;
    }

    /**
     * Retrieve all services with a specific tag.
     *
     * @param string $tag The tag identifier.
     * @return array<string, mixed> The tagged services.
     */
    public function getByTag(string $tag): array
    {
        $services = [];
        foreach ($this->tags[$tag] ?? [] as $id => $_) {
            $services[$id] = $this->get($id);
        }
        return $services;
    }
}