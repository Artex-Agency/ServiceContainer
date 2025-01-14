<?php declare(strict_types=1);

namespace Artex\DIContainer;

use InvalidArgumentException;

/**
 * ConfigLoader
 *
 * A lightweight utility for managing configuration settings.
 * Supports loading configuration files and retrieving values with dot notation.
 *
 * @package     Artex\DIContainer
 * @version     1.0.0
 * @author      James Gober <james@artexmail.com>
 * @link        https://artexsoftware.com
 * @license     Apache 2.0 
 * @copyright   © 2025 Artex Agency Inc.
 */
class ConfigLoader
{
    /**
     * The loaded configuration values.
     *
     * @var array<string, mixed>
     */
    private array $config = [];

    /**
     * Load configuration from a PHP file.
     *
     * @param string $path Path to the configuration file.
     * @return array<string, mixed> The loaded configuration array.
     * @throws InvalidArgumentException If the file does not exist or is not readable.
     */
    public function load(string $path): array
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException("Configuration file not found or unreadable: {$path}");
        }

        $loadedConfig = include $path;
        if (!is_array($loadedConfig)) {
            throw new InvalidArgumentException("Configuration file must return an array: {$path}");
        }

        $this->config = array_merge($this->config, $loadedConfig);
        return $this->config;
    }

    /**
     * Retrieve a configuration value using dot notation.
     *
     * @param string $key The configuration key.
     * @param mixed $default The default value if the key does not exist.
     * @return mixed The configuration value or the default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $current = $this->config;

        foreach ($keys as $k) {
            if (!isset($current[$k])) {
                return $default;
            }
            $current = $current[$k];
        }

        return $current;
    }

    /**
     * Set a configuration value using dot notation.
     *
     * @param string $key The configuration key.
     * @param mixed $value The value to set.
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $current = &$this->config;

        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        $current = $value;
    }

    /**
     * Get all configuration values.
     *
     * @return array<string, mixed> The complete configuration array.
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     * Clear all loaded configurations.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->config = [];
    }
}