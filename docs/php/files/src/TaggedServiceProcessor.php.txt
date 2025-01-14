<?php declare(strict_types=1);

namespace Artex\DIContainer;

use Artex\DIContainer\ServiceContainer;

/**
 * Tagged Service Processor
 *
 * Provides functionality for processing services tagged in the container.
 *
 * @package Artex\DIContainer
 */
class TaggedServiceProcessor
{
    /**
     * Process all services with a given tag.
     *
     * @param string $tag The tag to process.
     * @param ServiceContainer $container The container instance.
     * @param callable $callback A callback function to process each service.
     *                           The callback signature should be:
     *                           `function(mixed $service, string $id): void`
     * @return void
     */
    public function process(string $tag, ServiceContainer $container, callable $callback): void
    {
        $taggedServices = $container->getByTag($tag);

        foreach ($taggedServices as $id => $service) {
            $callback($service, $id);
        }
    }

    /**
     * Retrieve all services tagged with a specific tag.
     *
     * @param string $tag The tag to look for.
     * @param ServiceContainer $container The container instance.
     * @return array<string, mixed> An array of service IDs and their instances.
     */
    public function getServicesByTag(string $tag, ServiceContainer $container): array
    {
        return $container->getByTag($tag);
    }
}