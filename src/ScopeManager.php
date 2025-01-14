<?php declare(strict_types=1);

namespace Artex\DIContainer;

/**
 * Scope Manager
 *
 * Handles scoped instances, such as per-request or session services.
 *
 * @package Artex\DIContainer
 */
class ScopeManager
{
    /**
     * Defined scopes and their resolvers.
     *
     * @var array<string, callable>
     */
    private array $scopes = [];

    /**
     * Define a new scope with a resolver callback.
     *
     * @param string $scope The scope identifier.
     * @param callable $resolver The resolver for the scope.
     * @return void
     */
    public function defineScope(string $scope, callable $resolver): void
    {
        $this->scopes[$scope] = $resolver;
    }

    /**
     * Resolve a service within a specific scope.
     *
     * @param string $scope The scope identifier.
     * @param string $abstract The service identifier.
     * @return mixed
     */
    public function resolveInScope(string $scope, string $abstract): mixed
    {
        if (!isset($this->scopes[$scope])) {
            throw new \RuntimeException("Scope {$scope} is not defined.");
        }

        return $this->scopes[$scope]($abstract);
    }
}