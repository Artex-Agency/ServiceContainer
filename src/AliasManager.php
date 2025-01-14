<?php declare(strict_types=1);

namespace Artex\DIContainer;

/**
 * Alias Manager
 *
 * Manages aliases for services, allowing multiple identifiers for the same service.
 *
 * @package Artex\DIContainer
 */
class AliasManager
{
    /**
     * Service aliases.
     *
     * @var array<string, string>
     */
    private array $aliases = [];

    /**
     * Add an alias for a service.
     *
     * @param string $alias The alias name.
     * @param string $abstract The actual service identifier.
     * @return void
     */
    public function addAlias(string $alias, string $abstract): void
    {
        $this->aliases[$alias] = $abstract;
    }

    /**
     * Resolve the actual service identifier from an alias.
     *
     * @param string $alias The alias name.
     * @return string The actual service identifier.
     */
    public function resolveAlias(string $alias): string
    {
        return $this->aliases[$alias] ?? $alias;
    }
}