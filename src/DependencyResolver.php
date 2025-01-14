<?php declare(strict_types=1);

namespace Artex\DIContainer;

use ReflectionClass;
use ReflectionException;

/**
 * Dependency Resolver
 *
 * Handles autowiring and resolving class dependencies using the service container.
 *
 * @package Artex\DIContainer
 */
class DependencyResolver
{
    /**
     * The container instance.
     *
     * @var ServiceContainer
     */
    private ServiceContainer $container;

    /**
     * Create a new DependencyResolver instance.
     *
     * @param ServiceContainer $container The service container instance.
     */
    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Bind a specific value for a parameter when resolving dependencies.
     *
     * @param string $class The class name.
     * @param string $parameter The parameter name.
     * @param mixed $value The value to bind.
     * @return void
     */
    public function bindParameter(string $class, string $parameter, mixed $value): void
    {
        $this->parameterBindings[$class][$parameter] = $value;
    }

    /**
     * Resolve a class with its dependencies.
     *
     * @param string $class The class name to resolve.
     * @return object The constructed object.
     * @throws ReflectionException If the class cannot be reflected.
     */
    public function resolve(string $class): object
    {
        // Reflect the class
        $reflector = new ReflectionClass($class);
    
        // Check if class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new RuntimeException("Class {$class} is not instantiable.");
        }
    
        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $class;
        }
    
        // Handle parameter bindings
        $dependencies = array_map(function ($parameter) use ($class) {
            $parameterName = $parameter->getName();
            if (isset($this->parameterBindings[$class][$parameterName])) {
                return $this->parameterBindings[$class][$parameterName];
            }
    
            $type = $parameter->getType();
            if ($type && !$type->isBuiltin()) {
                return $this->container->get($type->getName());
            }
    
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }
    
            throw new RuntimeException(
                "Cannot resolve parameter {$parameter->getName()} in class {$class}."
            );
        }, $constructor->getParameters());
    
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve and call a method with its dependencies.
     *
     * @param object $instance The instance on which the method will be called.
     * @param string $method The method name to call.
     * @return mixed The method result.
     * @throws ReflectionException If the method cannot be reflected.
     */
    public function resolveMethod(object $instance, string $method): mixed
    {
        $reflector = new ReflectionClass($instance);
        $methodReflector = $reflector->getMethod($method);

        $dependencies = array_map(function ($parameter) {
            $type = $parameter->getType();
            if ($type && !$type->isBuiltin()) {
                return $this->container->get($type->getName());
            }

            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            throw new \RuntimeException(
                "Cannot resolve parameter {$parameter->getName()} in {$parameter->getDeclaringClass()->getName()}."
            );
        }, $methodReflector->getParameters());

        return $methodReflector->invokeArgs($instance, $dependencies);
    }
}