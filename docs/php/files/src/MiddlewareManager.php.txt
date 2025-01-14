<?php declare(strict_types=1);

namespace Artex\DIContainer;

/**
 * Middleware Manager
 *
 * Applies middleware during the service resolution process.
 *
 * @package Artex\DIContainer
 */
class MiddlewareManager
{
    /**
     * Registered middleware.
     *
     * @var array<callable>
     */
    private array $middleware = [];

    /**
     * Add a middleware callback.
     *
     * @param callable $middleware The middleware callback.
     * @return void
     */
    public function addMiddleware(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Apply middleware to a resolved service.
     *
     * @param string $abstract The service identifier.
     * @param mixed $instance The resolved service instance.
     * @return mixed The modified instance.
     */
    public function applyMiddleware(string $abstract, $instance): mixed
    {
        foreach ($this->middleware as $middleware) {
            $instance = $middleware($abstract, $instance);
        }

        return $instance;
    }
}