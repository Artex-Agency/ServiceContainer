<?php declare(strict_types=1);

namespace Artex\DIContainer;

/**
 * Configurable Resolver
 *
 * Resolves dependencies using configuration values.
 *
 * @package Artex\DIContainer
 */
class ConfigurableResolver
{
    /**
     * Configuration data.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * Create a new ConfigurableResolver instance.
     *
     * @param array<string, mixed> $config Configuration values.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Resolve a class with configuration bindings.
     *
     * @param string $class The class name.
     * @return object The constructed object.
     */
    public function resolve(string $class): object
    {
        $reflector = new \ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new \RuntimeException("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $class;
        }

        $dependencies = array_map(function ($parameter) {
            $name = $parameter->getName();

            return $this->config[$name] ?? (
                $parameter->isDefaultValueAvailable()
                    ? $parameter->getDefaultValue()
                    : throw new \RuntimeException("Missing configuration for parameter {$name}.")
            );
        }, $constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }
}